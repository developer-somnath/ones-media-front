<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@extends('layouts.logged_in_master')
@section('content')
    <div class="col-md-8">
        <div class="my-acc-right">
            <h4>{{ $title ? $title : '' }}</h4>
            <table class="w-100" id="purchasepageTable">
                <thead>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Purchase Type</th>
                    <th>Purchase Date</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @forelse ($purchaseDetails as $details)
                        
                        <tr>
                            <td>
                                <div class="list-p-img">
                                    {{--  <a href="{{ url('show/details/' . $productall->item_id) }}">  --}}
                                        <img
                                            src="{{ env('IMAGE_URL') }}uploads/categories/{{ $details->show?->category?->slug }}/{{ $details->show?->image }}">
                                    {{--  </a>  --}}
                                </div>
                            </td>
                            <td>
                                <div class="list-p-title">
                                    {{--  <a href="{{ url('show/details/' . $productall->item_id) }}">  --}}
                                        <h3>{{ $details->show?->title }}</h3>
                                    {{--  </a>  --}}
                                </div>
                            </td>

                            <td>
                                {!! $details->type == 1
                                    ? '<span class="badge badge-primary">Instant Download</span>'
                                    : '<span class="badge badge-secondary">CD</span>' !!}
                            </td>

                            <td>
                                {{ date('M d , Y', strtotime($details->created_at)) }}
                            </td>

                            <td>
                                <div class="list-p-btn">
                                    <a href="{{ url('download/' . $details->item_id) }}" class="t-add">Download</a>

                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" align="center">No Data Available!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
@push('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            loadData();
        })
        const loadData = () => {
            //   $('#bannerTable').DataTable().destroy();
            var dataTable = $('#purchasepageTable').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: true,
                searching: false,


            });
        }
    </script>
@endpush
