<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Repositories\EloquentOrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    protected EloquentOrderRepository $eloquentOrderRepository;
    public function __construct(EloquentOrderRepository $eloquentOrderRepository)
    {
        $this->eloquentOrderRepository = $eloquentOrderRepository;
    }

    public function index()
    {
        return view('admin.orders.list');
    }

    public function list(): JsonResponse
    {
        try {
            $columns = ['id', 'name','total','subtotal','is_confirmation', 'created_at'];
            $orderBy = 'created_at';
            $sortBy = 'DESC';
            $joins = [];
            $relations = [];
            $perPage = null;
            $filters = [];
            $results = $this->eloquentOrderRepository->all(
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
                    return  Str::limit($row->name, 20, '...');
                })
                ->editColumn('total', function ($row) {
                    return  $row->total.__('website.azn');
                })
                ->editColumn('subtotal', function ($row) {
                    return  $row->subtotal.__('website.azn');
                })
                ->addColumn('is_confirmation', function ($row) {
                    return $row->is_confirmation ? '<span class="badge bg-label-primary me-1">Təsdiqlənib</span>' : '<span class="badge bg-label-warning me-1">Təsdiqlənməyib</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.orders.show', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bxs-show me-2"></i></a>';
                })
                ->rawColumns(['id', 'total','subtotal', 'is_confirmation', 'actions'])
                ->toJson();

        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function show($id)
    {
        try {
            $filters = [
                'id' => ['operator' => '=', 'value' => $id],
            ];
            $joins = [];
            $columns = ['id', 'name','address', 'phone', 'message', 'is_confirmation','total','subtotal', 'created_at'];
            $relations = [];
            $order = $this->eloquentOrderRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );
            if (!$order) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function confirmation(Request $request)
    {
        try {
            $data = [
                'is_confirmation' => 1
            ];
            $updated = $this->eloquentOrderRepository->update(id: $request->is_confirmation, filters: [], data: $data, callback: null, filler: null, relatedData: []);
            if ($updated) {
                flash()->addFlash('success', 'Yenilənmə', 'Sifariş uğurla təsdiqləndi', ['timeout' => 1500, 'position' => 'top-center']);
                return redirect()->route('admin.orders');
            } else {
                flash()->addFlash('error', 'Yenilənmə', 'Sifariş təsdiqlənərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
