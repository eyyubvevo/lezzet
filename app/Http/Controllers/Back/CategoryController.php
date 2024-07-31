<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Tag;
use App\Repositories\EloquentAttributeRepository;
use App\Repositories\EloquentCategoryRepository;
use App\Repositories\EloquentTagRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    protected $languages;
    protected EloquentCategoryRepository $categoryRepository;
    protected EloquentAttributeRepository $attributeRepository;

    public function __construct(
        EloquentCategoryRepository  $categoryRepository,
        EloquentAttributeRepository $attributeRepository,
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->attributeRepository = $attributeRepository;
        $this->languages = config('translatable.locales');
    }

    public function indexTree()
    {
        $categories = Category::all();
        return view('admin.categories.three_categories', compact('categories'));
    }

    public function index()
    {
        return view('admin.categories.list');
    }

    public function list(): JsonResponse
    {
        try {
            $results = Category::all();
            return DataTables::of($results)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img src="' . asset('storage/' . $row->image) . '" alt="Image" width="50" height="50">';
                })
                ->addColumn('name', function ($row) {
                    return Str::limit($row->getTranslation('name', 'az'), 20, '...');
                })
                ->addColumn('status', function ($row) {
                    return $row->status ? '<span class="badge bg-label-primary me-1">Aktiv</span>' : '<span class="badge bg-label-warning me-1">Gözləmədə</span>';
                })
                ->addColumn('order', function ($row) {
                    return $row->order;
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.categories.edit', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bx-edit-alt me-2"></i></a>
                            <form action="' . route('admin.categories.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-light btn-sm"><i style="color: red" class="bx bx-trash me-2"></i></button>
                            </form>';
                })
                ->rawColumns(['id', 'image', 'status', 'actions','order'])
                ->toJson();

        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $languages = $this->languages;
        $categories = Category::all();
        $attributes = $this->attributeRepository->all();
        return view('admin.categories.edit-add', compact('attributes', 'languages', 'categories'));
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $languages = $this->languages;
            $category = Category::findOrFail($id);
            $categories = Category::all();
            $attributes = $this->attributeRepository->all();
            if (!$category) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.categories.edit-add', compact('category', 'attributes', 'categories', 'languages'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function store(Request $request)
    {
        $languages = $this->languages;

        $data = [
            'name' => [],
            'slug' => [],
            'meta_title' => [],
            'meta_description' => [],
            'meta_keywords' => [],
            'order' => $request->order,
            'status' => $request->status,
            'home_status' => $request->home_status,
//            'slug' => strtolower(str_replace(' ', '_', $request->name_az)),
            'parent_id' => $request->parent_id == null ? null : $request->parent_id,
        ];

        foreach ($languages as $language) {
            $data['name'][$language] = $request->get("name_$language");
            $data['slug'][$language] = $request->get("slug_$language");
            $data['meta_title'][$language] = $request->get("meta_title_$language");
            $data['meta_description'][$language] = $request->get("meta_description_$language");
            $data['meta_keywords'][$language] = $request->get("meta_keywords_$language");
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'categories';
            $disk = 'public';
            $filename = '';
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $maxSize = 2048;
            $optimize = false;
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

            $uploadFile = $this->categoryRepository->uploadFile(
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
            $data['image'] = $uploadFile['path'];
        }

        $category = $this->categoryRepository->create(data: $data);

        if ($category) {
            $category->attributes()->attach($request->input('attribute_id'));


            flash()->addFlash('success', 'Yüklənmə', 'Kateqoriya uğurla yükləndi', ['timeout' => 3000, 'position' => 'top-center']);
            return redirect()->route('admin.categories');
        } else {
            flash()->addFlash('error', 'Yüklənmə', 'Kateqoriya yüklənərkən xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function update(Request $request, $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
       
        $languages = $this->languages;
        $data = [
            'name' => [],
            'slug' => [],
            'meta_title' => [],
            'meta_description' => [],
            'meta_keywords' => [],
            'order' => $request->order,
            'status' => $request->status,
            'home_status' => $request->home_status,
//            'slug' => strtolower(str_replace(' ', '_', $request->name_az)),
            'parent_id' => $request->parent_id == null ? null : $request->parent_id,
        ];

        foreach ($languages as $language) {
            $data['name'][$language] = $request->get("name_$language");
            $data['slug'][$language] = $request->get("slug_$language");
            $data['meta_title'][$language] = $request->get("meta_title_$language");
            $data['meta_description'][$language] = $request->get("meta_description_$language");
            $data['meta_keywords'][$language] = $request->get("meta_keywords_$language");
        }
        try {
            if ($request->hasFile('image')) {
                $paths = $this->categoryRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
                $existingImagePath = storage_path('app/public/' . $paths->image);
                if (file_exists($existingImagePath)) {
                    $deleteFile = $this->categoryRepository->deleteFile('public', $paths->image);
                    if (count($deleteFile['failed']) > 0) {
                        flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                    }
                }
                $file = $request->file('image');
                $folder = 'categories';
                $disk = 'public';
                $filename = '';
                $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $maxSize = 2048;
                $optimize = false;
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

                $uploadFile = $this->categoryRepository->uploadFile(
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
                $data['image'] = $uploadFile['path'];
            }
            $updated = $this->categoryRepository->update(id: $id, data: $data);
            if ($updated) {
                if ($request->input('attribute_id')) {
                    $updated->attributes()->sync($request->input('attribute_id'));
                } else {
                    $updated->attributes()->detach();
                }
                flash()->addFlash('success', 'Yenilənmə', 'Kateqoriya uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.categories');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Kateqoriya yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
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
            $paths = $this->categoryRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
            $existingImagePath = storage_path('app/public/' . $paths->image);
            if (file_exists($existingImagePath)) {
                $deleteFile = $this->categoryRepository->deleteFile('public', $paths->image);
                if (count($deleteFile['failed']) > 0) {
                    flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                }
            }
            $deletedRows = $this->categoryRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            if ($deletedRows) {
                flash()->addFlash('success', 'Silinmə', 'Kateqoriya uğurla silindi', ['timeout' => 1500, 'position' => 'top-center']);
            } else {
                flash()->addFlash('error', 'Silinmə', 'Kateqoriya silinərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
            return back();
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

}
