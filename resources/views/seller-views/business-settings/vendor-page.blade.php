@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate(str_replace('-',' ',$page)))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/Pages.png')}}" alt="">{{ \App\CPU\translate('Vendor Static Pages') }}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('seller-views.business-settings.vendor-pages-inline-menu')
        <!-- End Inlile Menu -->

        @php( $page_data= json_decode($data->value, true))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                

                        <div class="card-header">
                            <h5 class="mb-0">{{\App\CPU\translate(str_replace('-',' ',$page))}}</h5>
                           
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                {!! $page_data['content'] !!}
                            </div>

                            
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{--ck editor--}}
    {{-- 
        <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
        <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
        <script>
            $('#editor').ckeditor({
                contentsLangDirection : '{{Session::get('direction')}}',
            });
        </script>
    --}}

    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('value');
    </script>

    {{--ck editor--}}
@endpush
