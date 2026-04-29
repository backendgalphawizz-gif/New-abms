@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
<link rel="stylesheet" href="https://unpkg.com/@adminkit/core@latest/dist/css/app.css">
<script src="https://unpkg.com/@adminkit/core@latest/dist/js/app.js"></script>

@section('content')
<div class="content container-fluid">
    <!-- Page Heading -->
    <div class="page-header pb-0 border-0 mb-3">
        <div class="d-flex justify-content-between  align-items-center mx-1">
            <div>
                <h1 class="page-header-title">{{\App\CPU\translate('Seller Dashboard')}}</h1>
                <div>{{ \App\CPU\translate('Welcome_message')}}.</div>
            </div>

            <div>
                <a class="btn btn--primary" href="{{route('seller.product.list')}}">
                 {{\App\CPU\translate('Products')}}</a>
            </div>
        </div>
    </div>




    <section class="mb-3">
        <div class="container-fluid px-0">
            <div class="row">

                <!-- TOTAL SALE START -->
                <div class="col-md-3">
                    <div class="card-for-dashboard carddivseller">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <g opacity="0.15">
                                <rect width="48" height="48" rx="8" fill="#E17A1A" />
                                <rect x="0.5" y="0.5" width="47" height="47" rx="7.5" stroke="white"
                                    stroke-opacity="0.07" />
                            </g>
                            <path
                                d="M16.3407 39C15.9401 38.8881 15.5288 38.8061 15.1409 38.6601C13.0791 37.8801 11.8197 35.7949 12.021 33.6084C12.216 31.4805 12.3641 29.3474 12.5346 27.2174C12.7019 25.1257 12.8703 23.0341 13.0376 20.9424C13.142 19.6382 13.2539 18.3351 13.3455 17.0309C13.435 15.7522 14.296 14.9222 15.5735 14.9169C16.4025 14.9126 17.2304 14.9158 18.0882 14.9158C18.1287 12.9307 18.8916 11.31 20.4856 10.123C21.6087 9.28766 22.8852 8.92858 24.281 9.01169C27.0429 9.17791 29.6875 11.4635 29.7206 14.9147C29.9891 14.9147 30.2587 14.9147 30.5282 14.9147C31.1047 14.9147 31.6801 14.9105 32.2565 14.9158C33.4968 14.9265 34.361 15.7661 34.4547 17C34.6519 19.5988 34.8692 22.1955 35.0759 24.7933C35.2422 26.8849 35.4041 28.9776 35.5671 31.0693C35.6428 32.0421 35.7589 33.0138 35.7834 33.9888C35.8463 36.4885 34.0893 38.5546 31.6194 38.9435C31.565 38.9521 31.5139 38.9808 31.4606 39C26.4196 39 21.3807 39 16.3407 39ZM27.9241 14.9019C28.0274 13.4944 27.2091 12.0112 25.8751 11.2813C24.4409 10.497 22.9971 10.5535 21.6385 11.4613C20.4355 12.2658 19.874 13.44 19.857 14.9009C22.5602 14.9019 25.2326 14.9019 27.9241 14.9019Z"
                                fill="#E17A1A" />
                            <path
                                d="M22.6926 27.824C23.9637 26.5528 25.2125 25.304 26.4613 24.0563C26.5508 23.9668 26.6414 23.8751 26.7448 23.8037C27.1007 23.5565 27.5716 23.6045 27.8678 23.9092C28.1694 24.2193 28.2035 24.6785 27.9446 25.0334C27.8764 25.1271 27.7922 25.2113 27.7101 25.2933C26.2962 26.7084 24.8822 28.1234 23.4672 29.5363C22.9195 30.084 22.4731 30.0861 21.9318 29.5448C21.2829 28.897 20.6339 28.2491 19.9882 27.5981C19.7389 27.3466 19.6185 27.0515 19.7282 26.6935C19.8316 26.3546 20.0607 26.1468 20.4027 26.0744C20.7384 26.003 21.0122 26.1351 21.2466 26.3706C21.7197 26.848 22.1949 27.3242 22.6926 27.824Z"
                                fill="#FBEBDD" />
                        </svg>

                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p> {{\App\CPU\translate('Total_Sale')}}</p>
                                <h2>{{ $data['total_sale'] }}</h2>
                            </div>
                            <div class="arrow__tab d-none">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-for-dashboard carddivseller">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <g opacity="0.15">
                                <rect width="48" height="48" rx="8" fill="#E17A1A" />
                                <rect x="0.5" y="0.5" width="47" height="47" rx="7.5" stroke="white"
                                    stroke-opacity="0.07" />
                            </g>
                            <path
                                d="M16.3407 39C15.9401 38.8881 15.5288 38.8061 15.1409 38.6601C13.0791 37.8801 11.8197 35.7949 12.021 33.6084C12.216 31.4805 12.3641 29.3474 12.5346 27.2174C12.7019 25.1257 12.8703 23.0341 13.0376 20.9424C13.142 19.6382 13.2539 18.3351 13.3455 17.0309C13.435 15.7522 14.296 14.9222 15.5735 14.9169C16.4025 14.9126 17.2304 14.9158 18.0882 14.9158C18.1287 12.9307 18.8916 11.31 20.4856 10.123C21.6087 9.28766 22.8852 8.92858 24.281 9.01169C27.0429 9.17791 29.6875 11.4635 29.7206 14.9147C29.9891 14.9147 30.2587 14.9147 30.5282 14.9147C31.1047 14.9147 31.6801 14.9105 32.2565 14.9158C33.4968 14.9265 34.361 15.7661 34.4547 17C34.6519 19.5988 34.8692 22.1955 35.0759 24.7933C35.2422 26.8849 35.4041 28.9776 35.5671 31.0693C35.6428 32.0421 35.7589 33.0138 35.7834 33.9888C35.8463 36.4885 34.0893 38.5546 31.6194 38.9435C31.565 38.9521 31.5139 38.9808 31.4606 39C26.4196 39 21.3807 39 16.3407 39ZM27.9241 14.9019C28.0274 13.4944 27.2091 12.0112 25.8751 11.2813C24.4409 10.497 22.9971 10.5535 21.6385 11.4613C20.4355 12.2658 19.874 13.44 19.857 14.9009C22.5602 14.9019 25.2326 14.9019 27.9241 14.9019Z"
                                fill="#E17A1A" />
                            <path
                                d="M22.6926 27.824C23.9637 26.5528 25.2125 25.304 26.4613 24.0563C26.5508 23.9668 26.6414 23.8751 26.7448 23.8037C27.1007 23.5565 27.5716 23.6045 27.8678 23.9092C28.1694 24.2193 28.2035 24.6785 27.9446 25.0334C27.8764 25.1271 27.7922 25.2113 27.7101 25.2933C26.2962 26.7084 24.8822 28.1234 23.4672 29.5363C22.9195 30.084 22.4731 30.0861 21.9318 29.5448C21.2829 28.897 20.6339 28.2491 19.9882 27.5981C19.7389 27.3466 19.6185 27.0515 19.7282 26.6935C19.8316 26.3546 20.0607 26.1468 20.4027 26.0744C20.7384 26.003 21.0122 26.1351 21.2466 26.3706C21.7197 26.848 22.1949 27.3242 22.6926 27.824Z"
                                fill="#FBEBDD" />
                        </svg>

                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p> {{\App\CPU\translate('Today_Sale')}}</p>
                               <h2>{{ $data['today_sale'] }}</h2>
                            </div>
                            <div class="arrow__tab d-none">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-for-dashboard carddivseller">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <g opacity="0.15">
                                <rect width="48" height="48" rx="8" fill="#E17A1A" />
                                <rect x="0.5" y="0.5" width="47" height="47" rx="7.5" stroke="white"
                                    stroke-opacity="0.07" />
                            </g>
                            <path
                                d="M16.3407 39C15.9401 38.8881 15.5288 38.8061 15.1409 38.6601C13.0791 37.8801 11.8197 35.7949 12.021 33.6084C12.216 31.4805 12.3641 29.3474 12.5346 27.2174C12.7019 25.1257 12.8703 23.0341 13.0376 20.9424C13.142 19.6382 13.2539 18.3351 13.3455 17.0309C13.435 15.7522 14.296 14.9222 15.5735 14.9169C16.4025 14.9126 17.2304 14.9158 18.0882 14.9158C18.1287 12.9307 18.8916 11.31 20.4856 10.123C21.6087 9.28766 22.8852 8.92858 24.281 9.01169C27.0429 9.17791 29.6875 11.4635 29.7206 14.9147C29.9891 14.9147 30.2587 14.9147 30.5282 14.9147C31.1047 14.9147 31.6801 14.9105 32.2565 14.9158C33.4968 14.9265 34.361 15.7661 34.4547 17C34.6519 19.5988 34.8692 22.1955 35.0759 24.7933C35.2422 26.8849 35.4041 28.9776 35.5671 31.0693C35.6428 32.0421 35.7589 33.0138 35.7834 33.9888C35.8463 36.4885 34.0893 38.5546 31.6194 38.9435C31.565 38.9521 31.5139 38.9808 31.4606 39C26.4196 39 21.3807 39 16.3407 39ZM27.9241 14.9019C28.0274 13.4944 27.2091 12.0112 25.8751 11.2813C24.4409 10.497 22.9971 10.5535 21.6385 11.4613C20.4355 12.2658 19.874 13.44 19.857 14.9009C22.5602 14.9019 25.2326 14.9019 27.9241 14.9019Z"
                                fill="#E17A1A" />
                            <path
                                d="M22.6926 27.824C23.9637 26.5528 25.2125 25.304 26.4613 24.0563C26.5508 23.9668 26.6414 23.8751 26.7448 23.8037C27.1007 23.5565 27.5716 23.6045 27.8678 23.9092C28.1694 24.2193 28.2035 24.6785 27.9446 25.0334C27.8764 25.1271 27.7922 25.2113 27.7101 25.2933C26.2962 26.7084 24.8822 28.1234 23.4672 29.5363C22.9195 30.084 22.4731 30.0861 21.9318 29.5448C21.2829 28.897 20.6339 28.2491 19.9882 27.5981C19.7389 27.3466 19.6185 27.0515 19.7282 26.6935C19.8316 26.3546 20.0607 26.1468 20.4027 26.0744C20.7384 26.003 21.0122 26.1351 21.2466 26.3706C21.7197 26.848 22.1949 27.3242 22.6926 27.824Z"
                                fill="#FBEBDD" />
                        </svg>

                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p> {{\App\CPU\translate('Today_Orders')}}</p>
                                <h2>{{ $data['today_orders'] }}</h2>
                            </div>
                            <div class="arrow__tab d-none">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOTAL SALE END -->



                <!-- SOLD OUT START -->

                <div class="col-md-3 d-none">
                    <div class="card-for-dashboard carddivseller">
                        <svg xmlns="http://www.w3.org/2000/svg" width="41" height="41" viewBox="0 0 41 41" fill="none">
                            <g opacity="0.25">
                                <rect width="41" height="41" rx="8" fill="#1271FF" />
                                <rect x="0.5" y="0.5" width="40" height="40" rx="7.5" stroke="white"
                                    stroke-opacity="0.07" />
                            </g>
                            <path
                                d="M22.0617 19.8625C22.061 21.9718 22.065 24.0817 22.0584 26.1909C22.0544 27.5347 20.9985 28.6305 19.4619 28.8932C19.244 28.9307 19.0183 28.9449 18.7958 28.9454C16.7269 28.9494 14.6581 28.9517 12.5885 28.9466C10.9294 28.9426 9.64107 28.0017 9.37493 26.6063C9.3458 26.4537 9.34513 26.2959 9.34513 26.141C9.34381 21.3629 9.34315 16.5849 9.34447 11.8068C9.34513 10.1838 10.7182 9.00518 12.6143 9.00235C14.6667 8.99894 16.719 8.99951 18.772 9.00235C20.6681 9.00462 22.0577 10.1974 22.0591 11.8227C22.0617 13.9109 22.0604 15.9992 22.061 18.0875L22.0677 18.0807C20.2729 18.079 18.4781 18.0705 16.6832 18.0801C16.1278 18.083 15.7028 18.4955 15.7028 18.975C15.7028 19.454 16.1271 19.8484 16.6832 19.8722C16.9719 19.8847 17.2619 19.8779 17.5519 19.8779C19.0573 19.8756 20.5622 19.8728 22.0677 19.8699L22.0617 19.8625Z"
                                fill="#2C41F8" />
                            <path
                                d="M22.0586 18.087C22.1572 18.0819 22.2559 18.0728 22.3545 18.0728C24.4651 18.0722 26.5764 18.0722 28.687 18.0722C28.7764 18.0722 28.8651 18.0722 29.0227 18.0722C28.9253 17.9843 28.8684 17.9304 28.8082 17.8787C27.3801 16.657 25.9501 15.4369 24.5234 14.214C23.962 13.7328 24.0778 13.0014 24.7544 12.717C25.1715 12.5417 25.6436 12.6149 26.0057 12.9202C26.5473 13.3781 27.0842 13.8401 27.6224 14.3009C29.1617 15.6174 30.7016 16.9339 32.2409 18.251C32.8201 18.747 32.8182 19.2049 32.2356 19.7037C30.1693 21.4714 28.1037 23.239 26.0362 25.0056C25.6025 25.3761 25.0424 25.4329 24.5995 25.1588C24.0593 24.824 24.0024 24.1907 24.4903 23.7673C25.3099 23.0569 26.1394 22.3538 26.9644 21.6479C27.5787 21.1224 28.1938 20.5963 28.8082 20.0709C28.8657 20.0215 28.9194 19.9699 29.0207 19.8774C28.8823 19.8774 28.7976 19.8774 28.7122 19.8774C26.5929 19.8774 24.4737 19.8774 22.3545 19.8768C22.2559 19.8768 22.1572 19.8677 22.0586 19.8626L22.0652 19.8694C22.0652 19.273 22.0652 18.6772 22.0652 18.0808L22.0586 18.087Z"
                                fill="#2C41F8" />
                        </svg>
                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p>{{\App\CPU\translate('Sold_Out')}}</p>
                                <h2>452</h2>
                            </div>
                            <div class="arrow__tab d-none">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SOLD OUT END -->


                <!-- TOTAL PRODUCT START -->

                <div class="col-md-3 ">
                    <div class="card-for-dashboard carddivseller">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="8" fill="#FFE8C4" />
                            <path
                                d="M31.8366 19.8383C30.9798 18.4792 30.1097 17.1278 29.2462 15.7736C29.0553 15.4741 28.7748 15.3347 28.404 15.3347C22.5901 15.3361 16.7763 15.3361 10.9617 15.334C10.5784 15.334 10.2972 15.4803 10.1012 15.7888C9.24354 17.1368 8.37858 18.4813 7.52464 19.832C7.13695 20.4456 7.5408 21.111 8.29635 21.1186C8.78169 21.1234 9.26777 21.1324 9.75165 21.1137C9.97706 21.1048 10.0138 21.1779 10.013 21.3698C10.0057 23.4439 10.0086 25.5179 10.0086 27.592C10.0086 28.2408 10.3442 28.5562 11.0351 28.5562C13.9193 28.5562 16.8035 28.5562 19.6876 28.5562C22.5718 28.5562 25.4559 28.5562 28.3401 28.5562C29.0098 28.5562 29.3475 28.2353 29.3482 27.6003C29.349 25.5179 29.3519 23.4349 29.3431 21.3526C29.3424 21.1593 29.3908 21.1082 29.5964 21.1151C30.081 21.131 30.5671 21.1234 31.0525 21.1193C31.8117 21.1117 32.2236 20.4512 31.8366 19.8383ZM9.80378 19.4662C10.328 18.6463 10.8259 17.8698 11.3193 17.0912C11.3677 17.0153 11.4206 16.9856 11.5146 16.9856C13.1138 16.9877 14.7138 16.987 16.31 16.987C16.3526 17.0774 16.2785 17.1244 16.2447 17.1775C15.7975 17.885 15.346 18.5904 14.8966 19.2964C14.8349 19.3931 14.7835 19.4704 14.6301 19.4697C13.0404 19.4635 11.45 19.4662 9.80378 19.4662ZM29.2462 19.4662C26.6021 19.4662 23.9588 19.4649 21.3147 19.4697C21.1326 19.4697 21.0232 19.4303 20.9248 19.2702C20.5004 18.5793 20.057 17.8995 19.6208 17.2155C19.5804 17.152 19.5415 17.0878 19.4798 16.987C22.2986 16.987 25.0609 16.987 27.8239 16.9863C27.9091 16.9863 27.9781 16.9912 28.0302 17.074C28.5273 17.8601 29.0288 18.6435 29.5538 19.4662C29.4202 19.4662 29.3336 19.4662 29.2462 19.4662Z"
                                fill="#FFA412" />
                        </svg>
                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p>{{\App\CPU\translate('Total_Product')}}</p>
                                   <h2>{{ $data['total_products'] }}</h2>
                            </div>
                            <div class="arrow__tab d-none">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOTAL PRODUCT END -->




                <!-- TOTAL ORDER START -->
                <div class="col-md-3 mt-2">
                    <div class="card-for-dashboard carddivseller">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <g opacity="0.15">
                                <rect width="40" height="40" rx="8" fill="#E92068" />
                                <rect x="0.5" y="0.5" width="39" height="39" rx="7.5" stroke="white"
                                    stroke-opacity="0.07" />
                            </g>
                            <path
                                d="M20.6568 13.9302C20.6568 14.426 20.6556 14.9007 20.6581 15.3755C20.6587 15.4778 20.6587 15.5846 20.6848 15.6818C20.7445 15.9081 20.9771 16.0498 21.2193 16.0244C21.4754 15.9977 21.6648 15.8032 21.6712 15.5261C21.6813 15.0825 21.6743 14.6382 21.675 14.194C21.675 14.1088 21.675 14.0237 21.675 13.915C22.0214 13.915 22.3449 13.915 22.6684 13.915C23.2963 13.915 23.9242 13.9137 24.5515 13.9156C25.0078 13.9169 25.2475 14.126 25.29 14.5779C25.3943 15.6825 25.4902 16.7883 25.5907 17.8929C25.644 18.477 25.6993 19.0604 25.7572 19.6782C23.3052 19.5244 21.2676 20.343 19.795 22.3094C18.3262 24.2714 18.1254 26.4456 18.9624 28.759C18.8614 28.759 18.7781 28.759 18.6942 28.759C16.0954 28.759 13.4966 28.7425 10.8985 28.7686C10.0856 28.7768 9.41318 28.175 9.50915 27.289C9.6674 25.8234 9.78435 24.3534 9.91972 22.8852C10.0557 21.4095 10.1924 19.9337 10.3277 18.4573C10.4453 17.1786 10.5572 15.8992 10.6818 14.6217C10.7332 14.0942 10.9474 13.915 11.473 13.9143C12.4016 13.9137 13.3308 13.9143 14.2962 13.9143C14.2962 14.0898 14.2962 14.2525 14.2962 14.4152C14.2962 14.7717 14.2892 15.1289 14.2987 15.4854C14.307 15.814 14.5173 16.0276 14.8097 16.0269C15.1027 16.0269 15.3137 15.814 15.3194 15.4842C15.3283 14.9706 15.322 14.4565 15.322 13.9283C17.1041 13.9302 18.8646 13.9302 20.6568 13.9302Z"
                                fill="#E92068" />
                            <path
                                d="M30.5193 26.2289C30.5256 29.2306 28.0978 31.6622 25.0897 31.6667C22.0791 31.6711 19.6392 29.2509 19.6328 26.2537C19.6265 23.2379 22.0556 20.8063 25.0764 20.8057C28.0889 20.805 30.5136 23.2201 30.5193 26.2289ZM24.4249 26.8492C24.0245 26.4831 23.6432 26.1253 23.2491 25.7808C23.1462 25.6906 23.0127 25.613 22.8812 25.5819C22.6422 25.5253 22.4019 25.6677 22.2933 25.8895C22.1795 26.1215 22.2278 26.3814 22.4414 26.5797C22.9473 27.0494 23.4576 27.514 23.968 27.9792C24.3042 28.2855 24.5717 28.2824 24.9048 27.9665C25.5035 27.3983 26.099 26.8269 26.6958 26.2568C27.046 25.9225 27.4006 25.5927 27.7432 25.2507C27.9516 25.0429 27.9777 24.7874 27.8417 24.5548C27.7171 24.3419 27.4769 24.2231 27.2309 24.2949C27.1013 24.333 26.9748 24.4201 26.8744 24.5148C26.0621 25.2819 25.255 26.0566 24.4249 26.8492Z"
                                fill="#E92068" />
                            <path
                                d="M15.3189 13.8147C14.9579 13.8147 14.6357 13.8147 14.2823 13.8147C14.3376 12.7704 14.1444 11.7256 14.5289 10.7208C15.1276 9.15603 16.7559 8.1563 18.3785 8.35968C20.1014 8.57513 21.4348 9.85832 21.6185 11.5661C21.6973 12.3008 21.6319 13.0514 21.6319 13.8121C21.3293 13.8121 21.0084 13.8121 20.6607 13.8121C20.6569 13.7308 20.6499 13.6469 20.6493 13.5636C20.6429 12.977 20.6639 12.3891 20.6258 11.8044C20.5368 10.4214 19.3292 9.33208 17.9463 9.35623C16.5296 9.38102 15.3653 10.5091 15.3233 11.9169C15.303 12.5404 15.3189 13.1658 15.3189 13.8147Z"
                                fill="#E92068" />
                        </svg>
                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p>{{\App\CPU\translate('Total_Order')}}</p>
                              <h2>{{ $data['total_order'] }}</h2>
                            </div>
                            <div class="arrow__tab d-none">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- TOTAL ORDER END -->


                <!-- Rating & Review START-->


                <div class="col-md-3 d-none mt-2">
                    <div class="card-for-dashboard carddivseller">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.2">
                                <rect width="40" height="40" rx="8" fill="#EDB900" />
                                <rect x="0.5" y="0.5" width="39" height="39" rx="7.5" stroke="white"
                                    stroke-opacity="0.07" />
                            </g>
                            <path
                                d="M26.9041 22.7067C26.6019 22.9996 26.4631 23.4231 26.5319 23.8384L27.5691 29.5784C27.6566 30.0649 27.4512 30.5572 27.0441 30.8384C26.6451 31.1301 26.1142 31.1651 25.6791 30.9317L20.5119 28.2367C20.3322 28.1411 20.1327 28.0897 19.9286 28.0839H19.6124C19.5027 28.1002 19.3954 28.1352 19.2974 28.1889L14.1291 30.8967C13.8736 31.0251 13.5842 31.0706 13.3007 31.0251C12.6101 30.8944 12.1492 30.2364 12.2624 29.5422L13.3007 23.8022C13.3696 23.3834 13.2307 22.9576 12.9286 22.6601L8.71572 18.5767C8.36339 18.2349 8.24089 17.7216 8.40189 17.2584C8.55822 16.7964 8.95722 16.4592 9.43905 16.3834L15.2374 15.5422C15.6784 15.4967 16.0657 15.2284 16.2641 14.8317L18.8191 9.59341C18.8797 9.47675 18.9579 9.36941 19.0524 9.27841L19.1574 9.19675C19.2122 9.13608 19.2752 9.08591 19.3452 9.04508L19.4724 8.99841L19.6707 8.91675H20.1619C20.6006 8.96225 20.9867 9.22475 21.1886 9.61675L23.7774 14.8317C23.9641 15.2132 24.3269 15.4781 24.7457 15.5422L30.5441 16.3834C31.0341 16.4534 31.4436 16.7917 31.6057 17.2584C31.7586 17.7262 31.6267 18.2396 31.2674 18.5767L26.9041 22.7067Z"
                                fill="#EDB900" />
                        </svg>

                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p>{{\App\CPU\translate('Rating & Review')}}</p>
                                <h2>452</h2>
                            </div>
                            <div class="arrow__tab d-none">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Rating & Review END-->

                <!-- Stock Management start -->

                <div class="col-md-3 d-none mt-2">
                    <div class="card-for-dashboard flex-column align-items-start p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <g opacity="0.25">
                                <rect width="40" height="40" rx="8" fill="#CB43D7" />
                                <rect x="0.5" y="0.5" width="39" height="39" rx="7.5" stroke="white"
                                    stroke-opacity="0.07" />
                            </g>
                            <path
                                d="M28.4962 8.33301H11.8281V13.3334H12.6615V22.5008C12.6615 22.9431 12.8374 23.367 13.1495 23.6796C13.4622 23.9923 13.8861 24.1676 14.3283 24.1676H25.996C26.4382 24.1676 26.8621 23.9918 27.1748 23.6796C27.4874 23.367 27.6628 22.9431 27.6628 22.5008V13.3334H28.4962V8.33301Z"
                                fill="#CB43D7" />
                            <path
                                d="M25.2544 26.1767C25.1441 25.9349 24.9456 25.8434 24.6855 25.8458C24.1016 25.8511 23.5178 25.8472 22.9339 25.8472C22.8703 25.8472 22.8067 25.8472 22.7321 25.8472C22.7321 25.8381 22.7321 25.8289 22.7321 25.8198C22.7321 25.4816 22.7321 25.166 22.7321 24.8312V24.6829H17.0895V24.8274C17.0895 25.1704 17.0895 25.4903 17.0895 25.8198C17.0895 25.8289 17.0895 25.8381 17.0895 25.8472C17.0066 25.8472 16.9416 25.8472 16.8761 25.8472C16.2802 25.8472 15.6843 25.8443 15.0884 25.8487C14.7377 25.8511 14.4751 26.131 14.5416 26.4576C14.569 26.5939 14.6596 26.7365 14.7608 26.8362C16.2835 28.3349 17.8121 29.8278 19.3421 31.3188C19.4731 31.4464 19.6268 31.551 19.7699 31.6661C19.8662 31.6661 19.9626 31.6661 20.0589 31.6661C20.1548 31.5977 20.2617 31.5399 20.3455 31.4585C21.9271 29.9169 23.5062 28.3725 25.0848 26.828C25.2968 26.6204 25.3556 26.3988 25.2544 26.1767Z"
                                fill="#CB43D7" />
                        </svg>
                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p>{{\App\CPU\translate('Stock Management')}}</p>
                                <h2>452</h2>
                            </div>
                            <div class="arrow__tab">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Stock Management end -->

                <!-- Customers start -->
                <div class="col-md-3 mt-2">
                    <div class="card-for-dashboard carddivseller">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.2" width="40" height="40" rx="8" fill="#1698B5" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M13.8258 23.7568C15.4941 23.1975 17.7042 23.0288 19.9788 23.0288C22.2672 23.0288 24.478 23.2041 26.1441 23.7707C26.9817 24.0556 27.7454 24.4602 28.3074 25.0492C28.8895 25.6593 29.2183 26.4308 29.2183 27.3427C29.2183 28.2565 28.8865 29.0276 28.3011 29.6354C27.7363 30.2217 26.9698 30.6224 26.1314 30.9037C24.4634 31.4632 22.2536 31.6321 19.9788 31.6321C17.6905 31.6321 15.4795 31.4572 13.8132 30.8907C12.9754 30.6059 12.2115 30.2014 11.6494 29.6123C11.0671 29.0021 10.7383 28.2304 10.7383 27.3183C10.7383 26.4042 11.0701 25.6329 11.6557 25.025C12.2206 24.4387 12.9873 24.038 13.8258 23.7568Z"
                                fill="#00C2ED" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M13.7813 14.6432C13.782 11.2223 16.5562 8.4502 19.9769 8.4502C23.3982 8.4502 26.1713 11.2233 26.1713 14.6446C26.1713 18.0658 23.3984 20.8402 19.9769 20.8402H19.9391L19.9363 20.8402C16.5263 20.8286 13.7705 18.0537 13.7813 14.6432ZM13.7813 14.6432L13.7813 14.6418L14.6146 14.6446H13.7813L13.7813 14.6432Z"
                                fill="#00C2ED" />
                        </svg>


                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p>{{\App\CPU\translate('Drivers')}}</p>
                                 <h2>{{ $data['drivers'] }}</h2>
                            </div>
                            <div class="arrow__tab d-none">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Customers end -->

                <!-- Total Delivery start -->
                <div class="col-md-3 d-none">
                    <div class="card-for-dashboard flex-column align-items-start p-3">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.25">
                                <rect width="40" height="40" rx="8" fill="#4399D7" />
                                <rect x="0.5" y="0.5" width="39" height="39" rx="7.5" stroke="white"
                                    stroke-opacity="0.07" />
                            </g>
                            <path
                                d="M8.32812 13.3707C8.43726 13.0927 8.64422 12.999 8.93663 13C13.2724 13.0052 17.6091 13.0031 21.9449 13.0031C22.4031 13.0031 22.5565 13.1575 22.5565 13.6209C22.5575 17.4109 22.5575 21.201 22.5565 24.991C22.5565 25.442 22.4031 25.5985 21.9593 25.5985C20.0988 25.5995 18.2382 25.5995 16.3777 25.5985C15.9514 25.5985 15.8238 25.4935 15.7486 25.0775C15.4614 23.5083 13.9941 22.6795 12.495 23.0079C11.3573 23.2571 10.6798 23.9861 10.4687 25.1382C10.41 25.4574 10.2535 25.5913 9.92095 25.5964C9.60177 25.6016 9.28259 25.5861 8.96443 25.6016C8.6782 25.6149 8.46301 25.5254 8.32812 25.2669C8.32813 21.3019 8.32812 17.3357 8.32812 13.3707Z"
                                fill="#0C77C8" />
                            <path
                                d="M23.4942 20.5866C23.4942 19.1204 23.4942 17.6553 23.4942 16.1891C23.4942 15.7206 23.6538 15.5631 24.1254 15.5631C25.1581 15.5621 26.1908 15.5662 27.2235 15.561C27.4779 15.56 27.6704 15.6455 27.8351 15.8432C29.0398 17.2908 30.2547 18.7302 31.4522 20.183C31.5665 20.322 31.6447 20.5362 31.6468 20.7164C31.6622 22.1445 31.6561 23.5725 31.655 24.9996C31.655 25.432 31.4944 25.5927 31.0671 25.5978C30.9765 25.5988 30.8849 25.5988 30.7932 25.5978C30.4215 25.5916 30.2774 25.4691 30.2064 25.1015C29.951 23.7682 28.9059 22.9198 27.5407 22.9393C26.1888 22.9579 25.2065 23.7713 24.9357 25.0933C24.8544 25.4897 24.7236 25.5947 24.3128 25.5988C23.5715 25.605 23.4922 25.5247 23.4922 24.7813C23.4942 23.3821 23.4942 21.9838 23.4942 20.5866ZM26.8714 19.7629C27.5087 19.7629 28.1471 19.7598 28.7844 19.765C29.0141 19.7671 29.2117 19.7156 29.3106 19.4891C29.4043 19.2759 29.3291 19.0947 29.186 18.9248C28.5806 18.2041 27.9783 17.4813 27.379 16.7554C27.2318 16.5773 27.0567 16.498 26.8261 16.5011C26.2186 16.5083 25.6112 16.5011 25.0047 16.5042C24.5959 16.5062 24.423 16.6772 24.4209 17.0859C24.4168 17.784 24.4168 18.4831 24.4209 19.1812C24.423 19.59 24.5959 19.7609 25.0047 19.7629C25.6266 19.765 26.2495 19.7629 26.8714 19.7629Z"
                                fill="#0C77C8" />
                            <path
                                d="M27.5837 27.775C26.5046 27.7771 25.6109 26.8916 25.6016 25.8095C25.5924 24.7253 26.4933 23.8151 27.5785 23.812C28.6565 23.8089 29.5729 24.7284 29.5636 25.8043C29.5533 26.8854 28.6627 27.773 27.5837 27.775ZM27.5816 26.3727C27.8956 26.3727 28.1592 26.1091 28.1582 25.7951C28.1582 25.4821 27.8925 25.2175 27.5795 25.2185C27.2655 25.2185 27.004 25.4821 27.004 25.7971C27.004 26.1122 27.2655 26.3737 27.5816 26.3727Z"
                                fill="#0C77C8" />
                            <path
                                d="M15.082 25.7868C15.0912 26.8659 14.2109 27.7606 13.1247 27.775C12.0384 27.7894 11.1375 26.9009 11.1251 25.8043C11.1128 24.7263 12.025 23.81 13.1071 23.812C14.1862 23.8151 15.0727 24.7006 15.082 25.7868ZM13.0999 26.3727C13.4119 26.3747 13.6858 26.1091 13.6889 25.8012C13.692 25.4965 13.4181 25.2205 13.1092 25.2195C12.8003 25.2174 12.5234 25.4862 12.5203 25.792C12.5172 26.0967 12.789 26.3696 13.0999 26.3727Z"
                                fill="#0C77C8" />
                        </svg>

                        <div class="d-flex justify-content-between align-items-end w-100">
                            <div class="content handle">
                                <p>{{\App\CPU\translate('Total Delivery')}}</p>
                                <h2>452</h2>
                            </div>
                            <div class="arrow__tab">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Total Delivery end -->
            </div>
        </div>


    </section>

    <!-- Order Statistics -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row justify-content-between align-items-center g-2 mb-3">
                <div class="col-sm-6">
                    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                        <img src="{{asset('/public/assets/back-end/img/business_analytics.png')}}" alt="">
                        {{\App\CPU\translate('business_analytics')}}
                    </h4>
                </div>
                <div class="col-sm-6 d-flex justify-content-sm-end">
                    <select class="custom-select w-auto" name="statistics_type"
                        onchange="order_stats_update(this.value)">
                        <option value="overall" {{session()->has('statistics_type') && session('statistics_type') ==
                            'overall'?'selected':''}}>
                            {{\App\CPU\translate('Overall Statistics')}}
                        </option>
                        <option value="today" {{session()->has('statistics_type') && session('statistics_type') ==
                            'today'?'selected':''}}>
                            {{\App\CPU\translate('Todays Statistics')}}
                        </option>
                        <option value="this_month" {{session()->has('statistics_type') && session('statistics_type') ==
                            'this_month'?'selected':''}}>
                            {{\App\CPU\translate('This Months Statistics')}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row g-2" id="order_stats">
                @include('seller-views.partials._dashboard-order-stats',['data'=>$data])
            </div>
        </div>
    </div>

    <!-- Seller Wallet -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row justify-content-between align-items-center g-2 mb-3">
                <div class="col-sm-6">
                    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                        <img width="20" class="mb-1" src="{{asset('/public/assets/back-end/img/admin-wallet.png')}}"
                            alt="">
                        {{\App\CPU\translate('Seller_Wallet')}}
                    </h4>
                </div>
            </div>
            <div class="row g-2" id="order_stats">
                @include('seller-views.partials._dashboard-wallet-stats',['data'=>$data])
            </div>
        </div>
    </div>

    <div class="modal fade" id="balance-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left'
                }};">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{\App\CPU\translate('Withdraw Request')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('seller.withdraw.request')}}" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="">
                            <select class="form-control" id="withdraw_method" name="withdraw_method" required>
                                @foreach($withdrawal_methods as $item)
                                <option value="{{$item['id']}}" {{ $item['is_default'] ? 'selected' :'' }}>
                                    {{$item['method_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="" id="method-filed__div">

                        </div>

                        <div class="mt-1">
                            <label for="recipient-name" class="col-form-label fz-16">{{\App\CPU\translate('Amount')}}
                                :</label>
                            <input type="number" name="amount" step=".01"
                                value="{{\App\CPU\BackEndHelper::usd_to_currency($data['total_earning'])}}"
                                class="form-control" id="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                        @if(auth('seller')->user()->account_no==null || auth('seller')->user()->bank_name==null)
                        <button type="button" class="btn btn--primary" onclick="call_duty()">
                            {{\App\CPU\translate('Incomplete bank info')}}
                        </button>
                        @else
                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Request')}}</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-2">
        <div class="col-lg-12">
            <!-- Card -->
            <div class="card h-100">
                <!-- Body -->
                <div class="card-body">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                <img src="{{asset('/public/assets/back-end/img/earning_statictics.png')}}" alt="">
                                {{\App\CPU\translate('Earning_statistics')}}
                            </h4>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <ul class="option-select-btn">
                                <li>
                                    <label>
                                        <input type="radio" name="statistics2" hidden="" checked="">
                                        <span data-earn-type="yearEarn"
                                            onclick="earningStatisticsUpdate(this)">{{\App\CPU\translate('This_Year')}}</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="statistics2" hidden="">
                                        <span data-earn-type="MonthEarn"
                                            onclick="earningStatisticsUpdate(this)">{{\App\CPU\translate('This_Month')}}</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="statistics2" hidden="">
                                        <span data-earn-type="WeekEarn"
                                            onclick="earningStatisticsUpdate(this)">{{\App\CPU\translate('This
                                            Week')}}</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Bar Chart -->
                    <div class="chartjs-custom mt-2" id="set-new-graph">
                        <canvas id="updatingData" class="earningShow" data-hs-chartjs-options='{
                        "type": "bar",
                        "data": {
                          "labels": ["Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                          "datasets": [{
                            "label": "{{ \App\CPU\translate(' seller')}}", "data" : [ @php($i=0)
                            @php($array_count=count($seller_data)) @foreach($seller_data as $value) {{ $value }}{{ (++$i
                            < $array_count) ? ',' :'' }} @endforeach ], "backgroundColor" : "#0177CD" , "borderColor"
                            : "#0177CD" }, { "label" : "{{ \App\CPU\translate('commission')}}" , "data" : [ @php($i=0)
                            @php($array_count=count($commission_data)) @foreach($commission_data as $value) {{ $value
                            }}{{ (++$i < $array_count) ? ',' :'' }} @endforeach ], "backgroundColor" : "#FFB36D"
                            , "borderColor" : "#FFB36D" }] }, "options" : { "legend" : { "display" : true, "position"
                            : "top" , "align" : "center" , "labels" : { "usePointStyle" : true, "boxWidth" :
                            6, "fontColor" : "#758590" , "fontSize" : 14 } }, "scales" : { "yAxes" : [{ "gridLines" :
                            { "color" : "rgba(180, 208, 224, 0.5)" , "borderDash" : [8, 4], "drawBorder" :
                            false, "zeroLineColor" : "rgba(180, 208, 224, 0.5)" }, "ticks" : { "beginAtZero" :
                            true, "fontSize" : 12, "fontColor" : "#97a4af" , "fontFamily" : "Open Sans, sans-serif"
                            , "padding" : 10, "postfix" : " {{\App\CPU\BackEndHelper::currency_symbol()}}" } }], "xAxes"
                            : [{ "gridLines" : { "color" : "rgba(180, 208, 224, 0.5)" , "display" : true, "drawBorder" :
                            true, "zeroLineColor" : "rgba(180, 208, 224, 0.5)" }, "ticks" : { "fontSize" :
                            12, "fontColor" : "#97a4af" , "fontFamily" : "Open Sans, sans-serif" , "padding" : 5
                            }, "categoryPercentage" : 0.5, "maxBarThickness" : "7" }] }, "cornerRadius" : 3, "tooltips"
                            : { "prefix" : " " , "hasIndicator" : true, "mode" : "index" , "intersect" : false
                            }, "hover" : { "mode" : "nearest" , "intersect" : true } } }'></canvas>
                    </div>
                    <!-- End Bar Chart -->
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->
        </div>




        <div class="container-fluid  mt-5 pt-md-5 d-none">
            <div class="row">
                <!-- GRAPH CHART -->
                <div class="col-md-6 mb-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center  mb-3">
                                <h5 class="mb-0">Product Sale</h5>
                                <div class="sell-chart">
                                    <button class="btn btn--primary">Daily</button>
                                    <button class="btn">Weekly</button>
                                    <button class="btn">Monthly</button>
                                </div>
                            </div>
                            <div class="gains">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- PIE CHART -->
                <div class="col-md-6 mb-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4>Category wise product count</h4>
                            <div class="w-100">
                                <canvas id="labelChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-4">
            <!-- Card -->
            <div class="card h-100 d-none">
                @include('seller-views.partials._top-selling-products',['top_sell'=>$data['top_sell']])
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-4">
            <!-- Card -->
            <div class="card h-100 d-none">
                @include('seller-views.partials._most-rated-products',['most_rated_products'=>$data['most_rated_products']])
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-4">
            <!-- Card -->
            <div class="card h-100 d-none">
                @include('seller-views.partials._top-delivery-man',['top_deliveryman'=>$data['top_deliveryman']])
            </div>
            <!-- End Card -->
        </div>
    </div>
                        </div>
</div>

<style>
      .footer{
        position: inherit !important;
        bottom:auto !important;
        left:auto !important;
      padding:0 !important;
      margin:0 !important;
      text-align: center;
      }
</style>

@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{asset('public/assets/back-end')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
@endpush

@push('script_2')
    <script>
        function earningStatisticsUpdate(t) {
            let value = $(t).attr('data-earn-type');

            $.ajax({
                url: '{{route('seller.dashboard.earning-statistics')}}',
                type: 'GET',
                data: {
                    type: value
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (response_data) {
                    document.getElementById("updatingData").remove();
                    let graph = document.createElement('canvas');
                    graph.setAttribute("id", "updatingData");
                    document.getElementById("set-new-graph").appendChild(graph);

                    var ctx = document.getElementById("updatingData").getContext("2d");
                    var options = {
                        responsive: true,
                        bezierCurve: false,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    color: "rgba(180, 208, 224, 0.5)",
                                    zeroLineColor: "rgba(180, 208, 224, 0.5)",
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    color: "rgba(180, 208, 224, 0.5)",
                                    zeroLineColor: "rgba(180, 208, 224, 0.5)",
                                    borderDash: [8, 4],
                                }
                            }]
                        },
                        legend: {
                            display: true,
                            position: "top",
                            labels: {
                                usePointStyle: true,
                                boxWidth: 6,
                                fontColor: "#758590",
                                fontSize: 14
                            }
                        },
                        plugins: {
                            datalabels: {
                                display: false
                            }
                        },
                    };
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [],
                            datasets: [
                                {
                                    label: "{{ \App\CPU\translate('seller')}}",
                                    data: [],
                                    backgroundColor: "#0177CD",
                                    borderColor: "#0177CD",
                                    fill: false,
                                    lineTension: 0.3,
                                    radius: 0
                                },
                                {
                                    label: "{{ \App\CPU\translate('In-house')}}",
                                    data: [],
                                    backgroundColor: "#FFB36D",
                                    borderColor: "#FFB36D",
                                    fill: false,
                                    lineTension: 0.3,
                                    radius: 0
                                }
                            ]
                        },
                        options: options
                    });

                    myChart.data.labels = response_data.seller_label;
                    myChart.data.datasets[0].data = response_data.seller_earn;
                    myChart.data.datasets[1].data = response_data.commission_earn;

                    myChart.update();
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
    </script>
    <script>
        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function () {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));
    </script>

    <script>
        $(document).ready(function () {
            let method_id = $('#withdraw_method').val()
            withdraw_method_field(method_id);
        });

        $('#withdraw_method').on('change', function () {
            withdraw_method_field(this.value);
        });

        function withdraw_method_field(method_id){

            // Set header if need any otherwise remove setup part
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('seller.withdraw.method-list')}}" + "?method_id=" + method_id,
                data: {},
                processData: false,
                contentType: false,
                type: 'get',
                success: function (response) {
                    let method_fields = response.content.method_fields;
                    $("#method-filed__div").html("");
                    method_fields.forEach((element, index) => {
                        $("#method-filed__div").append(`
                        <div class="mt-3">
                            <label for="wr_num" class="fz-16 c1 mb-2" style="color: #5b6777 !important;">${element.input_name.replaceAll('_', ' ')}</label>
                            <input type="${element.input_type}" class="form-control" name="${element.input_name}" placeholder="${element.placeholder}" ${element.is_required === 1 ? 'required' : ''}>
                        </div>
                    `);
                    })

                },
                error: function () {

                }
            });
        }
    </script>

    <script>
       var ctxP = document.getElementById("labelChart").getContext('2d');
var myPieChart = new Chart(ctxP, {
  plugins: [ChartDataLabels],
  type: 'pie',
  data: {
    labels: ["Product 1", "Product 2", "Product 3", "Product 4"],
    datasets: [{
      data: [210, 130, 120, 160],
      backgroundColor: ["#F765A3", "#A155B9", "#16BFD6", "#16BFD6"],
      hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5"]
    }]
  },
  options: {
    responsive: true,
    legend: {
      position: 'right',
      labels: {
        padding: 20,
        boxWidth: 10
      }
    },
    plugins: {
      datalabels: {
        formatter: (value, ctx) => {
          let sum = 0;
          let dataArr = ctx.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += data;
          });
          let percentage = (value * 100 / sum).toFixed(2) + "%";
          return percentage;
        },
        color: 'white',
        labels: {
          title: {
            font: {
              size: '18'
            }
          }
        }
      }
    }
  }
});
    </script>

    <script>
        $(function () {

            //get the doughnut chart canvas
            var ctx1 = $("#user_overview");

            //doughnut chart data
            var data1 = {
                labels: ["Customer", "Seller", "Delivery Man"],
                datasets: [
                    {
                        label: "User Overview",
                        data: [88297, 34546, 15000],
                        backgroundColor: [
                            "#017EFA",
                            "#51CBFF",
                            "#56E7E7",
                        ],
                        borderColor: [
                            "#017EFA",
                            "#51CBFF",
                            "#56E7E7",
                        ],
                        borderWidth: [1, 1, 1]
                    }
                ]
            };

            //options
            var options = {
                responsive: true,
                legend: {
                    display: true,
                    position: "bottom",
                    align: "start",
                    maxWidth: 100,
                    labels: {
                        usePointStyle: true,
                        boxWidth: 6,
                        fontColor: "#758590",
                        fontSize: 14
                    }
                },
                plugins: {
                    datalabels: {
                        display: false
                    }
                },
            };

            //create Chart class object
            var chart1 = new Chart(ctx1, {
                type: "doughnut",
                data: data1,
                options: options
            });
        });
    </script>

    <script>
        function call_duty() {
            toastr.warning('{{\App\CPU\translate('Update your bank info first!')}}', '{{\App\CPU\translate('Warning')}}!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>

    <script>
        function order_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('seller.dashboard.order-stats')}}',
                data: {
                    statistics_type: type
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    $('#order_stats').html(data.view)
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }

        function business_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.dashboard.business-overview')}}',
                data: {
                    business_overview: type
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    console.log(data.view)
                    $('#business-overview-board').html(data.view)
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
    </script>

<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<script>
    const dataPie = {
  type: "pie",
  data: {
    labels: ["Monday", "Tuesday", "Wednesday", "Thursday"],
    datasets: [{
      data: [1234, 2234, 3234, 4234],
      backgroundColor: ["rgba(117,169,255,0.6)", "rgba(148,223,215,0.6)",
        "rgba(208,129,222,0.6)", "rgba(247,127,167,0.6)"
      ],
    }, ],
  },
};

new mdb.Chart(document.getElementById("chart-pie"), dataPie);
    </script>
     
     <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.6/angular.js"></script>
     <script>
          var ctx = document.getElementById("myChart").getContext("2d");
var chart = new Chart(ctx, {
	// The type of chart we want to create
	type: "line",

	// The data for our dataset
	data: {
		labels: ["2015", "2016", "2017", "2018"],
		datasets: [
			{
				label: "Earnings",
				backgroundColor: "rgba(255, 255, 255, 0)",
				borderColor: "rgba(26, 188, 138, 1)",
				data: [0, 1000, 0.00046, -100.01]
			}
		]
	},

	// Configuration options go here
	options: {}
});

angular.module("app", []).controller("coin", function ($scope, $http) {
	$http
		.get("https://api.coindesk.com/v1/bpi/currentprice.json")
		.then(function (res) {
			$scope.content = res.data;
			$scope.priceGBP = $scope.content.bpi.GBP.rate;
		});
});

     </script>
    
@endpush
