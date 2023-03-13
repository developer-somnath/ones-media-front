<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="bredcrames-sec">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title ? $title : '' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="main-part-home">
        <div class="container">
            <div>
                <div class="page-title">
                    <h2>{{ $title }}</h2>
                </div>
                <div class="row">
                        <div class="col-md-12">
                            <p>{!! $cmsList->description !!}</h5>
                           {{--   <div class="popular-box">
                                <a href="{{ url('show/details/' . $product->id) }}">
                                    <div class="popular-box-container">
                                        <img
                                            src="{{ env('IMAGE_URL') }}uploads/categories/{{ $product->categorySlug }}/{{ $product->image }}">
                                    </div>
                                    <h3>{{ $product->title }}</h3>
                                </a>
                            </div>  --}}
                        </div>

                </div>
            </div>

        </div>

    </div>
@stop
@push('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).on('click', '.filterData', function(e) {
            //console.log('clicked');
            var filter_value = $(this).attr('value');
            //alert(filter_value)
            $('#filter').val(filter_value);
            $("#showForm").submit();
            //const currenturl = "{{ url()->full() }}";
            //alert(currenturl);
            //window.location.href = baseUrl+'?type='+value;
        });
        $(document).on('change', '.cd', function(e) {
            var value = $(this).val();
            $('#type').val(value);
            $("#showForm").submit();
            //const currenturl = "{{ url()->full() }}";
            //alert(currenturl);
            //window.location.href = baseUrl+'?type='+value;
        });
        $(document).ready(function() {
            loadData();
        })
        const loadData = () => {
            //   $('#bannerTable').DataTable().destroy();
            var dataTable = $('#showallDatatable').DataTable({
                {{--  dom: 'Bfrtip',  --}}
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: true,
                searching: false,


            });
        }
    </script>
@endpush
