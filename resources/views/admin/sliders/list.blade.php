@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Slayderlər /</span>Bütün Slayderlər</h4>
            <!-- Basic Bootstrap Table -->
            <div class="card">
                <div class="card-body">
                    <table class="table nowrap data-table" id="sliders">
                        <thead>
                        <tr>
                            {{--                            <th></th>--}}
                            <th>ID</th>
                            <th>Şəkli</th>
                            <th>Adı</th>
                            <th>Statusu</th>
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
            $('#sliders').DataTable({
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
                ajax: '{!! route('admin.sliders.list') !!}',
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
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],
                // 'rowCallback': function(row, data, index){
                //     if(data.Status == 'Disponible'){
                //         $(row).find('td:eq(3)').css('background-color', 'green').css('color', 'white');
                //     }
                //     if(data.Status == 'Indisponible'){
                //         $(row).find('td:eq(3)').css('background-color', 'red').css('color', 'white');
                //     }
                // },
                language: {
                    "sEmptyTable": "Cədvəldə heç bir məlumat yoxdur",
                    "sInfo": " _TOTAL_ slayderdən _START_ - _END_ arasındakı slayderlər göstərılır",
                    "sInfoEmpty": "Slayder yoxdur",
                    "sInfoFiltered": "( _MAX_ slayder içindən axtarış)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ",",
                    "sLengthMenu": "Səhifədə _MENU_ slayder göstər",
                    "sLoadingRecords": "Yüklənir...",
                    "sProcessing": "İşlənir...",
                    "sSearch": "Axtar:",
                    "sZeroRecords": "Uyğun gələn slayder tapılmadı",
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
