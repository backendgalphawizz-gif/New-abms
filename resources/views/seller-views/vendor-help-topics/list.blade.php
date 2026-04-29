@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate(str_replace('-',' ',$page)))

@push('css_or_js')

@endpush
@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/Pages.png')}}" width="20" alt="">
                {{\App\CPU\translate('pages')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
    @include('seller-views.business-settings.vendor-pages-inline-menu')
    <!-- End Inlile Menu -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('help_topic')}} {{\App\CPU\translate('Table')}} </h5>
                       
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100"
                                id="dataTable" cellspacing="0"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{\App\CPU\translate('SL')}}</th>
                                    <th>{{\App\CPU\translate('Question')}}</th>
                                    <th class="min-w-200">{{\App\CPU\translate('Answer')}}</th>
                                    <th>{{\App\CPU\translate('Ranking')}}</th>
                                 
                                   
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($helps as $k=>$help)
                                    <tr id="data-{{$help->id}}">
                                        <td>{{$k+1}}</td>
                                        <td>{{$help['question']}}</td>
                                        <td>{{$help['answer']}}</td>
                                        <td>{{$help['ranking']}}</td>

                                      
                                       
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- add modal --}}
       
    </div>

    {{-- edit modal --}}

   
@endsection

@push('script')
   
@endpush
