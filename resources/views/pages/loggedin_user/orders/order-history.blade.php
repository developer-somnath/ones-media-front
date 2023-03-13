@push('css')
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.logged_in_master')
@section('content')
    <div class="col-md-8">
        <div class="my-acc-right">
            <h4>{{ $title ? $title : '' }}</h4>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="orderTable" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Shipping Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
      <script>
        $(document).ready(function() {
            loadData()
        })
        const loadData = () => {
            $('#orderTable').DataTable().destroy();
            var dataTable = $('#orderTable').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('ajax-order-list') }}",
                    type: 'POST',
                    data: function(data) {
                        // if($('#searchEmail').val()!=""){
                        //    searchEmail = $('#searchEmail').val();
                        // }
                        // if($('#searchName').val()!=""){
                        //    searchName = $('#searchName').val();
                        // }
                        // if($('#searchFormDate').val()!=""){
                        //    searchFormDate = $('#searchFormDate').val();
                        // }
                        // if($('#searchToDate').val()!=""){
                        //    searchToDate = $('#searchToDate').val();
                        // }
                        data._token = _token
                        // data.searchEmail  = searchEmail,
                        // data.searchName      = searchName,
                        // data.searchFormDate  = searchFormDate,
                        // data.searchToDate = searchToDate
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'order_alt_id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'oder_amount'
                    },
                    {
                        data: 'payment_status'
                    },
                    {
                        data: 'shipment_status'
                    },
                    {
                        data: 'action'
                    }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }]
                // buttons: [
                //    {
                //         extend: 'excelHtml5',
                //         text: 'Export Excel ',
                //         filename: function () { return 'Customer List ' + new Date(); },
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4, 5],
                //             modifier: {
                //                 order: 'current',                       
                //                 page:  'all',
                //             }                    
                //         }
                //     },
                //     {
                //      text: 'Export Full Data In Excel',
                //      action: function ( e, dt, button, config ) {
                //        window.location = baseUrl+'customer-management/export-customer-list';
                //      }        
                //    }
                //  ]
            });


        }
    </script>
@endpush
