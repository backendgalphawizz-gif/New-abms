@extends('theme-views.layouts.app')

@section('title', translate('My_Wishlists').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3">
        <div class="container">
          
            <div class="row g-3">

            <div class="col-md-12">
            <div class="bread__crum">
                    <nav aria-label="breadcrumb">
                                <ol class="breadcrumb fs-12 mb-0">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">{{ translate('home') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{translate('Account')}}</li>
                                </ol>
                        </nav>
              </div>
            </div>

                <!-- Sidebar-->
                @include('theme-views.partials._profile-aside')



                <div class="col-lg-9">
                    <div class="d-flex gap-4 flex-wrap d-lg-none mb-3 mt-2 justify-content-between">
                        @if($wishlists->count()>0)
                        <a href="javascript:" onclick="route_alert('{{ route('delete-wishlist-all') }}','{{translate('want_to_clear_all_wishlist?')}}')" class="btn-link text-danger">{{translate('Clear_All')}}</a>
                        @endif
                        <button type="button" class="btn btn-primary">Add All to Cart</button>
                    </div>
                    

                    <div class="card h-lg-100 py-2 profilecard">
                        <div class="card-body px-lg-4 pb-lg-4 pt-lg-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>{{translate('My_Wish_List')}}</h5>
                                <div class="d-lg-flex gap-4 flex-wrap d-none">
                                    @if($wishlists->count()>0)
                                    <a href="javascript:" onclick="route_alert('{{ route('delete-wishlist-all') }}','{{translate('want_to_clear_all_wishlist?')}}')" class="btn-link text-danger">{{translate('Clear_All')}}</a>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4" id="set-wish-list">
                                @include('theme-views.partials._wish-list-data',['wishlists'=>$wishlists, 'brand_setting'=>$brand_setting])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection
