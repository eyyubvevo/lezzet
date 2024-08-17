<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Repositories\EloquentAttributeRepository;
use App\Repositories\EloquentCategoryRepository;
use App\Repositories\EloquentProductImageRepository;
use App\Repositories\EloquentProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected EloquentProductRepository $eloquentProductRepository;
    protected EloquentCategoryRepository $eloquentCategoryRepository;
    protected EloquentAttributeRepository $attributeRepository;
    protected EloquentProductImageRepository $eloquentProductImageRepository;
    protected $languages;

    public function __construct(EloquentProductRepository $eloquentProductRepository, EloquentAttributeRepository $attributeRepository, EloquentProductImageRepository $eloquentProductImageRepository, EloquentCategoryRepository $eloquentCategoryRepository)
    {
        $this->languages = config('translatable.locales');
        $this->eloquentProductRepository = $eloquentProductRepository;
        $this->attributeRepository = $attributeRepository;
        $this->eloquentProductImageRepository = $eloquentProductImageRepository;
        $this->eloquentCategoryRepository = $eloquentCategoryRepository;
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.products.list');
    }

    public function list(): JsonResponse
    {
        try {
            $results = Product::orderBy('created_at', 'DESC')
                ->get();
            return DataTables::of($results)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return Str::limit($row->getTranslation('title', app()->getLocale()), 20, '...');
                })
                ->editColumn('price', function ($row) {
                    if ($row->category->company) {
                        return '<span style="text-decoration: line-through;color: #999;">' . $row->price . ' AZN</span><br><span>' . $row->getPriceWithDiscount() . ' AZN</span>';
                    }elseif ($row->is_discountable){
                        return '<span style="text-decoration: line-through;color: #999;">' . $row->price . ' AZN</span><br><span>' . $row->price - ($row->discount * $row->price / 100) . ' AZN</span>';
                    } else {
                        return '<span>' . $row->price . ' AZN</span>';
                    }
                })
                ->addColumn('attribute', function ($row) {
                    return $row->attribute->getTranslation('name', 'az');
                })
                 ->addColumn('order', function ($row) {
                    return $row->order;
                })
                ->addColumn('category', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('image', function ($row) {
                    $image = $row->images->first();
                    return $image ? '<img src="' . asset('storage/' . $image->image) . '" alt="Product Image" width="100">' : '';
                })
                ->addColumn('status', function ($row) {
                    return $row->status ? '<span class="badge bg-label-primary me-1">Aktiv</span>' : '<span class="badge bg-label-warning me-1">Gözləmədə</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.products.edit', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bx-edit-alt me-2"></i></a>
                            <form action="' . route('admin.products.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-light btn-sm"><i style="color: red" class="bx bx-trash me-2"></i></button>
                            </form>';
                })
                ->rawColumns(['id', 'image', 'status', 'actions', 'price','order'])
                ->toJson();

        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $languages = $this->languages;
        $attributes = $this->attributeRepository->all();
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.products.edit-add', compact('tags','languages', 'attributes', 'categories'));
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        $languages = $this->languages;
        try {
            $attributes = $this->attributeRepository->all();
            $categories = Category::all();
            $tags = Tag::all();
            $filters = [
                'id' => ['operator' => '=', 'value' => $id],
            ];
            $joins = [];
            $columns = ['id', 'meta_title', 'meta_description', 'meta_keywords','order', 'slug', 'content', 'price', 'discount', 'home_status', 'is_discountable', 'attribute_id','category_id', 'discount_start_date', 'discount_end_date', 'title', 'status', 'created_at'];
            $relations = ['images', 'attribute'];
            $product = $this->eloquentProductRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );
            if (!$product) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.products.edit-add', compact('tags','product', 'languages', 'attributes', 'categories'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function upload(Request $request)
    {
        try {
            $folder = 'products';
            $disk = 'public';
            $filename = '';
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxSize = 2048;
            $optimize = false;
            $quality = 90;
            $maxWidth = 1920;
            $encodeFormat = 'png';
            $createThumbnail = true;
            $thumbnailWidth = 150;
            $thumbnailHeight = 150;
            $thumbnailQuality = 90;
            $thumbnailFormat = 'jpg';
            $colorFill = false;
            $color = 'rgba(0, 0, 0, 0.5)';

            $uploadFile = $this->eloquentProductRepository->uploadFile(
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

    public function store(Request $request)
    {
        try {
            $languages = $this->languages;
            $relatedData = ['images' => []];

            if ($request->hasFile('images')) {
                $files = $request->file('images');
                $folder = 'products';
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

                foreach ($files as $file) {
                    $uploadFile = $this->eloquentProductRepository->uploadFile(
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
                    $relatedData['images'][] = [
                        'image' => $uploadFile['path'],
                    ];
                }
            }

            $data = [
                'title' => [],
                'slug' => [],
                'content' => [],
                'meta_title' => [],
                'meta_description' => [],
                'meta_keywords' => [],
                'status' => $request->status,
                 'order' => $request->order,
                'price' => $request->price,
//                'slug' => strtolower(str_replace(' ', '_', $request->title_az)),
                'attribute_id' => $request->attribute_id,
                'category_id' => $request->category_id,
                'is_discountable' => $request->has('is_discountable') ? 1 : 0,
                'discount' => $request->has('is_discountable') ? $request->discount : null,
                'discount_start_date' => $request->has('is_discountable') ? $request->discount_start_date : null,
                'discount_end_date' => $request->has('is_discountable') ? $request->discount_end_date : null,
            ];

            foreach ($languages as $language) {
                $data['title'][$language] = $request->get("title_$language");
                $data['slug'][$language] = $request->get("slug_$language");
                $data['content'][$language] = $request->get("content_$language");
                $data['meta_title'][$language] = $request->get("meta_title_$language");
                $data['meta_description'][$language] = $request->get("meta_description_$language");
                $data['meta_keywords'][$language] = $request->get("meta_keywords_$language");
            }

            $product = $this->eloquentProductRepository->create(data: $data, relatedData: $relatedData);
            if ($product) {
                $product->tags()->attach($request->input('tag_id'));
                flash()->addFlash('success', 'Yüklənmə', 'Məhsul uğurla yükləndi', ['timeout' => 3000, 'position' => 'top-center']);
                return redirect()->route('admin.products');
            } else {
                flash()->addFlash('error', 'Yüklənmə', 'Məhsul yüklənərkən xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function imageDelete(Request $request)
    {
        if ($request->id) {
            try {
                $paths = $this->eloquentProductImageRepository->first(filters: ['id' => ['operator' => '=', 'value' => $request->id]]);
                $deleteFile = $this->eloquentProductImageRepository->deleteFile('public', $paths->image);
                if (count($deleteFile['failed']) > 0) {
                    flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                }
                $ids = [$request->id];
                $filters = [];

                $callbackBefore = function ($query) {
                    Log::info('Deleting records with IDs: ' . $query->pluck('id')->implode(', '));
                };

                $callbackAfter = function ($deletedRows) {
                    Log::info("$deletedRows rows have been deleted successfully.");
                };

                $relations = [];
                $deletedRows = $this->eloquentProductImageRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
                if ($deletedRows) {
                    return 1;
                }
            } catch (\Exception $e) {
                flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
            }
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = $this->eloquentProductRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
            $languages = $this->languages;
            $createDdata = [];
            $data = [
                'title' => [],
                'slug' => [],
                'content' => [],
                'meta_title' => [],
                'meta_description' => [],
                'meta_keywords' => [],
                'status' => $request->status,
                 'order' => $request->order,
                'price' => $request->price,
//                'slug' => strtolower(str_replace(' ', '_', $request->title_az)),
                'attribute_id' => $request->attribute_id,
                'category_id' => $request->category_id,
                'is_discountable' => $request->has('is_discountable') ? 1 : 0,
                'discount' => $request->has('is_discountable') ? $request->discount : null,
                'discount_start_date' => $request->has('is_discountable') ? $request->discount_start_date : null,
                'discount_end_date' => $request->has('is_discountable') ? $request->discount_end_date : null,
            ];

            foreach ($languages as $language) {
                $data['title'][$language] = $request->get("title_$language");
                $data['slug'][$language] = $request->get("slug_$language");
                $data['content'][$language] = $request->get("content_$language");
                $data['meta_title'][$language] = $request->get("meta_title_$language");
                $data['meta_description'][$language] = $request->get("meta_description_$language");
                $data['meta_keywords'][$language] = $request->get("meta_keywords_$language");
            }

            if ($request->hasFile('images')) {
                $files = $request->file('images');
                $folder = 'products';
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

                foreach ($files as $file) {
                    $uploadFile = $this->eloquentProductRepository->uploadFile(
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
                    $createDdata = [
                        'product_id' => $id,
                        'image' => $uploadFile['path'],
                    ];
                }
            }
            $updated = $this->eloquentProductRepository->update(id: $product->id, data: $data);
            $this->eloquentProductImageRepository->create(data: $createDdata, relatedData: []);

            if ($updated) {
                if($request->input('tag_id')){
                     $updated->tags()->sync($request->input('tag_id'));
                }else{
                    $updated->tags()->detach();
                }
                flash()->addFlash('success', 'Yenilənmə', 'Məhsul uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.products');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Məhsul yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
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
            $disk = 'public';
            $product = $this->eloquentProductRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
            foreach ($product->images as $image) {
                $existingImagePath = storage_path('app/public/' .$image->image);
                if (file_exists($existingImagePath)) {
                    $deleteFile = $this->eloquentProductImageRepository->deleteFile('public', $image->image);
                    if (count($deleteFile['failed']) > 0) {
                        flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                    }
                }
                $this->eloquentProductImageRepository->delete($image->id, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            }
            $deletedRows = $this->eloquentProductRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            if ($deletedRows) {
                flash()->addFlash('success', 'Silinmə', 'Məhsul uğurla silindi', ['timeout' => 1500, 'position' => 'top-center']);
            } else {
                flash()->addFlash('error', 'Silinmə', 'Məhsul silinərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
            return back();
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
            return back();
        }
    }

    public function getAttributes(Request $request)
    {
        $categoryId = $request->id;
        $locale = 'az';
        $category = Category::findOrFail($categoryId);
        $attributes = $category->attributes;

        $translations = [];

        foreach ($attributes as $attribute) {
            $translations['id'] = $attribute->id;
            $translations['name'] = $attribute->getTranslation('name', $locale);
        }

        return response()->json(['attributes' => $translations]);
    }
}
