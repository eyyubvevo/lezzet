<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Repositories\EloquentContactRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    protected EloquentContactRepository $contactRepository;

    public function __construct(EloquentContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function viewedIndex()
    {
        return view('admin.contacts.list');
    }

    public function notViewedIndex()
    {
        return view('admin.contacts.notList');
    }

    public function list()
    {
        try {
            $columns = ['id', 'message', 'is_read', 'created_at'];
            $orderBy = 'created_at';
            $sortBy = 'DESC';
            $joins = [];
            $relations = [];
            $perPage = null;
            $filters = ['is_read' => ['operator' => '=', 'value' => 1],];
            $results = $this->contactRepository->all(
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
                ->addColumn('message', function ($row) {
                    return Str::limit($row->message, 20, '...');
                })
                ->addColumn('is_read', function ($row) {
                    return $row->is_read ? '<span class="badge bg-label-primary me-1">Aktiv</span>' : '<span class="badge bg-label-warning me-1">Gözləmədə</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.contacts.show', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bxs-show me-2"></i></a>
                            <form action="' . route('admin.contacts.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-light btn-sm"><i style="color: red" class="bx bx-trash me-2"></i></button>
                            </form>';
                })
                ->rawColumns(['id', 'message', 'is_read', 'actions'])
                ->toJson();

        } catch (\Exception $e) {
            // return response()->json(['error' => 'Veri çekerken bir hata oluştu. Hata: ' . $e->getMessage()], 500);
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }

    public function notList()
    {
        try {
            $columns = ['id', 'message', 'is_read', 'created_at'];
            $orderBy = 'created_at';
            $sortBy = 'DESC';
            $joins = [];
            $relations = [];
            $perPage = null;
            $filters = ['is_read' => ['operator' => '=', 'value' => 0],];
            $results = $this->contactRepository->all(
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
                ->addColumn('message', function ($row) {
                    return Str::limit($row->message, 20, '...');
                })
                ->addColumn('is_read', function ($row) {
                    return $row->is_read ? '<span class="badge bg-label-primary me-1">Baxılıb</span>' : '<span class="badge bg-label-warning me-1">Baxılmayıb</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return strftime('%d %B %Y', strtotime($row->created_at));
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('admin.contacts.show', ['id' => $row->id]) . '" class="btn btn-light btn-sm"><i style="color: #0a58ca" class="bx bxs-show me-2"></i></a>
                            <form action="' . route('admin.contacts.delete', ['id' => $row->id]) . '" method="POST" style="display:inline">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-light btn-sm"><i style="color: red" class="bx bx-trash me-2"></i></button>
                            </form>';
                })
                ->rawColumns(['id', 'message', 'is_read', 'actions'])
                ->toJson();

        } catch (\Exception $e) {
            // return response()->json(['error' => 'Veri çekerken bir hata oluştu. Hata: ' . $e->getMessage()], 500);
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
            $columns = ['id', 'name', 'phone', 'email', 'message', 'is_read', 'created_at'];
            $relations = [];
            $contact = $this->contactRepository->first(
                filters: $filters,
                columns: $columns,
                relations: $relations,
                joins: $joins,
                useCache: false
            );

            if (!$contact) {
                flash()->addFlash('warning', 'Xəbərdarlıq', 'Mövcud deyil', ['timeout' => 3000, 'position' => 'top-center']);
            }
            if ($contact->is_read == 0) {
                $data = [
                    'is_read' => 1,
                ];
                $this->contactRepository->update(id: $id, data: $data);
            }

            return view('admin.contacts.show', compact('contact'));
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
            $deletedRows = $this->contactRepository->delete($ids, filters: $filters, callbackBefore: $callbackBefore, callbackAfter: $callbackAfter, relations: $relations);
            if ($deletedRows) {
                flash()->addFlash('success', 'Silinmə', 'Müraciət uğurla silindi', ['timeout' => 1500, 'position' => 'top-center']);
            } else {
                flash()->addFlash('error', 'Silinmə', 'Müraciət silinərkən xəta baş verdi', ['timeout' => 1500, 'position' => 'top-center']);
            }
            return back();
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
            return back();
        }
    }

}
