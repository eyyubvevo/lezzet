@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Müraciətlər /</span>Baxılmış Müraciətlər</h4>
            <!-- Basic Bootstrap Table -->
            <div class="card">
                <div class="card-body">
                    <table class="table nowrap data-table" id="contacts">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mesajı</th>
                            <th>Statusu</th>
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
            $('#contacts').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                rowReorder: false,
                stateSave: true,
                searching: true,
                paging: true,
                pageLength: 10,
                pagingType: 'simple_numbers',
                ajax: '{!! route('admin.contacts.list') !!}',
                "initComplete": function () {

                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'message', name: 'message'},
                    {data: 'is_read', name: 'is_read'},
                    {data: 'created_at', name: 'created_at'},
                     {data: 'actions', name: 'actions',orderable: false,searchable: false},
                ],
                language: {
                    "sEmptyTable":    "Cədvəldə heç bir məlumat yoxdur",
                    "sInfo":          " _TOTAL_ müraciətdən _START_ - _END_ arasındakı müraciətlər göstərılır",
                    "sInfoEmpty":     "Müraciət yoxdur",
                    "sInfoFiltered":  "( _MAX_ müraciət içindən axtarış)",
                    "sInfoPostFix":   "",
                    "sInfoThousands": ",",
                    "sLengthMenu":    "Səhifədə _MENU_ müraciət göstər",
                    "sLoadingRecords": "Yüklənir...",
                    "sProcessing":    "İşlənir...",
                    "sSearch":        "Axtar:",
                    "sZeroRecords":   "Uyğun gələn müraciət tapılmadı",
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
