<div class="table-responsive d-none d-md-block">
    <table class="table align-middle table-striped">
        <tbody>
            @if($wishlists->count()>0)
            @foreach($wishlists as $key=>$wishlist)
            @php($product = $wishlist->product_full_info)
            @if( $wishlist->product_full_info)
            @php($compare_list = count($product->compare_list)>0 ? 1 : 0)
            <div class="card br-12 mb-4">
                <div class="card-body">
                    <div class="d-flex ">
                        <div class="product-images-cart">
                            <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" class="me-4"
                                alt="">
                        </div>

                        <div class="product-content-cart">
                            <a href="{{route('product',$product['slug'])}}"><h6>{{$product['name']}}</h6>
                            <p class="item-bill"><span> {{\App\CPU\Helpers::get_price_range($product) }}</span><s>{{\App\CPU\Helpers::currency_converter($product->unit_price)}}</s></p>
                            <div class="d-none align-items-center mt-md-3 mt-2 mb-md-0 mb-3">
                                <div class="icon-andstar">

                                    <p><i class="fa-solid fa-star"></i> 4.3</p>
                                </div>
                                <div class="review-first-rate">
                                    <p>24-Inch</p>
                                </div>
                            </div>
                            <!-- BUTTON DELETE ADDD CART SECTION -->
                            <div class="d-flex align-items-center justify-content-md-end justify-content-start">
                                <a href="#"
                                    class="btn d-none btn-save-later btn-action add_to_compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
                                    onclick="addCompareList('{{$product['id']}}','{{route('store-compare-list')}}')"
                                    id="compare_list-{{$product['id']}}">
                                    <i class="bi bi-repeat"></i>
                                </a>
                                <button type="button"
                                    onclick="removeWishlist({{$product['id']}}, '{{ route('delete-wishlist') }}')"
                                    class="btn btn-outline-danger btn-action">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                            <!-- BUTTON DELETE ADDD CART SECTION -->
                        </div>
                    </div>
                </div>

            </div>
            @endif
            @endforeach
            @endif
            @if($wishlists->count()==0)


            <div class="col-lg-12">
                <div class="empty-content">
                    <div>
                    <svg width="300" height="300" viewBox="0 0 1080 1080" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_669_431)">
                        <path d="M768.02 371.24C752.81 341.03 727.18 317.56 693.54 310.3C689.21 309.36 684.81 308.71 680.39 308.33C643.63 305.18 609.2 320.44 582.43 344.97C574.51 352.22 567.28 360.21 560.66 368.66C553.67 377.58 548.05 387.4 543.34 397.7C541.83 400.99 540.45 401.16 538.13 398.33C535.61 395.25 533.1 392.16 530.34 389.27C511.2 369.29 488.21 355.36 462.16 346.56C440.38 339.2 418 335.26 394.99 339.33C347.55 347.72 313.25 374.25 290.69 416.32C282.95 430.76 278.45 446.34 277.25 462.77C277.25 473.08 277.25 483.38 277.25 493.69C278.45 494.21 278.13 495.34 278.24 496.26C280.92 518.93 288.1 540.17 298.49 560.41C312.72 588.15 332.61 611.54 355.65 632.12C413.41 683.7 476.47 727.46 547.41 759.22C564.5 766.87 581.87 773.92 599.52 780.2C609.3 783.68 618.06 781.48 625.85 774.59C632.22 768.97 638.35 763.12 643.62 756.46C676.78 714.6 706.19 670.25 731.55 623.24C759.82 570.85 779.2 515.73 783.52 455.85C785.67 426.24 781.43 397.87 768.02 371.24ZM759.5 450.57C753.39 516.91 730.94 577.74 696.3 634.3C672.79 672.68 647.9 710.11 619.61 745.2C610.85 756.06 610.95 756.18 597.78 751.57C545.23 733.17 497.07 706.38 451.41 674.85C416.77 650.93 383.91 624.79 354.9 594.18C332.08 570.1 314.72 542.69 306.78 510.03C293.54 455.54 316.61 402.88 365.68 376.09C381.08 367.68 397.71 363.45 415.54 363.3C465.9 367.09 505.61 389.03 532.47 432.62C535.13 436.94 538.15 440.09 543.46 439.64C548.42 439.22 551.57 436.19 553.26 431.58C561.72 408.55 574.29 388.02 590.93 370.02C607.32 352.29 626.81 339.53 650.63 334.21C688.73 325.7 721.44 339.89 742.04 373.73C756.44 397.38 762.02 423.18 759.5 450.57Z" fill="#B81C18"/>
                        <path d="M651.36 363.3H845.72C849.74 363.3 853.01 360.05 853.01 356.01C852.98 352 849.73 348.72 845.72 348.72H651.36C647.35 348.72 644.07 351.97 644.07 356.01C644.06 360.03 647.32 363.3 651.36 363.3Z" fill="#FFF9F9"/>
                        <path d="M562.561 356.01C562.561 360.03 565.811 363.3 569.851 363.3H618.911C622.931 363.3 626.201 360.05 626.201 356.01C626.201 352 622.921 348.72 618.911 348.72H569.851C565.841 348.72 562.561 351.97 562.561 356.01Z" fill="#FFF9F9"/>
                        <path d="M853.451 800.3H659.091C655.071 800.3 651.801 803.55 651.801 807.59C651.801 811.61 655.051 814.88 659.091 814.88H853.451C857.471 814.88 860.741 811.63 860.741 807.59C860.711 803.55 857.461 800.3 853.451 800.3Z" fill="#FFF9F9"/>
                        <path d="M626.639 800.3H577.579C573.559 800.3 570.289 803.55 570.289 807.59C570.289 811.61 573.539 814.88 577.579 814.88H626.639C630.659 814.88 633.929 811.63 633.929 807.59C633.929 803.55 630.659 800.3 626.639 800.3Z" fill="#FFF9F9"/>
                        <path d="M400.62 750.96C400.62 748.1 398.29 745.77 395.43 745.77H257.1C254.24 745.77 251.91 748.1 251.91 750.96C251.91 753.85 254.24 756.15 257.1 756.15H395.43C398.29 756.16 400.62 753.83 400.62 750.96Z" fill="#FFF9F9"/>
                        <path d="M453.44 756.16C456.3 756.16 458.63 753.83 458.63 750.97C458.63 748.11 456.3 745.78 453.44 745.78H418.53C415.67 745.78 413.34 748.11 413.34 750.97C413.34 753.86 415.67 756.16 418.53 756.16H453.44Z" fill="#FFF9F9"/>
                        <path d="M868.01 772.17H729.67C726.81 772.17 724.48 774.5 724.48 777.36C724.48 780.22 726.79 782.55 729.67 782.55H868C870.86 782.55 873.19 780.22 873.19 777.36C873.2 774.5 870.87 772.17 868.01 772.17Z" fill="#FFF9F9"/>
                        <path d="M926.02 772.17H891.1C888.24 772.17 885.91 774.5 885.91 777.36C885.91 780.22 888.22 782.55 891.1 782.55H926.01C928.87 782.55 931.2 780.22 931.2 777.36C931.21 774.5 928.88 772.17 926.02 772.17Z" fill="#FFF9F9"/>
                        <path d="M485.08 242.16H582.25C584.26 242.16 585.9 240.52 585.9 238.51C585.9 236.5 584.29 234.86 582.25 234.86H485.08C483.07 234.86 481.43 236.5 481.43 238.51C481.43 240.52 483.07 242.16 485.08 242.16Z" fill="#FFF9F9"/>
                        <path d="M444.33 242.16H468.86C470.87 242.16 472.51 240.52 472.51 238.51C472.51 236.5 470.87 234.86 468.86 234.86H444.33C442.32 234.86 440.68 236.5 440.68 238.51C440.68 240.52 442.32 242.16 444.33 242.16Z" fill="#FFF9F9"/>
                        <path d="M516.041 768.55H418.871C416.861 768.55 415.221 770.19 415.221 772.19C415.221 774.2 416.861 775.84 418.871 775.84H516.041C518.051 775.84 519.691 774.2 519.691 772.19C519.691 770.18 518.051 768.55 516.041 768.55Z" fill="#FFF9F9"/>
                        <path d="M402.651 768.55H378.121C376.111 768.55 374.471 770.19 374.471 772.19C374.471 774.2 376.111 775.84 378.121 775.84H402.651C404.661 775.84 406.301 774.2 406.301 772.19C406.301 770.18 404.661 768.55 402.651 768.55Z" fill="#FFF9F9"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_669_431">
                        <rect width="1080" height="1080" fill="white"/>
                        </clipPath>
                        </defs>
                        </svg>

                        <h3>{{translate('Not_Found_Anything')}}</h3>
                        <!-- <a href="{{route('account-address-add')}}" class="btn-login for-empty">{{translate('Add_New_Address')}}</a> -->
                    </div>
                </div>
            </div>



            <!-- <tr>
                <td><h5 class="text-center">{{translate('not_found_anything')}}</h5></td>
            </tr> -->
            @endif
        </tbody>
    </table>
</div>

<div class="d-flex flex-column gap-2 d-md-none">
    @if($wishlists->count()>0)
    @foreach($wishlists as $key=>$wishlist)
    @php($product = $wishlist->product_full_info)
    @if( $wishlist->product_full_info)
    <div class="media gap-3 bg-light p-3 rounded">
        <div class="avatar rounded">
            <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'" class="dark-support rounded"
                alt="">
        </div>
        <div class="media-body d-flex flex-column gap-1">
            <a href="{{route('product',$product['slug'])}}">
                <h6 class="text-truncate text-capitalize" style="--width: 20ch">{{$product['name']}}</h6>
            </a>
            <div>
                {{ translate('price') }} :
                @if($product->discount > 0)
                <del style="color: #E96A6A;">
                    {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                </del> &nbsp;&nbsp;
                @endif
                {{\App\CPU\Helpers::get_price_range($product) }}
            </div>

            @php($compare_list = count($product->compare_list)>0 ? 1 : 0)
            <div class="d-flex gap-2 align-items-center mt-1">
                <a href="#"
                    class="btn btn-outline-success rounded-circle btn-action add_to_compare compare_list-{{$product['id']}} {{($compare_list == 1?'compare_list_icon_active':'')}}"
                    onclick="addCompareList('{{$product['id']}}','{{route('store-compare-list')}}')">
                    <i class="bi bi-repeat"></i>
                </a>
                <button type="button" onclick="removeWishlist({{$product['id']}}, '{{ route('delete-wishlist') }}')"
                    class="btn btn-outline-danger rounded-circle btn-action">
                    <i class="bi bi-trash3-fill"></i>
                </button>
            </div>
        </div>
    </div>
    @endif
    @endforeach
    @endif
</div>

<div class="card-footer border-0 d-none">
    {{$wishlists->links()}}
</div>