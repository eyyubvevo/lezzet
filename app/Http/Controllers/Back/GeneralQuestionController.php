<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\GeneralQuestion;
use App\Repositories\EloquentGeneralQuestionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class GeneralQuestionController extends Controller
{
    protected EloquentGeneralQuestionRepository $general_questionsRepository;
    protected $languages;
    public function __construct(EloquentGeneralQuestionRepository $general_questionsRepository)
    {
        $this->general_questionsRepository = $general_questionsRepository;
        $this->languages = config('translatable.locales');
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.general_questions.list');
    }

    public function list(): JsonResponse
    {
        try {
            $columns = ['id', 'question','answer', 'status', 'created_at'];
            $orderBy = 'created_at';
            $sortBy = 'DESC';
            $joins = [];
            $relations = [];
            $perPage = null;
            $filters = [];
            $results = $this->general_questionsRepository->all(
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
                ->addColumn('question', function ($row) {
                    return Str::limit($row->question, 20, '...');

                })
                ->addColumn('answer', function ($row) {
                    return Str::limit($row->answer, 20, '...');
                })
                ->addColumn('status', function ($row) {
                    return $row->status ? '<span class="badge bg-label-primary me-1">Aktiv</span>' : '<span class="badge bg-label-warning me-1">Gözləmədə</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.general_questions.edit', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bx-edit-alt me-2"></i></a>
                            <form action="' . route('admin.general_questions.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-light btn-sm"><i style="color: red" class="bx bx-trash me-2"></i></button>
                            </form>';
                })
                ->rawColumns(['id', 'status','answer', 'actions'])
                ->toJson();

        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }


    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $languages = $this->languages;
        return view('admin.general_questions.edit-add',compact('languages'));
    }


    public function store(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $languages = $this->languages;
        try {
            $data = [
                'question' => [],
                'answer' => [],
                'status' => $request->status
            ];

            foreach ($languages as $language) {
                $data['question'][$language] = $request->get("question_$language");
                $data['answer'][$language] = $request->get("answer_$language");
            }
            $relatedData = [];

            $general_questions = $this->general_questionsRepository->create(data: $data, relatedData: $relatedData);

            if ($general_questions) {
                flash()->addFlash('success', 'Yüklənmə', 'Sual Cavab uğurla yükləndi', ['timeout' => 3000, 'position' => 'top-center']);
                return redirect()->route('admin.general_questions');
            } else {
                flash()->addFlash('error', 'Yüklənmə', 'Sual Cavab yüklənərkən xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
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
            $columns = ['id', 'question','answer', 'status', 'created_at'];
            $relations = [];
            $general_questions = $this->general_questionsRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );

            if (!$general_questions) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.general_questions.edit-add', compact('general_questions','languages'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }


    public function update(Request $request, $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $languages = $this->languages;
        $data = [
            'question' => [],
            'answer' => [],
            'status' => $request->status
        ];
        foreach ($languages as $language) {
            $data['question'][$language] = $request->get("question_$language");
            $data['answer'][$language] = $request->get("answer_$language");
        }
        $relatedData = [];
        try {
            $updated = $this->general_questionsRepository->update(id: $id, filters: [], data: $data, callback: null, filler: null, relatedData: $relatedData);
            if ($updated) {
                flash()->addFlash('success', 'Yenilənmə', 'Sual Cavab uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.general_questions');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Sual Cavab yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function destroy($id)
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
            $deletedRows = $this->general_questionsRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            if ($deletedRows) {
                flash()->addFlash('success', 'Silinmə', 'Sual Cavab uğurla silindi', ['timeout' => 1500, 'position' => 'top-center']);
            } else {
                flash()->addFlash('error', 'Silinmə', 'Sual Cavab silinərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
            return back();
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function upload(Request $request)
    {
        try {
            $folder = 'general_questions';
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

            $uploadFile = $this->general_questionsRepository->uploadFile(
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
