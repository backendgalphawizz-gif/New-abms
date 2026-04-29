<!-- Footer -->
<footer class="footer d-none">
    <div class="footer-bg-img" data-bg-img="{{theme_asset('assets/img/background/footer-bg.png')}}">

    </div>
    <div class="footer-top">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-lg-9 col-sm-6 d-flex justify-content-center justify-content-sm-start justify-content-lg-center">

                    <ul class="list-socials list-socials--white gap-4 fs-18">
                        @if($web_config['social_media'])
                        @foreach ($web_config['social_media'] as $item)
                        <li>
                            <a href="{{$item->link}}" target="_blank">
                                <i class="bi bi-{{($item->name == 'google-plus'?'google':$item->name)}}"></i>
                            </a>
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>
                <div class="col-lg-3 col-sm-6 d-flex justify-content-center justify-content-sm-start">
                    <div class="media gap-3 absolute-white">
                        <i class="bi bi-telephone-forward fs-28"></i>
                        <div class="media-body">
                            <h6 class="absolute-white mb-1">{{translate('Hotline')}}</h6>
                            <a href="tel:{{$web_config['phone']->value}}" class="absolute-white">{{$web_config['phone']->value}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-main px-2  px-lg-0">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4">
                    <div class="widget widget--about text-center text-lg-start absolute-white">
                        <img class="mb-3" width="180" src="{{asset("storage/app/public/company/")}}/{{ $web_config['footer_logo']->value }}"
                            onerror="this.src='{{theme_asset('assets/img/logo-white.png')}}'"
                            loading="lazy" alt="">
                        <p>{{ \App\CPU\Helpers::get_business_settings('shop_address')}}</p>
                        <a href="mailto:{{$web_config['email']->value}}">{{$web_config['email']->value}}</a>

                        <div class="d-flex gap-3 justify-content-center justify-content-lg-start flex-wrap mt-4">
                            @if($web_config['android']['status'])
                            <a href="{{ $web_config['android']['link'] }}"><img src="{{ theme_asset('assets/img/media/google-play.png') }}" loading="lazy" alt=""></a>
                            @endif
                            @if($web_config['ios']['status'])
                            <a href="{{ $web_config['ios']['link'] }}"><img src="{{ theme_asset('assets/img/media/app-store.png') }}" loading="lazy" alt=""></a>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row gy-5">
                        <div class="col-sm-4 col-6">
                            <div class="widget widget--nav absolute-white">
                                <h4 class="widget__title">{{translate('Accounts')}}</h4>
                                <ul class="d-flex flex-column gap-3">
                                    @if($web_config['seller_registration'])
                                    <li>
                                        <a href="{{route('shop.apply')}}">{{translate('Open_Your_Store')}}</a>
                                    </li>
                                    @endif
                                    <li>
                                        @if(auth('customer')->check())
                                        <a href="{{route('user-profile')}}">{{translate('Profile')}}</a>
                                        @else
                                        <button class="bg-transparent border-0 p-0" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('Profile')}}</button>
                                        @endif
                                    </li>
                                    <li>
                                        @if(auth('customer')->check())
                                        <a href="{{route('track-order.index') }}">{{translate('track_order')}}</a>
                                        @else
                                        <button class="bg-transparent border-0 p-0" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('track_order')}}</button>
                                        @endif
                                    </li>
                                    <li><a href="{{route('contacts')}}">{{translate('Help_&_Support')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="widget widget--nav absolute-white">
                                <h4 class="widget__title">{{translate('Quick_Links')}}</h4>
                                <ul class="d-flex flex-column gap-3">
                                    @if($web_config['flash_deals'])
                                    <li><a href="{{route('flash-deals',[$web_config['flash_deals']['id']])}}">{{translate('Flash_Deals')}}</a></li>
                                    @endif
                                    <li><a href="{{route('products',['data_from'=>'featured','page'=>1])}}">{{translate('Featured_Products')}}</a></li>
                                    <li><a href="{{route('sellers')}}">{{translate('Top_Stores')}}</a></li>
                                    <li><a href="{{route('products',['data_from'=>'latest'])}}">{{translate('Latest_Products')}}</a></li>
                                    <li><a href="{{route('helpTopic')}}">{{translate('FAQ')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="widget widget--nav absolute-white">
                                <h4 class="widget__title">{{translate('Other')}}</h4>
                                <ul class="d-flex flex-column gap-3">
                                    <li><a href="{{route('about-us')}}">{{translate('About_Company')}}</a></li>
                                    <li><a href="{{route('privacy-policy')}}">{{translate('Privacy_Policy')}}</a></li>
                                    <li><a href="{{route('terms')}}">{{translate('Terms_&_Conditions')}}</a></li>

                                    @if(isset($web_config['refund_policy']['status']) && $web_config['refund_policy']['status'] == 1)
                                    <li>
                                        <a href="{{route('refund-policy')}}">{{translate('refund_policy')}}</a>
                                    </li>
                                    @endif

                                    @if(isset($web_config['return_policy']['status']) && $web_config['return_policy']['status'] == 1)
                                    <li>
                                        <a href="{{route('return-policy')}}">{{translate('return_policy')}}</a>
                                    </li>
                                    @endif

                                    @if(isset($web_config['cancellation_policy']['status']) && $web_config['cancellation_policy']['status'] == 1)
                                    <li>
                                        <a href="{{route('cancellation-policy')}}">{{translate('cancellation_policy')}}</a>
                                    </li>
                                    @endif

                                    <li>
                                        @if(auth('customer')->check())
                                        <a href="{{route('account-tickets')}}">{{translate('Support_Ticket')}}</a>
                                        @else
                                        <button class="bg-transparent border-0 p-0" data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('Support_Ticket')}}</button>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom absolute-white">
        <div class="container">
            <div class="text-center copyright-text">
                {{ $web_config['copyright_text']->value }}
            </div>
        </div>
    </div>
</footer>

<div class="footer-space"></div>
<section class="footer-mobile pt-2 pb-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li class="{{Request::is('shop-cart') ? 'activenav' :''}}">
                        <a href="{{route('shop-cart')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M2.75 3.25L4.83 3.61L5.793 15.083C5.87 16.02 6.653 16.739 7.593 16.736H18.502C19.399 16.738 20.16 16.078 20.287 15.19L21.236 8.632C21.342 7.899 20.833 7.219 20.101 7.113C20.037 7.104 5.164 7.099 5.164 7.099"
                                    stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M13.375 10.7949C13.375 10.3807 13.7108 10.0449 14.125 10.0449H16.898C17.3122 10.0449 17.648 10.3807 17.648 10.7949C17.648 11.2091 17.3122 11.5449 16.898 11.5449H14.125C13.7108 11.5449 13.375 11.2091 13.375 10.7949Z"
                                    fill="#fff" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.15435 19.4521C6.43824 19.4521 5.86035 20.0328 5.86035 20.7461C5.86035 21.4596 6.43734 22.0411 7.15435 22.0411C7.87136 22.0411 8.44835 21.4596 8.44835 20.7461C8.44835 20.0328 7.87046 19.4521 7.15435 19.4521ZM18.4346 19.4521C17.7185 19.4521 17.1406 20.0328 17.1406 20.7461C17.1406 21.4596 17.7176 22.0411 18.4346 22.0411C19.1498 22.0411 19.7296 21.4614 19.7296 20.7461C19.7296 20.031 19.1489 19.4521 18.4346 19.4521Z"
                                    fill="#fff" />
                            </svg>
                            <br>
                            <small>{{translate('Cart')}}</small>
                        </a>
                    </li>
                    <li class="{{Request::is('products',['page'=>1]) ? 'activenav' :''}}">
                        <a href="{{ route('products',['page'=>1]) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3 6.5C3 3.87479 3.02811 3 6.5 3C9.97189 3 10 3.87479 10 6.5C10 9.12521 10.0111 10 6.5 10C2.98893 10 3 9.12521 3 6.5Z"
                                    stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M14 6.5C14 3.87479 14.0281 3 17.5 3C20.9719 3 21 3.87479 21 6.5C21 9.12521 21.0111 10 17.5 10C13.9889 10 14 9.12521 14 6.5Z"
                                    stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3 17.5C3 14.8748 3.02811 14 6.5 14C9.97189 14 10 14.8748 10 17.5C10 20.1252 10.0111 21 6.5 21C2.98893 21 3 20.1252 3 17.5Z"
                                    stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M14 17.5C14 14.8748 14.0281 14 17.5 14C20.9719 14 21 14.8748 21 17.5C21 20.1252 21.0111 21 17.5 21C13.9889 21 14 20.1252 14 17.5Z"
                                    stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <br>
                            <small>{{translate('Categories')}}</small>
                        </a>
                    </li>
                    <li class="{{Request::is('home') ? 'activenav' : ''}}">
                        <a href="{{route('home')}}">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.54754 1.09754C9.97325 -0.0325142 11.9979 -0.0325142 13.4236 1.09754L13.4253 1.09893L19.9999 6.34209C20.7749 6.9372 21.2365 7.85151 21.2499 8.82755L21.2501 8.83784L21.25 17.5618C21.25 19.882 19.3522 21.75 17.0271 21.75H15.103L15.0856 21.7498C14.0164 21.725 13.1503 20.8575 13.1505 19.7809V16.7047C13.1505 16.3422 12.8517 16.0356 12.4671 16.0356H9.58398C9.20227 16.0392 8.90721 16.3446 8.90722 16.7047V19.7714C8.90722 19.8452 8.89655 19.9166 8.87666 19.984C8.76973 20.9823 7.91806 21.75 6.89696 21.75H4.97291C2.64779 21.75 0.75 19.882 0.75 17.5618V8.83995C0.759661 7.86207 1.22214 6.94535 2.00022 6.35152L8.54754 1.09754Z"
                                    stroke="#fff" />
                            </svg>

                            <br>
                            <small>{{translate('Home')}}</small>
                        </a>

                    </li>
                    <li class="{{Request::is('sellers') ? 'activenav' : ''}}">
                        <a href="{{ route('sellers') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M21.9826 8.31182C21.9818 8.08382 21.9527 7.85997 21.8989 7.63861C21.6054 6.44638 21.2862 5.26079 20.9935 4.06857C20.9205 3.77176 20.8509 3.47494 20.7688 3.18062C20.6071 2.59694 20.0807 1.99835 19.2358 2C14.4006 2.00581 9.56539 2.00249 4.73017 2.00415C4.53617 2.00415 4.34382 2.02156 4.15976 2.10613C3.62915 2.34988 3.32322 2.76442 3.18476 3.32239C2.93852 4.31895 2.68897 5.31385 2.43776 6.30958C2.27443 6.95627 2.06633 7.59301 2 8.26125C2 8.33918 2 8.41712 2 8.49505C2 8.57298 2 8.65092 2 8.72885C2 8.87145 2 9.01488 2 9.15749C2.0398 9.21635 2.01907 9.28434 2.02487 9.34735C2.08705 9.98326 2.39464 10.5197 2.72545 11.0412C2.86142 11.2551 2.92111 11.4648 2.92028 11.7152C2.91531 14.5722 2.9236 17.4293 2.91282 20.2871C2.91033 21.0175 3.40696 21.9154 4.30402 21.9859C4.34299 21.9859 4.38196 21.9859 4.42093 21.9859C9.46093 21.9859 14.5009 21.9859 19.5401 21.9859C19.5658 21.9859 19.5923 21.9859 19.618 21.9859C19.8212 21.9519 20.0293 21.9494 20.2158 21.8383C20.8094 21.4851 21.0781 20.9504 21.0797 20.2797C21.0855 18.415 21.0822 13.2333 21.0822 11.3687C21.0822 11.3512 21.0814 11.333 21.0805 11.3156C21.1394 11.2484 21.1966 11.1788 21.2513 11.1067C21.6709 10.5528 21.9445 9.93683 22 9.23542C22 9.01406 22 8.79352 22 8.57215C21.9627 8.48759 21.9925 8.39805 21.9826 8.31182ZM15.9369 3.38375C16.984 3.39038 18.032 3.38872 19.0791 3.3854C19.2342 3.38457 19.3444 3.42188 19.3859 3.58687C19.7805 5.16379 20.1976 6.73573 20.564 8.31928C20.8625 9.61017 19.8991 10.9632 18.5809 11.1788C17.2386 11.3977 15.9518 10.4475 15.7868 9.11355C15.7686 8.96597 15.7587 8.81591 15.7587 8.6675C15.7562 7.84919 15.757 7.03089 15.757 6.21258C15.757 5.32961 15.7611 4.44663 15.7528 3.56366C15.752 3.41276 15.7943 3.38292 15.9369 3.38375ZM9.87547 3.38375C11.3206 3.38872 12.7657 3.38872 14.2108 3.38375C14.3525 3.38292 14.3956 3.41193 14.394 3.562C14.3857 4.45824 14.3898 5.35531 14.3898 6.25155C14.389 6.25155 14.3882 6.25155 14.3873 6.25155C14.3873 7.13535 14.3998 8.01915 14.384 8.90213C14.365 9.94678 13.5856 10.906 12.5916 11.144C11.3305 11.4458 10.0827 10.6722 9.77515 9.39958C9.72126 9.17821 9.6939 8.95353 9.6939 8.72554C9.69556 7.00353 9.69722 5.28235 9.69058 3.56034C9.68976 3.40696 9.73618 3.38292 9.87547 3.38375ZM3.40115 9.06297C3.33897 8.52821 3.48821 8.03408 3.6134 7.53248C3.9326 6.25072 4.25843 4.96978 4.58094 3.68885C4.65058 3.41359 4.68292 3.38706 4.96481 3.38706C6.01277 3.38706 7.0599 3.39038 8.10703 3.38375C8.25129 3.38292 8.29109 3.41608 8.28943 3.56366C8.28197 4.45326 8.28529 5.34287 8.28529 6.23248C8.28529 7.05742 8.28695 7.88153 8.28446 8.70647C8.28031 10.0297 7.36749 11.0478 6.05505 11.1979C4.79484 11.343 3.5479 10.329 3.40115 9.06297ZM10.3306 18.2666C10.3306 17.552 10.334 16.8381 10.3282 16.1235C10.3265 15.9809 10.3489 15.922 10.5147 15.9236C11.5038 15.9328 12.4929 15.9319 13.482 15.9245C13.6412 15.9228 13.6735 15.9717 13.6727 16.1201C13.6669 17.5619 13.6677 19.0037 13.6718 20.4455C13.6718 20.5756 13.6486 20.6229 13.5044 20.6221C12.502 20.6154 11.5005 20.6154 10.4981 20.6221C10.3597 20.6229 10.3265 20.5839 10.3273 20.4488C10.334 19.7217 10.3306 18.9937 10.3306 18.2666ZM19.5617 20.0832C19.5617 20.5383 19.5376 20.5624 19.0941 20.5624C17.8894 20.5624 16.6847 20.5624 15.4809 20.5624C15.4162 20.5624 15.3507 20.5632 15.2861 20.5591C15.2007 20.5533 15.1567 20.5101 15.1509 20.4247C15.1468 20.3534 15.1468 20.2821 15.1468 20.21C15.1468 18.6182 15.1468 17.0263 15.1468 15.4337C15.1468 15.277 15.1393 15.1219 15.0854 14.9727C14.9768 14.6692 14.7289 14.4819 14.4064 14.481C12.8046 14.4777 11.2028 14.4802 9.60104 14.4794C9.30009 14.4794 9.09862 14.6294 8.95767 14.879C8.86813 15.0374 8.8557 15.2115 8.85653 15.3889C8.85736 17.0006 8.85736 18.6115 8.85736 20.2233C8.85736 20.5624 8.85736 20.5624 8.50914 20.5624C7.71488 20.5624 6.92061 20.5624 6.12635 20.5624C5.67036 20.5624 5.21519 20.5649 4.75919 20.5615C4.50964 20.5599 4.45243 20.4994 4.44248 20.249C4.44082 20.21 4.44165 20.171 4.44165 20.1321C4.44165 17.6498 4.44165 15.1675 4.44165 12.6852C4.44165 12.6272 4.43917 12.5683 4.44663 12.5103C4.45575 12.4456 4.49306 12.4216 4.55607 12.4332C4.6141 12.444 4.66799 12.4672 4.72437 12.4838C5.69772 12.7723 6.64287 12.6968 7.55569 12.2574C8.05978 12.0145 8.50168 11.6854 8.84658 11.2393C8.92949 11.1324 8.99084 11.1108 9.08038 11.236C9.19396 11.3952 9.34403 11.5229 9.48414 11.6588C10.5263 12.672 12.2864 12.9472 13.5939 12.3088C14.1096 12.0576 14.5714 11.7417 14.9312 11.2882C15.0664 11.1183 15.0962 11.1232 15.2396 11.2949C15.3234 11.3952 15.408 11.493 15.5025 11.5826C16.6466 12.6595 18.2235 12.9108 19.5608 12.3901C19.5625 14.6867 19.5617 18.5444 19.5617 20.0832Z"
                                    fill="#fff" />
                            </svg>
                            <br>
                            <small>{{translate('Vendor')}}</small>
                        </a>
                    </li>
                    <li class="{{Request::is('user-account') ? 'activenav' : ''}}">
                        @if(auth('customer')->check())
                        <a href="{{route('user-account')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.71092 15.2207C8.14088 14.7413 10.0352 14.5967 11.9849 14.5967C13.9464 14.5967 15.8414 14.7469 17.2694 15.2326C17.9874 15.4768 18.642 15.8236 19.1237 16.3284C19.6226 16.8514 19.9045 17.5127 19.9045 18.2943C19.9045 19.0775 19.6201 19.7385 19.1183 20.2595C18.6342 20.762 17.9772 21.1055 17.2586 21.3466C15.8288 21.8261 13.9347 21.971 11.9849 21.971C10.0235 21.971 8.12834 21.821 6.70008 21.3355C5.98201 21.0913 5.3272 20.7446 4.8454 20.2397C4.34631 19.7166 4.06445 19.0552 4.06445 18.2733C4.06445 17.4899 4.34885 16.8288 4.85082 16.3077C5.33499 15.8051 5.99214 15.4617 6.71092 15.2207ZM5.93109 17.3484C5.69291 17.5956 5.56445 17.8859 5.56445 18.2733C5.56445 18.6624 5.69283 18.9549 5.93062 19.2041C6.18569 19.4715 6.5944 19.7152 7.18287 19.9153C8.3677 20.3181 10.0578 20.471 11.9849 20.471C13.9028 20.471 15.5934 20.323 16.7815 19.9244C17.3718 19.7264 17.782 19.4846 18.0379 19.2189C18.2761 18.9716 18.4045 18.6815 18.4045 18.2943C18.4045 17.9055 18.2761 17.6131 18.0384 17.3639C17.7834 17.0966 17.3748 16.8528 16.7864 16.6527C15.6018 16.2498 13.912 16.0967 11.9849 16.0967C10.067 16.0967 8.37612 16.2445 7.18775 16.6429C6.59736 16.8408 6.18707 17.0827 5.93109 17.3484Z"
                                    fill="#fff" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.67434 7.4089C6.675 4.47668 9.05286 2.10059 11.9848 2.10059C14.9174 2.10059 17.2944 4.47753 17.2944 7.41011C17.2944 10.3425 14.9176 12.7206 11.9848 12.7206H11.9525L11.95 12.7206C9.02721 12.7107 6.66512 10.3322 6.67434 7.4089ZM6.67434 7.4089C6.67434 7.4085 6.67435 7.4081 6.67435 7.4077L7.38863 7.41011H6.67434C6.67434 7.40971 6.67434 7.40931 6.67434 7.4089ZM11.9848 3.52916C9.84104 3.52916 8.10291 5.2667 8.10291 7.41011V7.41252H8.10291C8.0957 9.54738 9.82039 11.2842 11.9538 11.292H11.9848C14.1283 11.292 15.8658 9.55391 15.8658 7.41011C15.8658 5.2665 14.1285 3.52916 11.9848 3.52916Z"
                                    fill="#fff" />
                            </svg>
                            <br>
                            <small>{{translate('Account')}}</small>
                        </a>
                        @else
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#auth-model">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.71092 15.2207C8.14088 14.7413 10.0352 14.5967 11.9849 14.5967C13.9464 14.5967 15.8414 14.7469 17.2694 15.2326C17.9874 15.4768 18.642 15.8236 19.1237 16.3284C19.6226 16.8514 19.9045 17.5127 19.9045 18.2943C19.9045 19.0775 19.6201 19.7385 19.1183 20.2595C18.6342 20.762 17.9772 21.1055 17.2586 21.3466C15.8288 21.8261 13.9347 21.971 11.9849 21.971C10.0235 21.971 8.12834 21.821 6.70008 21.3355C5.98201 21.0913 5.3272 20.7446 4.8454 20.2397C4.34631 19.7166 4.06445 19.0552 4.06445 18.2733C4.06445 17.4899 4.34885 16.8288 4.85082 16.3077C5.33499 15.8051 5.99214 15.4617 6.71092 15.2207ZM5.93109 17.3484C5.69291 17.5956 5.56445 17.8859 5.56445 18.2733C5.56445 18.6624 5.69283 18.9549 5.93062 19.2041C6.18569 19.4715 6.5944 19.7152 7.18287 19.9153C8.3677 20.3181 10.0578 20.471 11.9849 20.471C13.9028 20.471 15.5934 20.323 16.7815 19.9244C17.3718 19.7264 17.782 19.4846 18.0379 19.2189C18.2761 18.9716 18.4045 18.6815 18.4045 18.2943C18.4045 17.9055 18.2761 17.6131 18.0384 17.3639C17.7834 17.0966 17.3748 16.8528 16.7864 16.6527C15.6018 16.2498 13.912 16.0967 11.9849 16.0967C10.067 16.0967 8.37612 16.2445 7.18775 16.6429C6.59736 16.8408 6.18707 17.0827 5.93109 17.3484Z"
                                    fill="#fff" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.67434 7.4089C6.675 4.47668 9.05286 2.10059 11.9848 2.10059C14.9174 2.10059 17.2944 4.47753 17.2944 7.41011C17.2944 10.3425 14.9176 12.7206 11.9848 12.7206H11.9525L11.95 12.7206C9.02721 12.7107 6.66512 10.3322 6.67434 7.4089ZM6.67434 7.4089C6.67434 7.4085 6.67435 7.4081 6.67435 7.4077L7.38863 7.41011H6.67434C6.67434 7.40971 6.67434 7.40931 6.67434 7.4089ZM11.9848 3.52916C9.84104 3.52916 8.10291 5.2667 8.10291 7.41011V7.41252H8.10291C8.0957 9.54738 9.82039 11.2842 11.9538 11.292H11.9848C14.1283 11.292 15.8658 9.55391 15.8658 7.41011C15.8658 5.2665 14.1285 3.52916 11.9848 3.52916Z"
                                    fill="#fff" />
                            </svg>
                            <br>
                            <small>{{translate('Account')}}</small>
                        </a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<a id="back-to-button" class="back-to-top"> <i class="fa-solid fa-angle-up"></i> </a>
<!-- Chatbot -->
<div class="botIcon" style="display: none;">
    <div class="botIconContainer">
        <div class="iconInner">
            <i class="fa fa-commenting" aria-hidden="true"></i>
        </div>
    </div>
    <div class="Layout Layout-open Layout-expand Layout-right">
        <div class="Messenger_messenger">
            <div class="Messenger_header">
                <h4 class="Messenger_prompt">How can we help you?</h4> <span class="chat_close_icon"><i class="fa fa-window-close" aria-hidden="true"></i></span>
            </div>
            <div class="Messenger_content">
                <div class="Messages">
                    <div class="Messages_list"></div>
                </div>
                <form id="messenger">
                    <div class="Input Input-blank">
                        <!-- <textarea name="msg" class="Input_field" placeholder="Send a message..."></textarea> -->
                        <input name="msg" class="Input_field" placeholder="Send a message...">
                        <button type="submit" class="Input_button Input_button-send">
                            <div class="Icon">
                                <svg viewBox="1496 193 57 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g id="Group-9-Copy-3" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(1523.000000, 220.000000) rotate(-270.000000) translate(-1523.000000, -220.000000) translate(1499.000000, 193.000000)">
                                        <path d="M5.42994667,44.5306122 L16.5955554,44.5306122 L21.049938,20.423658 C21.6518463,17.1661523 26.3121212,17.1441362 26.9447801,20.3958097 L31.6405465,44.5306122 L42.5313185,44.5306122 L23.9806326,7.0871633 L5.42994667,44.5306122 Z M22.0420732,48.0757124 C21.779222,49.4982538 20.5386331,50.5306122 19.0920112,50.5306122 L1.59009899,50.5306122 C-1.20169244,50.5306122 -2.87079654,47.7697069 -1.64625638,45.2980459 L20.8461928,-0.101616237 C22.1967178,-2.8275701 25.7710778,-2.81438868 27.1150723,-0.101616237 L49.6075215,45.2980459 C5.08414042,47.7885641 49.1422456,50.5306122 46.3613062,50.5306122 L29.1679835,50.5306122 C27.7320366,50.5306122 26.4974445,49.5130766 26.2232033,48.1035608 L24.0760553,37.0678766 L22.0420732,48.0757124 Z" id="sendicon" fill="#96AAB4" fill-rule="nonzero"></path>
                                    </g>
                                </svg>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="footerMain">
    <div class="container-fluid productcontent">
        <div class="row footstyle">
            <div class="col-md-3 footerLogoDiv">
                <img class="footer-logo" src="{{ theme_asset('assets/images/primeLogo.png')}}" alt="logo">
                <small>Bringing the market to you.</small>
            </div>
            <div class="col-2 col-md d-none">
                <h5>{{translate('ABOUT')}}</h5>
                <ul class="list-unstyled text-small">
                    <li class=""><a href="contact_us.php">{{translate('Contact_Us')}}</a></li>
                    <li class=""><a href="about-us.php">{{translate('About_Us')}}</a></li>
                    <li class=""><a class=" " href="#">{{translate('Careers')}}</a></li>
                </ul>
            </div>
            <div class="col-md-3 footerHead">
                <h5>Our Links</h5>
                <ul class="list-unstyled text-small">
                    <li><a href="{{route('about-us')}}">{{translate('About_Company')}}</a></li>
                        <li class=""><a class=" " href="{{route('contacts')}}"> {{translate('contacts')}}</a></li>
                          <li class=""><a class=" " href="{{route('helpTopic')}}"> {{translate('FAQ')}}</a></li>
                </ul>
            </div>
            <div class="col-md-2 footerHead">
                <h5>Our Policy</h5>
                <ul class="list-unstyled text-small">
                    <li><a href="{{route('privacy-policy')}}">{{translate('Privacy_Policy')}}</a></li>
                    <li><a href="{{route('terms')}}">{{translate('Terms_&_Conditions')}}</a></li>

                    @if(isset($web_config['refund_policy']['status']) && $web_config['refund_policy']['status'] == 1)
                    <li>
                        <a href="{{route('refund-policy')}}">{{translate('refund_policy')}}</a>
                    </li>
                    @endif

                    @if(isset($web_config['return_policy']['status']) && $web_config['return_policy']['status'] == 1)
                    <li>
                        <a href="{{route('return-policy')}}">{{translate('return_policy')}}</a>
                    </li>
                    @endif

                    @if(isset($web_config['cancellation_policy']['status']) && $web_config['cancellation_policy']['status'] == 1)
                    <li>
                        <a href="{{route('cancellation-policy')}}">{{translate('cancellation_policy')}}</a>
                    </li>
                    @endif

                    <!-- <li>
                        @if(auth('customer')->check())
                        <a href="{{route('account-tickets')}}">{{translate('Customer_Support')}}</a>
                        @else
                        <a class="bg-transparent border-0 p-0 " data-bs-toggle="modal" data-bs-target="#loginModal">{{translate('Customer_Support')}}</a>
                        @endif
                    </li> -->
                </ul>
            </div>
            <!-- <div class="col-md-2 footerHead d-none">
                <h5>{{translate('Let_Us_Help_You')}}</h5>
                <ul class="list-unstyled text-small">
                    <li class=""><a class=" "
                            href="{{route('become-seller')}}">{{translate('Become_a_Seller')}} </a></li>
                            <li class=""><a class=" "
                            href="#">{{translate('Become_a_Distributer')}} </a></li>
                    <li class=""><a class=" " href="{{route('user-account')}}"> {{translate('Your_Account')}}</a></li> 
                    <li class=""><a class=" " href="{{route('contacts')}}"> {{translate('contacts')}}</a></li> 
                    <li class=""><a class=" " href="{{route('helpTopic')}}"> {{translate('FAQ')}}</a></li> 
                </ul>
            </div> -->
            <div class="col-md-2 footerHead text-center">
                <h5 class="download-content"> {{translate('Connect with us')}}
                </h5>
                <ul class="icon-font-footer" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
                <li><a href="https://www.youtube.com/" target="_blank_"><i class="fa-brands fa-youtube"></i> </a></li>
                <li><a href="https://www.facebook.com/" target="_blank_"><i class="fa-brands fa-facebook"></i></a></li>
                <li><a href="https://in.linkedin.com/" target="_blank_"><i class="fa-brands fa-linkedin-in"></i></a></li>
                <li><a href="https://in.linkedin.com/" target="_blank_"><i class="fa-brands fa-instagram"></i></a></li>
            </ul>
            </div>
        </div>
    </div>
    <div class="container mt-3">
        <div class="div-hr-line"></div>
        <div class="d-flex justify-content-center align-items-center mt-3">
            <div class="copy-right">
                <p>{{translate('©_2025_copyright_by_Prime_Basket._all_rights_reserved.')}}</p>
            </div>
           
        </div>
    </div>

    <div class="footer-bottom d-none">
        <div class="container">
            <!-- Copyright -->
            <div class="copyright">
                <div class="row">
                    <div class="col-md-8 col-lg-8">
                        <div class="copyright-text">
                            <p class="mb-0">{{ $web_config['copyright_text']->value }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <!-- Copyright Menu -->
                        <div class="copyright-menu">
                            <ul class="policy-menu">
                                <li><a href="#">{{translate('Privacy')}}</a></li>
                                <li><a href="#"> {{translate('Terms_&_Condition')}}</a></li>
                                <li><a href="#">{{translate('Faq"s')}}</a></li>
                            </ul>
                        </div>
                        <!-- /Copyright Menu -->
                    </div>
                </div>
            </div>
            <!-- /Copyright -->
        </div>
    </div>
</footer>

<!-- Button trigger modal -->



<!-- <script src="{{ theme_asset('assets/js/bootstrap.bundle.min.js') }}"></script> -->
<script src="{{ theme_asset('assets/js/aos.js') }}"></script>
<script src="{{ theme_asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ theme_asset('assets/js/owl.carousel.min.js') }}"></script>



<script>
    AOS.init({
        duration: 800, // Animation duration in milliseconds
        once: true, // Only animate elements once
        disable: 'mobile', // Disable animations on mobile devices

    });
</script>
<script>
    jQuery("#carousel-bg-special").owlCarousel({
        autoplay: true,
        rewind: true,
        /* use rewind if you don't want loop */
        margin: 20,
        /*
  animateOut: 'fadeOut',
  animateIn: 'fadeIn',
  */
        navText: [
            '    <i class="fas fa-angle-left"></i>',
            '    <i class="fas fa-angle-right"></i>'
        ],
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: true,
        responsive: {
            0: {
                items: 2
            },

            600: {
                items: 2
            },

            1024: {
                items: 3
            },

            1366: {
                items: 4
            }
        }
    });
</script>
<script>
    $('.owl-carousel.navbar-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 4
            },
            1000: {
                items: 8
            }
        }
    })

    $('.cart-item-carousel').owlCarousel({
        loop: false,
        margin: 30,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 4
            },
            1000: {
                items: 5
            }
        }
    })

    $(document).ready(function() {
        // Initialize Owl Carousel
        var owl = $('.owl-carousel.cart-item-carousel');
        if (owl.length > 0) {
            owl.owlCarousel({
                loop: true,
                margin: 20,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 3000,
                responsive: {
                    0: {
                        items: 2
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 4
                    }
                }
            });
        }

        // Custom previous button click event
        $('#customPrevButton').on('click', function() {
            owl.trigger('prev.owl.carousel');
        });

        // Custom next button click event
        $('#customNextButton').on('click', function() {
            owl.trigger('next.owl.carousel');
        });
    });
</script>
<script>
    jQuery("#cart-item-slider").owlCarousel({
        autoplay: true,
        rewind: true,
        /* use rewind if you don't want loop */
        margin: 20,
        /*
  animateOut: 'fadeOut',
  animateIn: 'fadeIn',
  */
        navText: [
            '    <i class="fas fa-angle-left"></i>',
            '    <i class="fas fa-angle-right"></i>'
        ],
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: true,
        responsive: {
            0: {
                items: 2
            },

            600: {
                items: 3
            },

            1024: {
                items: 4
            },

            1366: {
                items: 4
            }
        }
    });
</script>
<script>
    $('.owl-carousel.navbar-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 4
            },
            1000: {
                items: 8
            }
        }
    })
</script>


<script>
    
    $('.owl-carousel.category-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplaySpeed: 2000, 
        responsive: {
            0: {
                items: 4
            },
            600: {
                items: 6
            },
            1000: {
                items: 10
            }
        }
    })
</script>
<script>
    $('.owl-carousel.banner-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplaySpeed: 2000, 
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })
</script>
<script>
    $('.owl-carousel.banner-carousel-mobile').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        autoplay: true, // Enable autoplay
        autoplayTimeout: 5000, // Set autoplay timeout in milliseconds
        autoplaySpeed: 2000, // Set autoplay speed in milliseconds
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })
</script>
<script>
    $('.owl-carousel.brand-carousel').owlCarousel({
        loop: true,
        // rewind:true,
        margin: 10,
        nav: false,
        dots: false,
        autoplay: true, // Enable autoplay
        autoplayTimeout: 3000, // Set autoplay timeout in milliseconds
        responsive: {
            0: {
                items: 5
            },
            600: {
                items: 5
            },
            1000: {
                items: 8
            }
        }


    })
</script>

<script>
    $('.owl-carousel.books-slider-carousel').owlCarousel({
        loop: true,
        autoplay: true,
        margin: 30,
        nav: false,
        dots: false,
        autoplay: true, // Enable autoplay
        autoplayTimeout: 3000, // Set autoplay timeout in milliseconds
        stopOnHover: true, // Stop autoplay on hover
        responsive: {
            0: {
                items: 3
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    })
</script>


<script>
    // Function to show a specific tab and its content
    function showTab(tabId, button) {
        // Hide all tabs and contents
        document.querySelectorAll('.tab, .content').forEach(function(el) {
            el.style.display = 'none';
        });

        // Remove the "active" class from all buttons
        document.querySelectorAll('.tab-button').forEach(function(el) {
            el.classList.remove('activetabbg');
        });

        // Show the selected tab and content
        document.getElementById(tabId).style.display = 'block';
        document.getElementById('content' + tabId.slice(-1)).style.display = 'block';

        // Add the "active" class to the clicked button
        button.classList.add('activetabbg');
    }
</script>



<script>
    $('.owl-carousel.navbar-slider').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 4
            },
            1000: {
                items: 8
            }
        }
    })

    const bodyEl = document.querySelector('body');
    const btnEl = document.querySelector('.btn-direction');
    btnEl.addEventListener('click', () => {
        const dir = bodyEl.getAttribute('dir') === 'ltr' ? 'rtl' : 'ltr';
        bodyEl.setAttribute('dir', dir);
    }, false);
</script>



<script>
    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        input = $(this).parent().find("input");
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>



<script>
    jQuery(document).ready(function() {

        var $this = $('#for-show-more');
        if ($this.find('div').length > 2) {
            $('#for-show-more').append('<div><a href="javascript:;" class="showMore"></a></div>');
        }

        // If more than 2 Education list-group, hide the remaining
        $('#for-show-more #for-show-more-item').slice(0, 6).addClass('shown');
        $('#for-show-more #for-show-more-item').not('.shown').hide();
        $('#for-show-more .showMore').on('click', function() {
            $('#for-show-more #for-show-more-item').not('.shown').toggle(300);
            $(this).toggleClass('showLess');
        });

    });
</script>





<!-- <script>
$(document).ready(function() {
    $(".dropdown").hover(
        function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideDown("300");
            $(this).toggleClass('open');
        },
        function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideUp("300");
            $(this).toggleClass('open');
        }
    );
});
</script> -->




<script>
    function toggleNavs(navId, width) {
        document.getElementById(navId).style.width = width;
    }
</script>







<script>
    $('.search-toggle').addClass('closed');

    $('.search-toggle .search-icon').click(function(e) {
        if ($('.search-toggle').hasClass('closed')) {
            $('.search-toggle').removeClass('closed').addClass('opened');
            $('.search-toggle, .search-container').addClass('opened');
            $('#search-terms').focus();
        } else {
            $('.search-toggle').removeClass('opened').addClass('closed');
            $('.search-toggle, .search-container').removeClass('opened');
        }
    });
</script>



<!-- <script>
$(document).ready(function() {
    $(".dropdown").hover(
        function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideDown("400");
            $(this).toggleClass('open');
        },
        function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideUp("400");
            $(this).toggleClass('open');
        }
    );
});
</script> -->

<script>
    const header = document.querySelector(".page-header");
    const toggleClass = "is-sticky";

    window.addEventListener("scroll", () => {
        const currentScroll = window.pageYOffset;
        if (currentScroll > 150) {
            header.classList.add(toggleClass);
        } else {
            header.classList.remove(toggleClass);
        }
    });
</script>

<script>
    var btn = $('#back-to-button');
    $(window).scroll(function() {
        if ($(window).scrollTop() > 100) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });

    btn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, '100');
    });
</script>

<script>
    var myVar;

    function myFunction() {
        myVar = setTimeout(showPage, 3000);
    }

    function showPage() {
        document.getElementById("loader").style.display = "none";
        document.getElementById("myDiv").style.display = "block";
    }
</script>


<script>
    $('.owl-carousel.deal-primecardprimer').owlCarousel({
        loop: false,
        margin: 30,
        autoplay: true,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 3
            },
            1000: {
                items: 3
            }
        }
    })
</script>

<script>
    // Get the current page's filename
    var path = window.location.pathname;
    var page = path.split("/").pop();

    // Get all the links in the navigation menu
    var navLinks = document.querySelectorAll('.list-group-item a');

    // Loop through each link and check if its href matches the current page
    for (var i = 0; i < navLinks.length; i++) {
        if (navLinks[i].getAttribute('href') === page) {
            // If there's a match, add the 'active' class to the link
            navLinks[i].classList.add('.bg-light-green-actived ');
        }
    }
</script>


<script>
    $('.owl-carousel.our-partner-carousel').owlCarousel({
        loop: true,
        margin: 30,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    })
</script>
<script type="text/javascript">
    jQuery("#carousel-featured_products").owlCarousel({
        autoplay: true,
        rewind: true,
        /* use rewind if you don't want loop */
        margin: 20,
        /*
  animateOut: 'fadeOut',
  animateIn: 'fadeIn',
  */
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: false,
        responsive: {
            0: {
                items: 1
            },

            600: {
                items: 3
            },

            1024: {
                items: 4
            },

            1366: {
                items: 4
            }
        }
    });
</script>
<script>
    function sharePage() {
        // You can replace 'YourPageURL' with the actual URL of your page
        const pageURL = window.location.href;

        // Use the Web Share API if supported by the browser
        if (navigator.share) {
            navigator.share({
                    title: 'Your Page Title',
                    text: 'Check out this awesome page!',
                    url: pageURL
                })
                .then(() => console.log('Shared successfully'))
                .catch((error) => console.error('Error sharing:', error));
        } else {
            // Fallback for browsers that do not support Web Share API
            alert(`Share this page:\n${pageURL}`);
        }
    }
</script>
<script>
    var otp = "";

    function moveToNextField(input) {
        var maxLength = parseInt(input.getAttribute("maxlength"));
        var currentLength = input.value.length;
        otp += `${input.value}`
        if (currentLength === maxLength) {

            var nextInput = input.nextElementSibling;

            // Move to the next input field
            if (nextInput) {
                nextInput.focus();
            } else {
                $('input[name=otp]').val(otp)

                otp = ""

                $('.btn-login').removeAttr('disabled')

                // If there is no next input field, you can submit the form or perform other actions
                document.getElementById("otpForm").submit();
            }
        }
    }
</script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        jQuery(document).on('click', '.iconInner', function(e) {
            jQuery(this).parents('.botIcon').addClass('showBotSubject');
            $("[name='msg']").focus();
        });

        jQuery(document).on('click', '.closeBtn, .chat_close_icon', function(e) {
            jQuery(this).parents('.botIcon').removeClass('showBotSubject');
            jQuery(this).parents('.botIcon').removeClass('showMessenger');
        });

        jQuery(document).on('submit', '#botSubject', function(e) {
            e.preventDefault();

            jQuery(this).parents('.botIcon').removeClass('showBotSubject');
            jQuery(this).parents('.botIcon').addClass('showMessenger');
        });

        /* Chatboat Code */
        $(document).on("submit", "#messenger", function(e) {
            e.preventDefault();

            var val = $("[name=msg]").val().toLowerCase();
            var mainval = $("[name=msg]").val();
            name = '';
            nowtime = new Date();
            nowhoue = nowtime.getHours();

            function userMsg(msg) {
                $('.Messages_list').append('<div class="msg user"><span class="avtr"><figure style="background-image: url(https://mrseankumar25.github.io/Sandeep-Kumar-Frontend-Developer-UI-Specialist/images/avatar.png)"></figure></span><span class="responsText">' + mainval + '</span></div>');
            };

            function appendMsg(msg) {
                $('.Messages_list').append('<div class="msg"><span class="avtr"><figure style="background-image: url(https://mrseankumar25.github.io/Sandeep-Kumar-Frontend-Developer-UI-Specialist/images/avatar.png)"></figure></span><span class="responsText">' + msg + '</span></div>');
                $("[name='msg']").val("");
            };


            userMsg(mainval);
            if (val.indexOf("hello") > -1 || val.indexOf("hi") > -1 || val.indexOf("good morning") > -1 || val.indexOf("good afternoon") > -1 || val.indexOf("good evening") > -1 || val.indexOf("good night") > -1) {
                if (nowhoue >= 12 && nowhoue <= 16) {
                    appendMsg('good afternoon');
                } else if (nowhoue >= 10 && nowhoue <= 12) {
                    appendMsg('hi');
                } else if (nowhoue >= 0 && nowhoue <= 10) {
                    appendMsg('good morning');
                } else {
                    appendMsg('good evening');
                }

                appendMsg("what's you name?");

            } else if (val.indexOf("i have problem with") > -1) {
                if (val.indexOf("girlfriend") > -1 || val.indexOf("gf") > -1) {

                    appendMsg("take out your girlfriend, for dinner or movie.");
                    appendMsg("is it helpful? (yes/no)");

                } else if (val.indexOf("boyfriend") > -1 || val.indexOf("bf") > -1) {
                    appendMsg("bye something for him.");
                    appendMsg("is it helpful? (yes/no)");
                } else {
                    appendMsg("sorry, i'm not able to get you point, please ask something else.");
                }
            } else if (val.indexOf("yes") > -1) {

                var nowtime = new Date();
                var nowhoue = nowtime.getHours();
                appendMsg("it's my pleaser that i can help you");

                saybye();
            } else if (val.indexOf("no") > -1) {

                var nowtime = new Date();
                var nowhoue = nowtime.getHours();
                appendMsg("it's my bad that i can't able to help you. please try letter");

                saybye();
            } else if (val.indexOf("my name is ") > -1 || val.indexOf("i am ") > -1 || val.indexOf("i'm ") > -1 || val.split(" ").length < 2) {
                /*|| mainval != ""*/
                // var name = "";
                if (val.indexOf("my name is") > -1) {
                    name = val.replace("my name is", "");
                } else if (val.indexOf("i am") > -1) {
                    name = val.replace("i am", "");
                } else if (val.indexOf("i'm") > -1) {
                    name = val.replace("i'm", "");
                } else {
                    name = mainval;
                }

                // appendMsg("hi " + name + ", how can i help you?");
                appendMsg("hi " + name + ", how can i help you?");
            } else {
                appendMsg("sorry i'm not able to understand what do you want to say");
            }

            function saybye() {
                if (nowhoue <= 10) {
                    appendMsg(" have nice day! :)");
                } else if (nowhoue >= 11 || nowhoue <= 20) {
                    appendMsg(" bye!");
                } else {
                    appendMsg(" good night!");
                }
            }

            var lastMsg = $('.Messages_list').find('.msg').last().offset().top;
            $('.Messages').animate({
                scrollTop: lastMsg
            }, 'slow');
        });
        /* Chatboat Code */
    })
</script>