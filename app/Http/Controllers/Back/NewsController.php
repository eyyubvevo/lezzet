<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Repositories\EloquentNewsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use MetaTag;

class NewsController extends Controller
{
    protected EloquentNewsRepository $newsRepository;
    protected $languages;

    public function __construct(EloquentNewsRepository $newsRepository)
    {
        $this->languages = config('translatable.locales');
        $this->newsRepository = $newsRepository;
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.news.list');
    }

    public function list(): JsonResponse
    {
        try {

            $columns = ['id', 'title', 'short_content', 'content', 'image', 'status', 'created_at'];
            $orderBy = 'created_at';
            $sortBy = 'DESC';
            $joins = [];
            $relations = [];
            $perPage = null;
            $filters = [];
            $results = $this->newsRepository->all(
                filters: $filters,
                columns: $columns,
                orderBy: $orderBy,
                sortBy: $sortBy,
                relations: $relations,
                perPage: $perPage,
                limit: null,
                withTrashed: false,
                trashed: 'no',
                joins: $joins,
                useCache: false,
            );
            return DataTables::of($results)
                ->addIndexColumn()
//                ->addColumn('checkbox', '<input type="checkbox" name="pdr_checkbox[]" class="pdr_checkbox" value="" />')

                ->addColumn('image', function ($row) {
                    return '<img src="' . asset('storage/' . $row->image) . '" alt="Image" width="50" height="50">';
                })
                ->addColumn('title', function ($row) {
                    return Str::limit($row->title, 20, '...');
                })
                ->addColumn('status', function ($row) {
                    return $row->status ? '<span class="badge bg-label-primary me-1">Aktiv</span>' : '<span class="badge bg-label-warning me-1">Gözləmədə</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.news.edit', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bx-edit-alt me-2"></i></a>
                            <form action="' . route('admin.news.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-light btn-sm"><i style="color: red" class="bx bx-trash me-2"></i></button>
                            </form>';
                })
                ->rawColumns(['id', 'image', 'status', 'actions'])
                ->toJson();

        } catch (\Exception $e) {
            // return response()->json(['error' => 'Veri çekerken bir hata oluştu. Hata: ' . $e->getMessage()], 500);
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);

        }
    }


    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $languages = $this->languages;
        return view('admin.news.edit-add', compact('languages'));
    }


    public function store(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $languages = $this->languages;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $folder = 'news';
                $disk = 'public';
                $filename = '';
                $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
                $maxSize = 2048;
                $optimize = true;
                $quality = 90;
                $maxWidth = 1920;
                $encodeFormat = 'png';
                $createThumbnail = false;
                $thumbnailWidth = 150;
                $thumbnailHeight = 150;
                $thumbnailQuality = 90;
                $thumbnailFormat = 'jpg';
                $colorFill = false;
                $color = 'rgba(0, 0, 0, 0.5)';

                $uploadFile = $this->newsRepository->uploadFile(
                    file: $file,
                    folder: $folder,
                    disk: $disk,
                    filename: $filename,
                    allowedMimes: $allowedMimes,
                    maxSize: $maxSize,
                    optimize: $optimize,
                    quality: $quality,
                    maxWidth: $maxWidth,
                    encodeFormat: $encodeFormat,
                    createThumbnail: $createThumbnail,
                    thumbnailWidth: $thumbnailWidth,
                    thumbnailHeight: $thumbnailHeight,
                    thumbnailQuality: $thumbnailQuality,
                    thumbnailFormat: $thumbnailFormat,
                    colorFill: $colorFill,
                    color: $color,
                );
                if (isset($uploadFile['error'])) {
                    flash()->addFlash('warning', 'Xəbərdarlıq', $uploadFile['error'], ['timeout' => 3000, 'position' => 'top-center']);
                    return back();
                }
                $data = [
                    'title' => [],
                    'image' => $uploadFile['path'],
                    'status' => $request->status,
                    'content' => [],
                    'short_content' => [],
                    'meta_title' => [],
                    'meta_description' => [],
                    'meta_keywords' => [],
                ];

                foreach ($languages as $language) {
                    $data['title'][$language] = $request->get("title_$language");
                    $data['content'][$language] = $request->get("content_$language");
                    $data['short_content'][$language] = $request->get("short_content_$language");
                    $data['meta_title'][$language] = $request->get("meta_title_$language");
                    $data['meta_description'][$language] = $request->get("meta_description_$language");
                    $data['meta_keywords'][$language] = $request->get("meta_keywords_$language");
                }
                $relatedData = [];
                $news = $this->newsRepository->create(data: $data, relatedData: $relatedData);
            }

            if ($news) {
                $news->metaTag()->create($data);
                flash()->addFlash('success', 'Yüklənmə', 'Xəbər uğurla yükləndi', ['timeout' => 3000, 'position' => 'top-center']);
                return redirect()->route('admin.news');
            } else {
                flash()->addFlash('error', 'Yüklənmə', 'Xəbər yüklənərkən xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }


    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $languages = $this->languages;
            $filters = [
                'id' => ['operator' => '=', 'value' => $id],
            ];
            $joins = [];
            $columns = ['id', 'meta_title', 'meta_description', 'meta_keywords', 'title', 'short_content', 'content', 'image', 'status', 'created_at'];
            $relations = [];
            $news = $this->newsRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );

            if (!$news) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.news.edit-add', compact('news', 'languages'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }


    public function update(Request $request, $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $languages = $this->languages;
            $data = [
                'title' => [],
                'status' => $request->status,
                'content' => [],
                'short_content' => [],
                'meta_title' => [],
                'meta_description' => [],
                'meta_keywords' => [],
            ];

            foreach ($languages as $language) {
                $data['title'][$language] = $request->get("title_$language");
                $data['content'][$language] = $request->get("content_$language");
                $data['short_content'][$language] = $request->get("short_content_$language");
                $data['meta_title'][$language] = $request->get("meta_title_$language");
                $data['meta_description'][$language] = $request->get("meta_description_$language");
                $data['meta_keywords'][$language] = $request->get("meta_keywords_$language");
            }
            if ($request->hasFile('image')) {
                $paths = $this->newsRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
                $existingImagePath = storage_path('app/public/' . $paths->image);
                if (file_exists($existingImagePath)) {
                    $deleteFile = $this->newsRepository->deleteFile('public', $paths->image);
                    if (count($deleteFile['failed']) > 0) {
                        flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                    }
                }

                $file = $request->file('image');

                $folder = 'news';
                $disk = 'public';

                $uploadFile = $this->newsRepository->uploadFile(
                    file: $file,
                    folder: $folder,
                    disk: $disk,
                    filename: '',
                    allowedMimes: ['image/jpeg', 'image/png', 'image/jpg'],
                    maxSize: 2048,
                    optimize: true,
                    quality: 90,
                    maxWidth: 1920,
                    encodeFormat: 'png',
                    createThumbnail: false,
                    thumbnailWidth: 150,
                    thumbnailHeight: 150,
                    thumbnailQuality: 90,
                    thumbnailFormat: 'jpg',
                    colorFill: false,
                    color: 'rgba(0, 0, 0, 0.5)',
                );

                if (isset($uploadFile['error'])) {
                    flash()->addFlash('warning', 'Xəbərdarlıq', $uploadFile['error'], ['timeout' => 3000, 'position' => 'top-center']);
                    return back();
                }

                $data['image'] = $uploadFile['path'];
            }

            $updated = $this->newsRepository->update(id: $id, data: $data);

            if ($updated) {
                flash()->addFlash('success', 'Yenilənmə', 'Xəbər uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.news');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Xəbər yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function destroy($id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $ids = [$id];
        $filters = [];

        $callbackBefore = function ($query) {
            Log::info('Deleting records with IDs: ' . $query->pluck('id')->implode(', '));
        };

        $callbackAfter = function ($deletedRows) {
            Log::info("$deletedRows rows have been deleted successfully.");
        };

        $relations = [];

        try {
            $paths = $this->newsRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
            $existingImagePath = storage_path('app/public/' . $paths->image);
            if (file_exists($existingImagePath)) {
                $deleteFile = $this->newsRepository->deleteFile('public', $paths->image);
                if (count($deleteFile['failed']) > 0) {
                    flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                }
            }

            $deletedRows = $this->newsRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            if ($deletedRows) {
                flash()->addFlash('success', 'Silinmə', 'Xəbər uğurla silindi', ['timeout' => 1500, 'position' => 'top-center']);
            } else {
                flash()->addFlash('error', 'Silinmə', 'Xəbər silinərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
            return back();
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function upload(Request $request)
    {
        try {
            $folder = 'news';
            $disk = 'public';
            $filename = '';
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxSize = 2048;
            $optimize = true;
            $quality = 90;
            $maxWidth = 1920;
            $encodeFormat = 'png';
            $createThumbnail = false;
            $thumbnailWidth = 150;
            $thumbnailHeight = 150;
            $thumbnailQuality = 90;
            $thumbnailFormat = 'jpg';
            $colorFill = false;
            $color = 'rgba(0, 0, 0, 0.5)';

            $uploadFile = $this->newsRepository->uploadFile(
                file: $request->file('upload'),
                folder: $folder,
                disk: $disk,
                filename: $filename,
                allowedMimes: $allowedMimes,
                maxSize: $maxSize,
                optimize: $optimize,
                quality: $quality,
                maxWidth: $maxWidth,
                encodeFormat: $encodeFormat,
                createThumbnail: $createThumbnail,
                thumbnailWidth: $thumbnailWidth,
                thumbnailHeight: $thumbnailHeight,
                thumbnailQuality: $thumbnailQuality,
                thumbnailFormat: $thumbnailFormat,
                colorFill: $colorFill,
                color: $color,
            );
            if (isset($uploadFile['error'])) {
                flash()->addFlash('warning', 'Xəbərdarlıq', $uploadFile['error'], ['timeout' => 3000, 'position' => 'top-center']);
                return null;
            }
            return response()->json([
                'fileName' => $uploadFile['path'],
                'uploaded' => 1,
                'url' => $uploadFile['url']
            ]);
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

}
