<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Models\About;
use App\Repositories\EloquentAboutRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MetaTag;

class AboutController extends Controller
{
    protected EloquentAboutRepository $aboutRepository;
    protected $languages;

    public function __construct(EloquentAboutRepository $aboutRepository)
    {
        $this->aboutRepository = $aboutRepository;
        $this->languages = config('translatable.locales');
    }

    public function index()
    {
        try {
            $languages = $this->languages;
            $filters = [];
            $joins = [];
            $columns = ['id', 'title', 'content', 'image','keywords', 'created_at'];
            $relations = [];
            $about = $this->aboutRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );
            if (!$about) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.about.edit', compact('about', 'languages'));

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
                'content' => [],
            ];
            foreach ($languages as $language) {
                $data['title'][$language] = $request->get("title_$language");
                $data['content'][$language] = $request->get("content_$language");
            }
            if ($request->hasFile('image')) {
                $paths = $this->aboutRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
                $existingImagePath = storage_path('app/public/' . $paths->image);
                if (file_exists($existingImagePath)) {
                    $deleteFile = $this->aboutRepository->deleteFile('public', $paths->image);
                    if (count($deleteFile['failed']) > 0) {
                        flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                    }
                }

                $file = $request->file('image');

                $folder = 'about';
                $disk = 'public';

                $uploadFile = $this->aboutRepository->uploadFile(
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

            $updated = $this->aboutRepository->update(id: $id, data: $data);
//            $updated->metaTag()->create($data);

            if ($updated) {
                flash()->addFlash('success', 'Yenilənmə', 'Haqqımızda Səhifəsi uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.about.index');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Haqqımızda Səhifəsi yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
        return redirect()->back()->withErrors(['error' => 'Bir hata oluştu.']);
    }

    public function upload(Request $request)
    {
        try {
            $folder = 'about';
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

            $uploadFile = $this->aboutRepository->uploadFile(
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
