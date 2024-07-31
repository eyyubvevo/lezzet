<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Repositories\EloquentCompanyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    protected EloquentCompanyRepository $companyRepository;

    public function __construct(EloquentCompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        return view('admin.companies.list');
    }

    public function list(): JsonResponse
    {
        try {
            $results = Company::all();
            return DataTables::of($results)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img src="' . asset('storage/' . $row->image) . '" alt="Image" width="50" height="50">';
                })
                ->addColumn('category', function ($row) {
                    return $row->category->getTranslation('name', app()->getLocale());
                })
                ->addColumn('discount', function ($row) {
                    return $row->discount . " %";
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.companies.edit', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bx-edit-alt me-2"></i></a>
                            <form action="' . route('admin.companies.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-light btn-sm"><i style="color: red" class="bx bx-trash me-2"></i></button>
                            </form>';
                })
                ->rawColumns(['id', 'image', 'actions'])
                ->toJson();
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }


    public function create()
    {
        $categories = Category::hasDiscountedCategory()->get();

        return view('admin.companies.edit-add', compact('categories'));
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $categories = Category::all();
            $filters = [
                'id' => ['operator' => '=', 'value' => $id],
            ];
            $joins = [];
            $columns = ['id', 'discount', 'image', 'discount_start_date', 'discount_end_date', 'category_id', 'created_at'];
            $relations = [];
            $company = $this->companyRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );
            if (!$company) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.companies.edit-add', compact('categories', 'company'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function store(Request $request)
    {

        $data = [
            'discount_start_date' => $request->discount_start_date,
            'discount_end_date' => $request->discount_end_date,
            'discount' => $request->discount,
            'category_id' => $request->category_id
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'companies';
            $disk = 'public';
            $filename = '';
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
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

            $uploadFile = $this->companyRepository->uploadFile(
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

        $company = $this->companyRepository->create(data: $data);

        if ($company) {
            flash()->addFlash('success', 'Yüklənmə', 'Kompaniya uğurla yükləndi', ['timeout' => 3000, 'position' => 'top-center']);
            return redirect()->route('admin.companies');
        } else {
            flash()->addFlash('error', 'Yüklənmə', 'Kompaniya yüklənərkən xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function update(Request $request, $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {

        $data = [
            'discount_start_date' => $request->discount_start_date,
            'discount_end_date' => $request->discount_end_date,
            'discount' => $request->discount,
            'category_id' => $request->category_id
        ];

        try {
            if ($request->hasFile('image')) {
                $paths = $this->companyRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
                $existingImagePath = storage_path('app/public/' . $paths->image);
                if (file_exists($existingImagePath)) {
                    $deleteFile = $this->companyRepository->deleteFile('public', $paths->image);
                    if (count($deleteFile['failed']) > 0) {
                        flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                    }
                }
                $file = $request->file('image');
                $folder = 'companies';
                $disk = 'public';
                $filename = '';
                $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
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

                $uploadFile = $this->companyRepository->uploadFile(
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
            $updated = $this->companyRepository->update(id: $id, data: $data);
            if ($updated) {
                flash()->addFlash('success', 'Yenilənmə', 'Kompaniya uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.companies');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Kompaniya yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
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
            $paths = $this->companyRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
            $existingImagePath = storage_path('app/public/' . $paths->image);
            if (file_exists($existingImagePath)) {
                $deleteFile = $this->companyRepository->deleteFile('public', $paths->image);
                if (count($deleteFile['failed']) > 0) {
                    flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                }
            }

            $deletedRows = $this->companyRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            if ($deletedRows) {
                flash()->addFlash('success', 'Silinmə', 'Kompaniya uğurla silindi', ['timeout' => 1500, 'position' => 'top-center']);
            } else {
                flash()->addFlash('error', 'Silinmə', 'Kompaniya silinərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
            return back();
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
