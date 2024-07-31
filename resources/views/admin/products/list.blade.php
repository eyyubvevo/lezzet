@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Məhsullar /</span>Bütün Məhsullar</h4>
            <!-- Basic Bootstrap Table -->
            <div class="card">
                <div class="card-body">
                    <table class="table nowrap data-table" id="products">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Şəkli</th>
                            <th>Adı</th>
                            <th>Atributu</th>
                            <th>Kateqoriyasi</th>
                            <th>Qiyməti</th>
                            <th>Statusu</th>
                            <th>Sıra</th>
                            <th>Yaradılma Tarixi</th>
                            <th>Əməliyyatlar</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!--/ Basic Bootstrap Table -->

            <hr class="my-5"/>

        </div>
        <!-- / Content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#products').DataTable({
                columnDefs: [
                    {
                        targets: [0],
                        orderable: false,
                        checkboxes: {
                            selectRow: true
                        }
                    }
                ],
                processing: true,
                serverSide: true,
                responsive: true,
                rowReorder: false,
                stateSave: true,
                searching: true,
                paging: true,
                lengthChange: true,
                orderCellsTop: true,
                fixedHeader: true,
                searchBuilder: true,
                buttons: true,
                RowGroup: true,
                select: true,
                searchPanes: true,
                pageLength: 10,
                pagingType: 'simple_numbers',
                ajax: '{!! route('admin.products.list') !!}',
                "initComplete": function () {

                },
                // select: {
                //     style: 'multi'
                // },
                columns: [
                    // {data: "checkbox",name: 'checkbox'},
                    {data: 'id', name: 'id'},
                    {data: 'image', name: 'image'},
                    {data: 'title', name: 'title'},
                    {data: 'attribute', name: 'attribute'},
                    {data: 'category', name: 'category'},
                    {data: 'price', name: 'price'},
                    {data: 'status', name: 'status'},
                    {data: 'order', name: 'order'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],
                language: {
                    "sEmptyTable": "Cədvəldə heç bir məlumat yoxdur",
                    "sInfo": " _TOTAL_ məhsuldən _START_ - _END_ arasındakı məhsullər göstərılır",
                    "sInfoEmpty": "Məhsul yoxdur",
                    "sInfoFiltered": "( _MAX_ məhsul içindən axtarış)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ",",
                    "sLengthMenu": "Səhifədə _MENU_ məhsul göstər",
                    "sLoadingRecords": "Yüklənir...",
                    "sProcessing": "İşlənir...",
                    "sSearch": "Axtar:",
                    "sZeroRecords": "Uyğun gələn məhsul tapılmadı",
                    "oPaginate": {
                        "sFirst": "İlk",
                        "sLast": "Son",
                        "sNext": "Növbəti",
                        "sPrevious": "Əvvəlki"
                    },
                    "oAria": {
                        "sSortAscending": ": artan sütun sıralamasını aktivləşdir",
                        "sSortDescending": ": azalan sütun sıralamasını aktivləşdir"
                    }
                },
            });
            $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
                JSON.parse(settings.jqXHR.responseText, function (k, v) {
                    if (k == 'message' && v == 'Unauthenticated.') {
                        location.reload();
                    }
                });
            };
        });
    </script>
@endpush
