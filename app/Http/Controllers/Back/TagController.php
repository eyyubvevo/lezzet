<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\EloquentTagRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    protected EloquentTagRepository $tagRepository;
    protected $languages;

    public function __construct(EloquentTagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->languages = config('translatable.locales');
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.tags.list');
    }

    public function list(): JsonResponse
    {
        try {
            $columns = ['id', 'name'];
            $orderBy = 'created_at';
            $sortBy = 'DESC';
            $joins = [];
            $relations = [];
            $perPage = null;
            $filters = [];
            $results = $this->tagRepository->all(
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
                ->addColumn('name', function ($row) {
                    return Str::limit($row->getTranslation('name', 'az'), 20, '...');
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.tags.edit', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bx-edit-alt me-2"></i></a>
                            <form action="' . route('admin.tags.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
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
        $products = Product::all();
        return view('admin.tags.edit-add', compact('languages', 'products'));
    }

    public function store(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $languages = $this->languages;
            $data = [
                'name' => [],
            ];
            foreach ($languages as $language) {
                $data['name'][$language] = $request->get("name_$language");
            }
            $relatedData = [];
            $tag = $this->tagRepository->create(data: $data, relatedData: $relatedData);
            if ($request->product_id) {
                $tag->products()->attach($request->input('product_id'));
            }
            if ($tag) {
                flash()->addFlash('success', 'Yüklənmə', 'Açar söz uğurla yükləndi', ['timeout' => 3000, 'position' => 'top-center']);
                return redirect()->route('admin.tags');
            } else {
                flash()->addFlash('error', 'Yüklənmə', 'Açar söz yüklənərkən xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
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
            $columns = ['id', 'name'];
            $relations = [];
            $tag = $this->tagRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );
            $products = Product::all();
            if (!$tag) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.tags.edit-add', compact('tag', 'languages', 'products'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function update(Request $request, $id): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $languages = $this->languages;
            $data = [
                'name' => [],
            ];
            foreach ($languages as $language) {
                $data['name'][$language] = $request->get("name_$language");
            }
            $updated = $this->tagRepository->update(id: $id, data: $data);
            if ($request->product_id) {
                $updated->products()->sync($request->input('product_id'));
            }else{
                $updated->products()->detach();
            }
            if ($updated) {
                flash()->addFlash('success', 'Yenilənmə', 'Açar söz uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.tags');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Açar söz yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
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
            $deletedRows = $this->tagRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            if ($deletedRows) {
                flash()->addFlash('success', 'Silinmə', 'Açar söz uğurla silindi', ['timeout' => 1500, 'position' => 'top-center']);
            } else {
                flash()->addFlash('error', 'Silinmə', 'Açar söz silinərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
            return back();

        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
