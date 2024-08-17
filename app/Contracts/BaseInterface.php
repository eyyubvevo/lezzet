<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseInterface
{

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
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all(
        array  $filters = [],
        array  $columns = ['*'],
        string $orderBy = 'id',
        string $sortBy = 'desc',
        array  $relations = [],
        ?int   $perPage = null,
        ?int   $limit = null,
        bool   $withTrashed = false,
        string $trashed = 'no',
        array  $joins = [],
        bool   $useCache = true
    ): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator;


    /**
     * @param array $data
     * @param array $relatedData
     * @return mixed
     */
    public function create(
        array $data,
        array $relatedData = []
    ): mixed;

    /**
     * @param int $id
     * @param array $filters
     * @param array $data
     * @param callable|null $callback
     * @param callable|null $filler
     * @param array $relatedData
     * @return mixed
     */
    public function update(
        int       $id,
        array     $filters = [],
        array     $data = [],
        ?callable $callback = null,
        ?callable $filler = null,
        array     $relatedData = []
    ): mixed;

    /**
     * @param $ids
     * @param array $filters
     * @param callable|null $callbackBefore
     * @param callable|null $callbackAfter
     * @param array $relations
     * @return mixed
     */
    public function delete(
        $ids = null,
        array $filters = [],
        ?callable $callbackBefore = null,
        ?callable $callbackAfter = null,
        array $relations = []
    ): mixed;


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
     * @return mixed
     */
    public function search(
        array  $conditions,
        string $keyword,
        ?int   $perPage = null,
        ?int   $limit = null,
        string $orderBy = 'id',
        string $sortBy = 'desc',
        array  $relations = [],
        array  $columns = ['*'],
        bool   $withTrashed = false,
        array  $joins = [],
        string $trashed = 'no',
        bool   $useCache = false
    ): mixed;

    /**
     * @param array $filters
     * @param array $relations
     * @return mixed
     */
    public function count(
        $filters = [],
        array $relations = []
    ): mixed;


    /**
     * @param $filters
     * @param array $relations
     * @return mixed
     */
    public function exists(
        $filters = [],
        array $relations = []
    ): mixed;

    /**
     * @param array $filters
     * @param array $columns
     * @param array $relations
     * @param array $joins
     * @param bool $useCache
     * @return mixed
     */
    public function first(
        $filters = [],
        array $columns = ['*'],
        array $relations = [],
        array $joins = [],
        bool $useCache = false
    ): mixed;


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
     * @param int $watermarkoOpacity
     * @return mixed
     */
    public function uploadFile(
        UploadedFile $file,
        string       $folder = null,
        string       $disk = 'public',
        string       $filename = null,
        array        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'],
        int          $maxSize = 2048,
        bool         $optimize = false,
        int          $quality = 90,
        int          $maxWidth = 800,
        string       $encodeFormat = null,
        bool         $createThumbnail = false,
        int          $thumbnailWidth = 150,
        int          $thumbnailHeight = 150,
        int          $thumbnailQuality = 90,
        string       $thumbnailFormat = null,
        bool         $colorFill = false,
        array        $colorCoordinates = [10, 10],
        string       $color = null,
        bool         $insertWatermark = false,
        UploadedFile $watermarkFile = null,
        string       $watermarkPosition = 'center',
        int          $watermarkX = null,
        int          $watermarkY = null,
        int          $watermarkWidth = null,
        int          $watermarkHeight = null,
        int          $watermarkoOpacity = 100
    ): mixed;

    /**
     * @param string $disk
     * @param $paths
     * @return mixed
     */
    public function deleteFile(
        string $disk,
               $paths
    ): mixed;
}
