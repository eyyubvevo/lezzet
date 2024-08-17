@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Atributlar /</span>Bütün Atributlar</h4>
            <!-- Basic Bootstrap Table -->
            <div class="card">
                <div class="card-body">
                    <table class="table nowrap data-table" id="attributes">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Adı</th>
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
            $('#attributes').DataTable({
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
                pageLength: 10,
                pagingType: 'simple_numbers',
                ajax: '{!! route('admin.attributes.list') !!}',
                "initComplete": function () {

                },
                select: {
                    style: 'multi'
                },
                columns: [
                    // {data: "checkbox",name: 'checkbox'},
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions',orderable: false,searchable: false},
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
                    "sEmptyTable":    "Cədvəldə heç bir məlumat yoxdur",
                    "sInfo":          " _TOTAL_ atributdən _START_ - _END_ arasındakı atributlər göstərılır",
                    "sInfoEmpty":     "Açar söz yoxdur",
                    "sInfoFiltered":  "( _MAX_ atribut içindən axtarış)",
                    "sInfoPostFix":   "",
                    "sInfoThousands": ",",
                    "sLengthMenu":    "Səhifədə _MENU_ atribut göstər",
                    "sLoadingRecords": "Yüklənir...",
                    "sProcessing":    "İşlənir...",
                    "sSearch":        "Axtar:",
                    "sZeroRecords":   "Uyğun gələn atribut tapılmadı",
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
