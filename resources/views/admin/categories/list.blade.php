@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kateqoriyalar /</span>Bütün Kateqoriyalar
            </h4>
            <!-- Basic Bootstrap Table -->
            <div class="card">
                <div class="card-body">
                    <table class="table nowrap data-table" id="categories">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Şəkli</th>
                            <th>Adı</th>
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

            <hr class="my-5" />

        </div>
        <!-- / Content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#categories').DataTable({
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
                ajax: '{!! route('admin.categories.list') !!}',
                "initComplete": function () {

                },
                select: {
                    style: 'multi'
                },
                columns: [
                    // {data: "checkbox",name: 'checkbox'},
                    {data: 'id', name: 'id'},
                    {data: 'image', name: 'image'},
                    {data: 'name', name: 'name'},
                    {data: 'status', name: 'status'},
                     {data: 'order', name: 'order'},
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
                    "sInfo":          " _TOTAL_ Kateqoriyadən _START_ - _END_ arasındakı Kateqoriyalər göstərılır",
                    "sInfoEmpty":     "Kateqoriya yoxdur",
                    "sInfoFiltered":  "( _MAX_ Kateqoriya içindən axtarış)",
                    "sInfoPostFix":   "",
                    "sInfoThousands": ",",
                    "sLengthMenu":    "Səhifədə _MENU_ Kateqoriya göstər",
                    "sLoadingRecords": "Yüklənir...",
                    "sProcessing":    "İşlənir...",
                    "sSearch":        "Axtar:",
                    "sZeroRecords":   "Uyğun gələn Kateqoriya tapılmadı",
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
