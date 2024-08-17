<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Repositories\EloquentSettingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    protected EloquentSettingRepository $settingRepository;

    public function __construct(EloquentSettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }



public function index(){
    $settings = Setting::get();
     return view('admin.settings.newList',compact('settings'));
}


    public function old_index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.settings.list');
    }


    public function list(): JsonResponse
    {
        try {
            $columns = ['id', 'key', 'value', 'type', 'order', 'created_at'];
            $orderBy = 'created_at';
            $sortBy = 'DESC';
            $joins = [];
            $relations = [];
            $perPage = null;
            $filters = [];
            $results = $this->settingRepository->all(
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
                ->addColumn('key', function ($row) {
                    return $row->key;
                })
                ->addColumn('value', function ($row) {
                    if ($row->type == 'text' or $row->type == 'ckeditor') {
                        return Str::limit($row->value, 30, '...');
                    } else {
                        return '<img src="' . asset('storage/' . $row->value) . '" alt="Image" width="50" height="50">';
                    }
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.settings.edit', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bx-edit-alt me-2"></i></a>';
                })
                ->rawColumns(['id', 'status', 'actions', 'value'])
                ->toJson();

        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $filters = [
                'id' => ['operator' => '=', 'value' => $id],
            ];
            $joins = [];
            $columns = ['id', 'key','display_name','group', 'value', 'type', 'created_at'];
            $relations = [];
            $setting = $this->settingRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );

            if (!$setting) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.settings.edit-add', compact('setting'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function store(Request $request)
    {
        $data = [
            'key' => $request->key,
            'type' => $request->type,
        ];
        $relatedData = [];
        if ($request->type == 'text') {
            $data['value'] = $request->value_text;
        } elseif ($request->type == 'ckeditor') {
            $data['value'] = $request->value_ckeditor;
        } elseif ($request->type == 'image') {
            if ($request->hasFile('value_image')) {
                $file = $request->file('value_image');

                $folder = 'settings';
                $disk = 'public';

                $uploadFile = $this->settingRepository->uploadFile(
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
                $data['value'] = $uploadFile['path'];
            }
        } else {
            $data['value'] = null;
        }
        try {
            $setting = $this->settingRepository->create(data: $data, relatedData: $relatedData);
            if ($setting) {
                flash()->addFlash('success', 'Yüklənmə', 'Parametr uğurla yükləndi', ['timeout' => 3000, 'position' => 'top-center']);
                return redirect()->route('admin.settings');
            } else {
                flash()->addFlash('error', 'Yüklənmə', 'Parametr yüklənərkən xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function update(Request $request, $id)
    {
        $data = [
        ];
        try {
            $setting = $this->settingRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);

            if ($setting->type == 'text') {
                $data['value'] = $request->value_text;
            } elseif ($setting->type == 'ckeditor') {
                $data['value'] = $request->value_ckeditor;
            } elseif ($setting->type == 'image') {
                if ($request->hasFile('value_image')) {
                    $existingImagePath = storage_path('app/public/' . $setting->value);
                    if (file_exists($existingImagePath)) {
                        $deleteFile = $this->settingRepository->deleteFile('public', $setting->value);
                        if (count($deleteFile['failed']) > 0) {
                            flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                        }
                    }

                    $file = $request->file('value_image');

                    $folder = 'settings';
                    $disk = 'public';

                    $uploadFile = $this->settingRepository->uploadFile(
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

                    $data['value'] = $uploadFile['path'];
                }
            }
            $updated = $this->settingRepository->update(id: $id, data: $data);
            if ($updated) {
                flash()->addFlash('success', 'Yenilənmə', 'Parametr uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.settings');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Parametr yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.settings.edit-add');
    }
    public function upload(Request $request)
    {
        try {
            $folder = 'settings';
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

            $uploadFile = $this->settingRepository->uploadFile(
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
