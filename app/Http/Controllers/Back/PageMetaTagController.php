<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Repositories\EloquentPageMetaTagRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PageMetaTagController extends Controller
{
    protected EloquentPageMetaTagRepository $pageMetaTagRepository;
    protected $languages;

    public function __construct(EloquentPageMetaTagRepository $pageMetaTagRepository)
    {
        $this->pageMetaTagRepository = $pageMetaTagRepository;
        $this->languages = config('translatable.locales');
    }
    public function index()
    {
        return view('admin.page_meta_tags.list');
    }

    public function list(): JsonResponse
    {
        try {
            $columns = ['id', 'page_name', 'created_at'];
            $orderBy = 'created_at';
            $sortBy = 'DESC';
            $joins = [];
            $relations = [];
            $perPage = null;
            $filters = [];
            $results = $this->pageMetaTagRepository->all(
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
                ->addColumn('page_name', function ($row) {
                    return $row->page_name;
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.page_meta_tags.edit', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bx-edit-alt me-2"></i></a>
                            <form action="' . route('admin.page_meta_tags.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-light btn-sm"><i style="color: red" class="bx bx-trash me-2"></i></button>
                            </form>';
                })
                ->rawColumns(['id', 'actions'])
                ->toJson();

        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $languages = $this->languages;
        return view('admin.page_meta_tags.edit-add', compact('languages'));
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $languages = $this->languages;
            $filters = [
                'id' => ['operator' => '=', 'value' => $id],
            ];
            $joins = [];
            $columns = ['id', 'title','description','keywords'];
            $relations = [];
            $page_meta_tag = $this->pageMetaTagRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );
            if (!$page_meta_tag) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.page_meta_tags.edit-add', compact('page_meta_tag', 'languages'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function store(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $languages = $this->languages;
        try {
            $data = [
                'page_name' => $request->page_name,
                'title' => [],
                'description' => [],
                'keywords' => [],
            ];

            foreach ($languages as $language) {
                $data['title'][$language] = $request->get("title_$language");
                $data['description'][$language] = $request->get("description_$language");
                $data['keywords'][$language] = $request->get("keywords_$language");
            }
            $relatedData = [];

            $general_questions = $this->pageMetaTagRepository->create(data: $data, relatedData: $relatedData);

            if ($general_questions) {
                flash()->addFlash('success', 'Yüklənmə', 'Seo Parametrləri uğurla yükləndi', ['timeout' => 3000, 'position' => 'top-center']);
                return redirect()->route('admin.page_meta_tags');
            } else {
                flash()->addFlash('error', 'Yüklənmə', 'Seo Parametrləri yüklənərkən xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function update(Request $request, $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $languages = $this->languages;
        $data = [
            'title' => [],
            'description' => [],
            'keywords' => [],
        ];
        foreach ($languages as $language) {
            $data['title'][$language] = $request->get("title_$language");
            $data['description'][$language] = $request->get("description_$language");
            $data['keywords'][$language] = $request->get("keywords_$language");
        }
        $relatedData = [];
        try {
            $updated = $this->pageMetaTagRepository->update(id: $id, filters: [], data: $data, callback: null, filler: null, relatedData: $relatedData);
            if ($updated) {
                flash()->addFlash('success', 'Yenilənmə', 'Seo Parametrləri uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.page_meta_tags');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Seo Parametrləri yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
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
            $deletedRows = $this->pageMetaTagRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            if ($deletedRows) {
                flash()->addFlash('success', 'Silinmə', 'Seo Parametrləri uğurla silindi', ['timeout' => 1500, 'position' => 'top-center']);
            } else {
                flash()->addFlash('error', 'Silinmə', 'Seo Parametrləri silinərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
            return back();
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
