@extends('theme-views.layouts.app')

@section('title', translate('My_Profile').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))

@section('content')
    <!-- Main Content -->
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
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
                    <div class="card mb-3 d-none">
                        <div class="card-body">
                            <div class="d-flex gap-3 flex-wrap flex-grow-1">
                                <div class="card border flex-grow-1 shadow br-12">
                                    <div class="card-body grid-center">
                                        <div class="text-center">
                                            <h3 class="mb-2">{{ $total_order }}</h3>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <img width="16" src="{{ theme_asset('assets/img/icons/profile-icon2.png') }}" class="dark-support" alt="">
                                                <span>{{translate('Orders')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border flex-grow-1 shadow br-12">
                                    <div class="card-body grid-center">
                                        <div class="text-center">
                                            <h3 class="mb-2">{{ $wishlists }}</h3>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <img width="16" src="{{ theme_asset('assets/img/icons/profile-icon3.png') }}" class="dark-support" alt="">
                                                <span>{{translate('Wish_List')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border flex-grow-1 shadow br-12">
                                    <div class="card-body grid-center">
                                        <div class="text-center">
                                            <h3 class="mb-2">{{ \App\CPU\Helpers::currency_converter($total_wallet_balance) }}</h3>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <img width="16" src="{{theme_asset('assets/img/icons/profile-icon5.png')}}" class="dark-support" alt="">
                                                <span>{{translate('Wallet')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border flex-grow-1 shadow br-12">
                                    <div class="card-body grid-center">
                                        <div class="text-center">
                                            <h3 class="mb-2">{{$total_loyalty_point}}</h3>
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <img width="16" src="{{theme_asset('assets/img/icons/profile-icon6.png')}}" class="dark-support" alt="">
                                                <span>{{translate('Loyalty_Point')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-30 bg-light rounded p-3 d-none">
                                <div class="d-flex align-items-center flex-wrap justify-content-between gap-3">
                                    <h5>{{translate('Personal_Details')}}</h5>
                                    <a href="{{route('user-account')}}" class="btn btn-outline-secondary rounded-pill px-3 px-sm-4"><span class="d-none d-sm-inline-block">{{ translate('Edit_Profile') }}</span> <i class="bi bi-pencil-square"></i></a>
                                </div>

                                <div class="mt-4">
                                    <div class="row text-dark">
                                        <div class="col-md-6 col-xl-3 col-lg-4">
                                            <dl class="mb-0 flexible-grid" style="--width: 6rem">
                                                <dt>{{translate('First_Name')}}</dt>
                                                <dd>{{$customer_detail['f_name']}}</dd>

                                                <dt>{{translate('Last_Name')}}</dt>
                                                <dd>{{$customer_detail['l_name']}}</dd>
                                            </dl>
                                        </div>
                                        <div class="col-md-6 col-xl-3 col-lg-4">
                                            <dl class="mb-0 flexible-grid" style="--width: 6rem">
                                                <dt>{{translate('Phone')}}</dt>
                                                <dd><a href="tel:{{$customer_detail['phone']}}" class="text-dark">{{$customer_detail['phone']}}</a></dd>

                                                <dt>{{translate('Email')}}</dt>
                                                <dd><a href="mailto:{{$customer_detail['email']}}" class="text-dark">{{$customer_detail['email']}}</a></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card profilecard">
                        <div class="card-body">
                            <!-- <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>{{translate('My_Addresses')}}</h5>
                                <a href="{{route('account-address-add')}}" class="btn-save-later">
                                    <span class="d-none d-sm-inline-block">{{translate('Add_Address')}}</span> <i class="bi bi-geo-alt-fill"></i>
                                </a>
                            </div> -->

                            <div class="mt-3">
                                <div class="row gy-3 text-dark">
                                    @if (count($addresses) > 0)
                                            @foreach($addresses as $address)
                                            <div class="col-md-6">
                                                <div class="card br-12">
                                                    <div class="card-header  gap-2 align-items-center d-flex justify-content-between">
                                                        <h6>{{translate($address['address_type'])}}({{ $address['is_billing']==1 ? translate('Billing_Address'):translate('Shipping_Address') }})</h6>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <a href="{{route('address-edit',$address->id)}}" class="p-0 bg-transparent border-0">
                                                                <img src="{{theme_asset('assets/img/svg/location-edit.svg')}}" alt="" class="svg">
                                                            </a>

                                                            <!-- <a href="javascript:" onclick="route_alert('{{ route('address-delete',['id'=>$address->id]) }}','{{translate('want_to_delete_this_address?')}}')" id="delete" class="p-0 bg-transparent border-0">
                                                                <img src="{{theme_asset('assets/img/svg/delete.svg')}}" alt="" class="svg">
                                                            </a> -->
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <address>
                                                            <dl class="mb-0 flexible-grid" style="--width: 5rem">
                                                                <dt>{{translate('Name')}}</dt>
                                                                <dd>{{$address['contact_person_name']}}</dd>

                                                                <dt>{{translate('Phone')}}</dt>
                                                                <dd><a href="#" class="text-dark">{{$address['phone']}}</a></dd>

                                                                <dt>{{translate('Address')}}</dt>
                                                                <dd>{{$address['address']}}</dd>
                                                            </dl>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="col-lg-12">
                                                <div class="empty-content">
                                                    <div>                                    
                                                        <svg width="211" height="180" viewBox="0 0 211 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M183.985 35.2754H123.765C122.521 35.2754 121.506 36.2835 121.506 37.5346C121.506 38.7787 122.514 39.7939 123.765 39.7939H183.985C185.229 39.7939 186.245 38.7858 186.245 37.5346C186.237 36.2906 185.229 35.2754 183.985 35.2754Z" fill="#E3E1EC"/>
                                                            <path d="M113.713 35.2754H98.5132C97.2691 35.2754 96.2539 36.2835 96.2539 37.5346C96.2539 38.7787 97.262 39.7939 98.5132 39.7939H113.713C114.957 39.7939 115.972 38.7858 115.972 37.5346C115.972 36.2906 114.957 35.2754 113.713 35.2754Z" fill="#E3E1EC"/>
                                                            <path d="M186.38 175.191H126.16C124.916 175.191 123.9 176.199 123.9 177.451C123.9 178.695 124.908 179.71 126.16 179.71H186.38C187.624 179.71 188.639 178.702 188.639 177.451C188.632 176.199 187.624 175.191 186.38 175.191Z" fill="#E3E1EC"/>
                                                            <path d="M116.108 175.191H100.908C99.6636 175.191 98.6484 176.199 98.6484 177.451C98.6484 178.695 99.6565 179.71 100.908 179.71H116.108C117.352 179.71 118.367 178.702 118.367 177.451C118.367 176.199 117.352 175.191 116.108 175.191Z" fill="#E3E1EC"/>
                                                            <path d="M1.60864 161.514H44.4699C45.3564 161.514 46.0786 160.792 46.0786 159.906C46.0786 159.019 45.3564 158.297 44.4699 158.297H1.60864C0.7221 158.297 0 159.019 0 159.906C0 160.799 0.7221 161.514 1.60864 161.514Z" fill="#E3E1EC"/>
                                                            <path d="M51.6262 161.514H62.4434C63.33 161.514 64.0521 160.792 64.0521 159.906C64.0521 159.019 63.33 158.297 62.4434 158.297H51.6262C50.7397 158.297 50.0176 159.019 50.0176 159.906C50.0176 160.799 50.7397 161.514 51.6262 161.514Z" fill="#E3E1EC"/>
                                                            <path d="M148.031 169.694H190.892C191.778 169.694 192.5 168.972 192.5 168.085C192.5 167.199 191.778 166.477 190.892 166.477H148.031C147.144 166.477 146.422 167.199 146.422 168.085C146.422 168.972 147.137 169.694 148.031 169.694Z" fill="#E3E1EC"/>
                                                            <path d="M198.048 169.694H208.865C209.752 169.694 210.474 168.972 210.474 168.085C210.474 167.199 209.752 166.477 208.865 166.477H198.048C197.162 166.477 196.439 167.199 196.439 168.085C196.439 168.972 197.154 169.694 198.048 169.694Z" fill="#E3E1EC"/>
                                                            <path d="M102.352 0H72.2458C71.6238 0 71.1162 0.507616 71.1162 1.12962C71.1162 1.75163 71.6238 2.25925 72.2458 2.25925H102.352C102.974 2.25925 103.482 1.75163 103.482 1.12962C103.482 0.507616 102.982 0 102.352 0Z" fill="#E3E1EC"/>
                                                            <path d="M67.2198 0H59.6198C58.9978 0 58.4902 0.507616 58.4902 1.12962C58.4902 1.75163 58.9978 2.25925 59.6198 2.25925H67.2198C67.8418 2.25925 68.3494 1.75163 68.3494 1.12962C68.3494 0.507616 67.8418 0 67.2198 0Z" fill="#E3E1EC"/>
                                                            <path d="M81.8397 165.354H51.7331C51.1111 165.354 50.6035 165.861 50.6035 166.483C50.6035 167.105 51.1111 167.613 51.7331 167.613H81.8397C82.4617 167.613 82.9693 167.105 82.9693 166.483C82.9693 165.861 82.4617 165.354 81.8397 165.354Z" fill="#E3E1EC"/>
                                                            <path d="M46.7071 165.354H39.1072C38.4851 165.354 37.9775 165.861 37.9775 166.483C37.9775 167.105 38.4851 167.613 39.1072 167.613H46.7071C47.3291 167.613 47.8367 167.105 47.8367 166.483C47.8367 165.861 47.3291 165.354 46.7071 165.354Z" fill="#E3E1EC"/>
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M92.7295 89.8764C92.7295 82.5449 98.6708 76.6035 106.002 76.6035C113.331 76.6035 119.271 82.5457 119.271 89.8764C119.271 97.2038 113.33 103.145 106.002 103.145C98.6716 103.145 92.7295 97.2046 92.7295 89.8764ZM106.002 82.7285C102.054 82.7285 98.8545 85.9276 98.8545 89.8764C98.8545 93.8203 102.053 97.0202 106.002 97.0202C109.947 97.0202 113.146 93.8211 113.146 89.8764C113.146 85.9268 109.946 82.7285 106.002 82.7285Z" fill="#0A9494"/>
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M105.998 62.3125C90.7992 62.3125 78.4375 74.7454 78.4375 90.1334C78.4375 99.906 84.2766 109.849 91.1411 117.577C94.5278 121.391 98.0467 124.53 100.97 126.693C102.435 127.776 103.704 128.58 104.695 129.098C105.451 129.493 105.863 129.623 105.998 129.666C106.133 129.623 106.546 129.493 107.301 129.098C108.292 128.58 109.562 127.776 111.027 126.692C113.95 124.53 117.47 121.39 120.857 117.577C127.723 109.848 133.563 99.9058 133.563 90.1334C133.563 74.7459 121.197 62.3125 105.998 62.3125ZM72.3125 90.1334C72.3125 71.4122 87.3671 56.1875 105.998 56.1875C124.628 56.1875 139.688 71.4116 139.688 90.1334C139.688 102.146 132.661 113.512 125.436 121.645C121.778 125.763 117.947 129.192 114.669 131.617C113.034 132.827 111.49 133.82 110.137 134.527C108.933 135.156 107.417 135.812 105.998 135.812C104.579 135.812 103.063 135.156 101.859 134.527C100.506 133.82 98.9627 132.826 97.3273 131.617C94.0499 129.192 90.2189 125.763 86.5616 121.645C79.338 113.512 72.3125 102.146 72.3125 90.1334Z" fill="#0A9494"/>
                                                        </svg>

                                                        <h3>{{translate('No_Address_Found')}}</h3>
                                                        <a href="{{route('account-address-add')}}" class="btn-login for-empty">{{translate('Add_New_Address')}}</a>
                                                    </div>
                                            </div>
                                            </div>
                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End Main Content -->
@endsection
