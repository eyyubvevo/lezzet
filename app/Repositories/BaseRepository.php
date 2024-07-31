<?php

namespace App\Repositories;

use App\Contracts\BaseInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository implements BaseInterface
{
    protected Model $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $filters
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @param array $relations
     * @param int|null $perPage
     * @param int|null $limit
     * @param bool $withTrashed
     * @param string $trashed
     * @param array $joins
     * @param bool $useCache
     * @return Collection|LengthAwarePaginator
     * @throws \Exception
     */
    public function all(array $filters = [], array $columns = ['*'], string $orderBy = 'id', string $sortBy = 'desc', array $relations = [], ?int $perPage = null, ?int $limit = null, bool $withTrashed = false, string $trashed = 'no', array $joins = [], bool $useCache = true): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        try {
            $executeAll = function () use ($filters, $columns, $orderBy, $sortBy, $relations, $perPage, $limit, $withTrashed, $trashed, $joins) {
                $query = $this->buildQuery(columns: $columns, trashed: $trashed, relations: $relations, joins: $joins, withTrashed: $withTrashed, orderBy: $orderBy, sortBy: $sortBy);
                $query = $this->applyFilters(query: $query, filters: $filters);
                return $this->getResults(query: $query, perPage: $perPage, limit: $limit);
            };
            $cacheKey = $this->getCacheKey(args: func_get_args(), methodName: 'all');
            return $useCache ? Cache::remember($cacheKey, now()->addMinutes(10), $executeAll) : $executeAll();
        } catch (\Exception $e) {
            Log::error('Error in BaseRepository@all: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array $conditions
     * @param string $keyword
     * @param int|null $perPage
     * @param int|null $limit
     * @param string $orderBy
     * @param string $sortBy
     * @param array $relations
     * @param array $columns
     * @param bool $withTrashed
     * @param array $joins
     * @param string $trashed
     * @param bool $useCache
     * @return LengthAwarePaginator|Collection
     * @throws \Exception
     */
    public function search(array $conditions, string $keyword, ?int $perPage = null, ?int $limit = null, string $orderBy = 'id', string $sortBy = 'desc', array $relations = [], array $columns = ['*'], bool $withTrashed = false, array $joins = [], string $trashed = 'no', bool $useCache = false): \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
    {
        try {
            $executeQuery = function () use ($conditions, $trashed, $keyword, $relations, $columns, $joins, $orderBy, $sortBy, $withTrashed, $limit, $perPage) {
                $query = $this->buildQuery(columns: $columns, trashed: $trashed, relations: $relations, joins: $joins, withTrashed: $withTrashed, orderBy: $orderBy, sortBy: $sortBy);
                $query = $this->applyConditions(query: $query, conditions: $conditions);
                return $this->getResults(query: $query, perPage: $perPage, limit: $limit);
            };
            if ($useCache) {
                $cacheKey = $this->getCacheKey(func_get_args(), 'search');
                return Cache::remember($cacheKey, now()->addMinutes(10), $executeQuery);
            }
            return $executeQuery();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database Query Error in BaseRepository@search: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error in BaseRepository@search: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array $data
     * @param array $relatedData
     * @return Model
     * @throws \Exception
     */
    public function create(array $data, array $relatedData = []): \Illuminate\Database\Eloquent\Model
    {
        DB::beginTransaction();
        try {
            $model = $this->model->create($data);
            if (!empty($relatedData)) {
                foreach ($relatedData as $relationMethod => $relationData) {
                    if (!method_exists($model, $relationMethod)) {
                        throw new \InvalidArgumentException("Invalid relation method: $relationMethod");
                    } else {
                        $model->$relationMethod()->createMany($relationData);
                    }
                }
            }
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in BaseRepository@create: ' . $e->getMessage());
            throw new \Exception('Error creating model in BaseRepository.' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @param int $id
     * @param array $filters
     * @param array $data
     * @param callable|null $callback
     * @param callable|null $filler
     * @param array $relatedData
     * @return Model
     * @throws \Exception
     */
    public function update(int $id, array $filters = [], array $data = [], ?callable $callback = null, ?callable $filler = null, array $relatedData = []): Model
    {
        DB::beginTransaction();
        try {
            $query = $this->model;
            if (!empty($filters)) {
                $query = $query->where($filters);
            }
            $model = $query->findOrFail($id);
            $data = is_callable($callback) ? $callback($data, $model) : $data;
            if (is_callable($filler)) {
                $model = $filler($model, $data);
                $model->save();
            } else {
                $model->update($data);
            }
            if (!empty($relatedData)) {
                foreach ($relatedData as $relation => $relationData) {
                    if (!method_exists($model, $relation)) {
                        throw new \InvalidArgumentException("Invalid relation method: $relation");
                    } else {
                        $model->$relation()->update($relationData);
                    }
                }
            }
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in BaseRepository@update: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $ids
     * @param array $filters
     * @param callable|null $callbackBefore
     * @param callable|null $callbackAfter
     * @param array $relations
     * @return int
     * @throws \Exception
     */
    public function delete($ids = null, array $filters = [], ?callable $callbackBefore = null, ?callable $callbackAfter = null, array $relations = []): int
    {
        DB::beginTransaction();
        try {
            $query = $this->model;
            if ($ids !== null) {
                $ids = is_array($ids) ? $ids : [$ids];
                $query = $query->whereIn('id', $ids);
            }
            $query = $this->applyFilters(query: $query, filters: $filters);

            if (is_callable($callbackBefore)) {
                $callbackBefore($query);
            }

            if (!empty($relations)) {
                $models = $query->with($relations)->get();
                foreach ($models as $model) {
                    foreach ($relations as $relation) {
                        if (!method_exists($model, $relation)) {
                            throw new \InvalidArgumentException("Invalid relation method: $relation");
                        } else {
                            $model->$relation()->delete();
                        }
                    }
                }
            }

            $deletedRows = $query->delete();

            if (is_callable($callbackAfter)) {
                $callbackAfter($deletedRows);
            }

            DB::commit();
            return $deletedRows;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in Repository@delete: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array $filters
     * @param array $relations
     * @return array
     */
    public function count($filters = [], array $relations = []): array
    {
        try {
            $query = $this->model->query();
            $query = $this->applyFilters(query: $query, filters: $filters);
            $counts = [];
            $counts['total'] = $query->count();

            foreach ($relations as $relation) {
                if (!method_exists($this->model, $relation)) {
                    throw new \InvalidArgumentException("Invalid relation method: $relation");
                }
                $counts[$relation] = $query->withCount($relation)->first()->{$relation . '_count'};
            }

            return $counts;
        } catch (\Exception $e) {
            Log::error('Error in Repository@count: ' . $e->getMessage());
            throw new \RuntimeException('Error occurred while counting the records.', 0, $e);
        }
    }

    /**
     * Check if records exist based on provided filters and relations.
     *
     * @param array $filters
     * @param array $relations
     * @return bool
     * @throws \RuntimeException
     */
    public function exists($filters = [], array $relations = []): bool
    {
        DB::beginTransaction();
        try {
            $query = $this->model->query();
            $query = $this->applyFilters(query: $query, filters: $filters);

            if (!empty($relations)) {
                $query->withExists($relations);
            }

            $result = $query->first();
            if (!$result) {
                DB::rollBack();
                return false;
            }

            foreach ($relations as $relation) {
                if (!$result->{$relation . '_exists'}) {
                    DB::rollBack();
                    return false;
                }
            }

            DB::commit();
            return true;
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Database Query Error in Repository@exists: ' . $e->getMessage());
            throw new \RuntimeException('Error occurred while checking the existence of the records: ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in Repository@exists: ' . $e->getMessage());
            throw new \RuntimeException('Error occurred while checking the existence of the records: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @param array $filters
     * @param array $columns
     * @param array $nestedRelations
     * @param array $joins
     * @param bool $useCache
     * @return Model|null
     */
    public function first($filters = [], array $columns = ['*'], array $relations = [], array $joins = [], bool $useCache = false): ?Model
    {
        try {
            $query = $this->buildQuery(columns: $columns, trashed: 'no', relations: $relations, joins: $joins, withTrashed: false, orderBy: 'id', sortBy: 'asc');

            $query = $this->applyFilters(query: $query, filters: $filters);

            $executeFirst = fn() => $query->first();
            if ($useCache) {
                $cacheKey = md5(serialize(func_get_args()));
                return Cache::remember($cacheKey, now()->addMinutes(10), $executeFirst);
            }
            return $executeFirst();
        } catch (\Exception $e) {
            Log::error('Error in ' . __METHOD__ . ': ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @param UploadedFile $file
     * @param string|null $folder
     * @param string $disk
     * @param string|null $filename
     * @param array $allowedMimes
     * @param int $maxSize
     * @param bool $optimize
     * @param int $quality
     * @param int $maxWidth
     * @param string|null $encodeFormat
     * @param bool $createThumbnail
     * @param int $thumbnailWidth
     * @param int $thumbnailHeight
     * @param int $thumbnailQuality
     * @param string|null $thumbnailFormat
     * @param bool $colorFill
     * @param array $colorCoordinates
     * @param string|null $color
     * @param bool $insertWatermark
     * @param UploadedFile|null $watermarkFile
     * @param string $watermarkPosition
     * @param int|null $watermarkX
     * @param int|null $watermarkY
     * @param int|null $watermarkWidth
     * @param int|null $watermarkHeight
     * @param int $watermarkOpacity
     * @return array|string[]
     */
    public function uploadFile(UploadedFile $file, string $folder = null, string $disk = 'public', string $filename = null, array $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'], int $maxSize = 2048, bool $optimize = false, int $quality = 90, int $maxWidth = 800, string $encodeFormat = null, bool $createThumbnail = false, int $thumbnailWidth = 150, int $thumbnailHeight = 150, int $thumbnailQuality = 90, string $thumbnailFormat = null, bool $colorFill = false, array $colorCoordinates = [0, 0], string $color = null, bool $insertWatermark = false, UploadedFile $watermarkFile = null, string $watermarkPosition = 'center', int $watermarkX = null, int $watermarkY = null, int $watermarkWidth = null, int $watermarkHeight = null, int $watermarkOpacity = 100): array
    {
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return ['error' => 'Invalid file type. Allowed types are: ' . implode(', ', $allowedMimes)];
        }
        if ($file->getSize() > $maxSize * 1024 * 1024) {
            return ['error' => "File size should not exceed ${maxSize}MB"];
        }
        $name = $filename == null ? uniqid('FILE_') . dechex(time()) : $this->resolveFileNameConflict($folder, disk: $disk, name: $filename, extension: $file->getClientOriginalExtension());
        try {
            $path = $file->storeAs(path: $folder, name: $name . "." . $file->getClientOriginalExtension(), options: $disk);

            if ($optimize) {
                $this->optimizeImage(disk: $disk, path: $path, quality: $quality, maxWidth: $maxWidth, encodeFormat: $encodeFormat, interlace: true);
            }

            if ($colorFill) {
                $this->colorFillImage(disk: $disk, path: $path, coordinates: $colorCoordinates, color: $color);
            }

            if ($insertWatermark) {
                $this->insertWatermark(disk: $disk, path: $path, watermarkPath: $watermarkFile, watermarkPosition: $watermarkPosition, watermarkX: $watermarkX, watermarkY: $watermarkY, watermarkWidth: $watermarkWidth, watermarkHeight: $watermarkHeight, watermarkoOpacity: $watermarkOpacity);
            }

            $thumbnailResult = $createThumbnail ? $this->createThumbnail($disk, path: $path, width: $thumbnailWidth, height: $thumbnailHeight, quality: $thumbnailQuality, format: $thumbnailFormat) : null;
        } catch (\Exception $e) {
            return ['error' => 'File could not be uploaded: ' . $e->getMessage()];
        }

        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'thumbnailPath' => $thumbnailResult['path'] ?? null,
            'thumbnailUrl' => isset($thumbnailResult['path']) ? Storage::disk($disk)->url($thumbnailResult['path']) : null
        ];
    }

    /**
     * @param string $disk
     * @param $paths
     * @return array[]
     */
    public function deleteFile(string $disk, $paths): array
    {
        $deletedFiles = [];
        $failedFiles = [];
        $paths = is_array($paths) ? $paths : [$paths];

        foreach ($paths as $path) {
            try {
                if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                    $deletedFiles[] = $path;
                } else {
                    $failedFiles[] = [
                        'path' => $path,
                        'error' => 'File does not exist.'
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('File deletion error: ' . $e->getMessage());
                $failedFiles[] = [
                    'path' => $path,
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'deleted' => $deletedFiles,
            'failed' => $failedFiles
        ];
    }









    /**
     * @param string $disk
     * @param string $path
     * @param string $watermarkPath
     * @param string $watermarkPosition
     * @param int|null $watermarkX
     * @param int|null $watermarkY
     * @param int|null $watermarkWidth
     * @param int|null $watermarkHeight
     * @param int $watermarkoOpacity
     * @return void
     */
    protected function insertWatermark(string $disk, string $path, string $watermarkPath, string $watermarkPosition, int $watermarkX = null, int $watermarkY = null, int $watermarkWidth = null, int $watermarkHeight = null, int $watermarkoOpacity = 100): void
    {
        try {
            $image = Image::make(Storage::disk($disk)->get($path));
            $watermark = Image::make($watermarkPath);
            if ($watermarkWidth !== null && $watermarkHeight !== null) {
                $watermark->resize($watermarkWidth, $watermarkHeight);
            }
            $watermark->opacity($watermarkoOpacity);
            if ($watermarkX !== null && $watermarkY !== null) {
                $image->insert($watermark, $watermarkPosition, $watermarkX, $watermarkY);
            } else {
                $image->insert($watermark, $watermarkPosition);
            }
            Storage::disk($disk)->put($path, (string)$image->encode());
        } catch (\Exception $e) {

        }
    }

    /**
     * @param string $disk
     * @param string $path
     * @param array $coordinates
     * @param string|null $color
     * @return void
     */
    protected function colorFillImage(string $disk, string $path, array $coordinates, string $color = null): void
    {
        try {
            $image = Image::make(Storage::disk($disk)->get($path));
            if ($color) {
                $image->fill($color);
            } else {
                $pickedColor = $image->pickColor($coordinates[0], $coordinates[1]);
                $image->fill($pickedColor);
            }
            Storage::disk($disk)->put($path, (string)$image->encode());
        } catch (\Exception $e) {

        }
    }

    /**
     * @param $folder
     * @param $disk
     * @param $name
     * @param $extension
     * @return mixed
     */
    protected function resolveFileNameConflict($folder, $disk, $name, $extension): mixed
    {
        while (Storage::disk($disk)->exists($folder . '/' . $name . '.' . $extension)) {
            $name = uniqid('FILE_') . dechex(time());
        }
        return $name;
    }

    /**
     * @param string $disk
     * @param string $path
     * @param int $quality
     * @param int $maxWidth
     * @param string|null $encodeFormat
     * @param bool $interlace
     * @return array
     */
    protected function optimizeImage(string $disk, string $path, int $quality = 90, int $maxWidth = 800, string $encodeFormat = null, bool $interlace = true): array
    {
        try {
            $image = Image::make(Storage::disk($disk)->get($path));
            if ($image->width() > $maxWidth) {
                $image->resize($maxWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            if ($interlace) {
                $image->interlace();
            }
            $encodedImage = $encodeFormat ? $image->encode($encodeFormat, $quality) : $image->encode($image->mime(), $quality);
            Storage::disk($disk)->put($path, (string)$encodedImage);
            return ['success' => true, 'message' => 'Image optimized successfully.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Image could not be optimized: ' . $e->getMessage()];
        }
    }

    /**
     * @param $disk
     * @param $path
     * @param int $width
     * @param int $height
     * @param int $quality
     * @param $format
     * @return string[]
     */
    protected function createThumbnail($disk, $path, int $width = 150, int $height = 150, int $quality = 90, $format = null): array
    {
        try {
            $image = Image::make(Storage::disk($disk)->get($path));

            $image->fit($width, $height);

            $image->encode($format ?? $image->mime(), $quality);

            $thumbnailPath = pathinfo($path, PATHINFO_DIRNAME) . '/' . pathinfo($path, PATHINFO_FILENAME) . '_thumb.' . ($format ?? pathinfo($path, PATHINFO_EXTENSION));
            Storage::disk($disk)->put($thumbnailPath, (string)$image->encode());

        } catch (\Exception $e) {
            return ['error' => 'Thumbnail could not be created.'];
        }

        return ['success' => 'Thumbnail created successfully.', 'path' => $thumbnailPath];
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
    protected function applyFilters(Builder $query, $filters): Builder
    {
        return $query->when(is_callable($filters), static function (Builder $query) use ($filters) {
            return $filters($query);
        }, function (Builder $query) use ($filters) {
            foreach ($filters as $field => $value) {
                if (str_contains($field, '.')) {
                    [$tableName, $columnName] = explode('.', $field);
                    if (method_exists($query->getModel(), $tableName)) {
                        $query->whereHas($tableName, function (Builder $q) use ($columnName, $value) {
                            $this->applyCondition(query: $q, field: $columnName, value: $value);
                        });
                    } else {
                        $query->where($field, $value['operator'], $value['value']);
                    }
                } else {
                    $this->applyCondition(query: $query, field: $field, value: $value);
                }
            }
            return $query;
        });
    }

    /**
     * @param $query
     * @param $field
     * @param $value
     * @return void
     */
    protected function applyCondition($query, $field, $value): void
    {
        if (is_array($value)) {
            $operator = $value['operator'] ?? null;
            switch ($operator) {
                case 'between':
                case 'not between':
                    $query->whereBetween($field, $value['value'], $operator === 'not between');
                    break;
                case 'in':
                case 'not in':
                    $query->whereIn($field, $value['value'], 'and', $operator === 'not in');
                    break;
                case 'null':
                case 'not null':
                    $query->whereNull($field, 'and', $operator === 'not null');
                    break;
                case 'column':
                    $query->whereColumn($field, '=', $value['value']);
                    break;
                case 'or':
                    $query->orWhere($field, '=', $value['value']);
                    break;
                case 'exists':
                    $query->whereExists($value['value']);
                    break;
                case 'raw':
                    $query->whereRaw($value['value']);
                    break;
                case 'date':
                    $query->whereDate($field, '=', $value['value']);
                    break;
                case 'month':
                    $query->whereMonth($field, '=', $value['value']);
                    break;
                case 'day':
                    $query->whereDay($field, '=', $value['value']);
                    break;
                case 'year':
                    $query->whereYear($field, '=', $value['value']);
                    break;
                case 'time':
                    $query->whereTime($field, '=', $value['value']);
                    break;
                case 'having':
                    $query->having($field, '=', $value['value']);
                    break;
                case 'nested':
                    $query->whereNested($value['value']);
                    break;
                default:
                    $query->where($field, $operator ?? '=', $value['value']);
                    break;
            }
        } else {
            $query->where($field, $value);
        }
    }

    /**
     * @param array $columns
     * @param string $trashed
     * @param array $relations
     * @param array $joins
     * @param bool $withTrashed
     * @param string $orderBy
     * @param string $sortBy
     * @return Builder
     * @throws \Exception
     */
    protected function buildQuery(array $columns, string $trashed, array $relations, array $joins, bool $withTrashed, string $orderBy, string $sortBy): \Illuminate\Database\Eloquent\Builder
    {
        try {
            foreach ($relations as $relation) {
                if (!method_exists($this->model, $relation)) {
                    throw new \InvalidArgumentException("Invalid relation: $relation");
                }
            }
            $query = $this->model->select($columns)->with($relations);
            if (!empty($joins)) {
                $query = $this->applyJoin(query: $query, joins: $joins);
            }
            if ($withTrashed) {
                if (!in_array($trashed, ['no', 'yes', 'only'])) {
                    throw new \InvalidArgumentException('Invalid trashed parameter value');
                } else {
                    $query = $this->applyTrashed(query: $query, trashed: $trashed);
                }
            }
            return $query->orderBy($orderBy, $sortBy);
        } catch (\Exception $e) {
            \Log::error('Error building query: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Apply the given join to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $join
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyJoin(Builder $query, array $joins): Builder
    {
        foreach ($joins as $join) {
            if (!isset($join['table'], $join['first'], $join['operator'], $join['second'])) {
                throw new \InvalidArgumentException('Missing required keys in join array');
            }
            if (!is_string($join['table']) || !is_string($join['first']) || !is_string($join['operator']) || !is_string($join['second'])) {
                throw new \InvalidArgumentException('Invalid data types in join array');
            }
            $type = strtolower($join['type'] ?? 'inner');
            $table = $join['table'];
            $first = $join['first'];
            $operator = $join['operator'];
            $second = $join['second'];

            $query = match ($type) {
                'inner' => $query->join(table: $table, first: $first, operator: $operator, second: $second),
                'left' => $query->leftJoin(table: $table, first: $first, operator: $operator, second: $second),
                'right' => $query->rightJoin(table: $table, first: $first, operator: $operator, second: $second),
                'cross' => $query->crossJoin(table: $table),
                default => throw new \InvalidArgumentException("Invalid join type: $type"),
            };
        }
        return $query;
    }

    /**
     * @param $query
     * @param int|null $perPage
     * @param int|null $limit
     * @return mixed
     */
    protected function getResults($query, ?int $perPage = null, ?int $limit = null): mixed
    {
        if (($perPage !== null && !is_int($perPage)) || ($limit !== null && !is_int($limit))) {
            throw new \InvalidArgumentException('The perPage and limit parameters must be of type int.');
        }

        if ($perPage !== null && $perPage <= 0) {
            throw new \InvalidArgumentException('The perPage parameter must be a positive integer.');
        }

        if ($limit !== null && $limit <= 0) {
            throw new \InvalidArgumentException('The limit parameter must be a positive integer.');
        }
        $query->when($limit, fn($query, $limit) => $query->take($limit));
        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    /**
     * @param array $args
     * @param string $methodName
     * @return string
     */
    protected function getCacheKey(array $args, string $methodName): string
    {
        return md5($methodName . ':' . serialize($args));
    }

    /**
     * @param $query
     * @param $trashed
     * @return mixed
     */
    protected function applyTrashed($query, $trashed): mixed
    {
        return match ($trashed) {
            'yes' => $query->withTrashed(),
            'only' => $query->onlyTrashed(),
            default => throw new \InvalidArgumentException("Invalid trashed parameter value: $trashed"),
        };
    }

    /**
     * @param $query
     * @param $conditions
     * @param $keyword
     * @return mixed
     */
    protected function applyConditions($query, $conditions): mixed
    {
        if (empty($conditions) || !is_array($conditions)) {
            throw new \InvalidArgumentException('Conditions parameter must be a non-empty array.');
        }
        $query->where(function ($query) use ($conditions) {
            foreach ($conditions as $condition) {
                if (!isset($condition['column'], $condition['value'])) {
                    throw new \InvalidArgumentException('Each condition must have a column and value key.');
                }
                $column = $condition['column'];
                $value = $condition['value'];
                $query->orWhere($column, 'LIKE', '%' . $value . '%');
            }
        });
        return $query;
    }
}
