@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Sifarişlər /</span>Bütün Sifarişlər</h4>            <!-- Basic Bootstrap Table -->
            <div class="card">
                <div class="card-body">
                    <table class="table nowrap data-table" id="orders">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Adı</th>
                            <th>Məbləğ</th>
                            <th>Toplam Məbləğ</th>
                            <th>Status</th>
                            <th>Yaradılma Tarixi</th>
                            <th>Əməliyyatlar</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!--/ Basic Bootstrap Table -->

            <hr class="my-5" />

        </div>
        <!-- / Content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#orders').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                rowReorder: false,
                stateSave: true,
                searching: true,
                paging: true,
                pageLength: 10,
                pagingType: 'simple_numbers',
                ajax: '{!! route('admin.orders.list') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'total', name: 'total'},
                    {data: 'subtotal', name: 'subtotal'},
                    {data: 'is_confirmation', name: 'is_confirmation'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions',orderable: false,searchable: false},
                ],
                language: {
                    "sEmptyTable":    "Cədvəldə heç bir məlumat yoxdur",
                    "sInfo":          " _TOTAL_ Sifariş _START_ - _END_ arasındakı Sifariş göstərılır",
                    "sInfoEmpty":     "Sifariş yoxdur",
                    "sInfoFiltered":  "( _MAX_ Sifariş içindən axtarış)",
                    "sInfoPostFix":   "",
                    "sInfoThousands": ",",
                    "sLengthMenu":    "Səhifədə _MENU_ Sifariş göstər",
                    "sLoadingRecords": "Yüklənir...",
                    "sProcessing":    "İşlənir...",
                    "sSearch":        "Axtar:",
                    "sZeroRecords":   "Uyğun gələn Sifariş tapılmadı",
                    "oPaginate": {
                        "sFirst":    "İlk",
                        "sLast":     "Son",
                        "sNext":     "Növbəti",
                        "sPrevious": "Əvvəlki"
                    },
                    "oAria": {
                        "sSortAscending":  ": artan sütun sıralamasını aktivləşdir",
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
