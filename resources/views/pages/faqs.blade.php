<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@extends('layouts.master')
{{--  @push('css')
    <style>
        .faqBgColor-header {
            background-color: #e9ecef
        }
    </style>
@endpush  --}}

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
        <div class="page-title">
            <h2>FAQ</h2>
        </div>
        <div class="demo">
            <div class="accordion" id="accordionExample">
                @forelse ($faqList as $faq)
                    <div class="card">
                        <div class="card-header faqBgColor" id="headingTwo" style="background-color: #e9ecef">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                                    data-target="{{ '#' . $faq->question }}"> {{ $faq->question }}</button>
                            </h2>
                        </div>
                        <div id="collapseTwo{{ $faq->question }}" class="collapse show" aria-labelledby="headingTwo"
                            data-parent="#accordionExample">
                            <div class="card-body">
                                <p>{!! $faq->answer !!}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <h3>No Data Found !!</h3>
                    </div>
                @endforelse

            </div>
        </div>
        {{--   <div id="accordion">
            @forelse ($faqList as $faq)
            <div class="card">
                <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#collapseOne">
                        {{ $faq->question }}
                    </a>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#accordion">
                    <div class="card-body">
                         {!! $faq->answer !!}
                    </div>
                </div>
            </div>
            @empty
            <div class="card">
               <h3>No Data Found !!</h3>
            </div>
            @endforelse
        </div>  --}}
    </div>








@stop
@push('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
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
    {{--   $(document).ready(function () {
    $(".collapse.show").each(function () {
    $(this)
    .prev(".card-header")
    .find(".fa")
    .addClass("fa-minus")
    .removeClass("fa-plus");
    });

   
    $(".collapse")
    .on("show.bs.collapse", function () {
    $(this)
    .prev(".card-header")
    .find(".fa")
    .removeClass("fa-plus")
    .addClass("fa-minus");
    })
    .on("hide.bs.collapse", function () {
    $(this)
    .prev(".card-header")
    .find(".fa")
    .removeClass("fa-minus")
    .addClass("fa-plus");
    });
    });  --}}
@endpush
