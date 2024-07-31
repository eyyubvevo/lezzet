<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\EloquentAttributeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class AttributeController extends Controller
{
    protected EloquentAttributeRepository $attributeRepository;
    protected $languages;

    public function __construct(EloquentAttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->languages = config('translatable.locales');
    }

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.attributes.list');
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
            $results = $this->attributeRepository->all(
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
                    return '<a href="' . route('admin.attributes.edit', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bx-edit-alt me-2"></i></a>
                            <form action="' . route('admin.attributes.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
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
        $categories = Category::all();
        return view('admin.attributes.edit-add', compact('languages','categories'));
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
            $attribute = $this->attributeRepository->create(data: $data, relatedData: $relatedData);
            if ($request->category_id){
                $attribute->categories()->attach($request->input('category_id'));
            }
            if ($attribute) {
                flash()->addFlash('success', 'Yüklənmə', 'Atribut uğurla yükləndi', ['timeout' => 3000, 'position' => 'top-center']);
                return redirect()->route('admin.attributes');
            } else {
                flash()->addFlash('error', 'Yüklənmə', 'Atribut yüklənərkən xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
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
            $attribute = $this->attributeRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );
            $categories = Category::all();
            if (!$attribute) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.attributes.edit-add', compact('attribute', 'languages','categories'));
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
            $updated = $this->attributeRepository->update(id: $id, data: $data);
            if ($updated) {
                if ($request->category_id){
                    $updated->categories()->sync($request->input('category_id'));
                }
                flash()->addFlash('success', 'Yenilənmə', 'Atribut uğurla yeniləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.attributes');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Atribut yenilənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
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
            $deletedRows = $this->attributeRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            if ($deletedRows) {
                flash()->addFlash('success', 'Silinmə', 'Atribut uğurla silindi', ['timeout' => 1500, 'position' => 'top-center']);
            } else {
                flash()->addFlash('error', 'Silinmə', 'Atribut silinərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
            return back();
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
