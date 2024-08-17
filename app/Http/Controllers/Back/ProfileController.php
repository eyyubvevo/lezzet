<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Repositories\EloquentUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected EloquentUserRepository $userRepository;

    public function __construct(EloquentUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        return view('admin.profile.edit');
    }

    public function update(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
            ];

            if ($request->hasFile('profile_image')) {
                $paths = $this->userRepository->first(filters: ['id' => ['operator' => '=', 'value' => $id]]);
                $existingImagePath = storage_path('app/public/' . $paths->profile_image);
                if (file_exists($existingImagePath)) {
                    $deleteFile = $this->userRepository->deleteFile('public', $paths->profile_image);
                    if (count($deleteFile['failed']) > 0) {
                        flash()->addFlash('warning', 'Xəbərdarlıq', 'Some files could not be deleted.', ['timeout' => 3000, 'position' => 'top-center']);
                    }
                }
                $file = $request->file('profile_image');

                $folder = 'profile';
                $disk = 'public';

                $uploadFile = $this->userRepository->uploadFile(
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

                $data['profile_image'] = $uploadFile['path'];
            }

            $updated = $this->userRepository->update(id: $id, data: $data);

            if ($updated) {
                flash()->addFlash('success', 'Yenilənmə', 'Məlumatlarınız uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('profile.index');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Məlumatlarınız yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function passwordIndex(){
        return view('admin.profile.password_edit');
    }

    public function passwordUpdate(Request $request){
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            flash()->addFlash('warning', 'Xəbərdarlıq', 'The provided password does not match your current password.', ['timeout' => 3000, 'position' => 'top-center']);
            return redirect()->back();
        }
        $data = [
            'password' => Hash::make($request->new_password),
        ];
        try {
            $updated = $this->userRepository->update(id: $user->id, data: $data);
            if ($updated) {
                Auth::logout();
                flash()->addFlash('success', 'Yenilənmə', 'Şifrəniz uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return null;
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Şifrəniz yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
