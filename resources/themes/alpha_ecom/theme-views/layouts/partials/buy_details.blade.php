@php
    $shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method');
    $cart = \App\Model\Cart::where(['cart_group_id' => session()->get('buy_now')])->get()->groupBy('cart_group_id');
    $carts = \App\Model\Cart::where(['cart_group_id' => session()->get('buy_now')])->get();
    $savelists = \App\Model\Savelist::where(['customer_id' => auth('customer')->id()])->get();
    $coupon_dis = 0;
@endphp
<div class="container">
    <h4 class="d-none">{{ translate('Cart_List') }}</h4>

    <section class="">
        <div class="container">
            <div class="col-lg-12 mt-md-3">
            <div class="bread__crum">
                    <nav aria-label="breadcrumb">
                                <ol class="breadcrumb fs-12 mb-2">
                                    <li class="breadcrumb-item"><a href="#">{{ translate('home') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"> Cart</li>
                                </ol>
                        </nav>
              </div>
                <div class="home-and-back-btn">
                    <span class="cart-item-number d-block ">Cart <span>({{$carts->count()}} Items) </span></span>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-{{ $cart->count() > 0 ? 8 : 12 }}">
                    @if($carts->count() > 0)
                        @foreach($carts as $dt_cart)
                            <div class="card br-12 mb-2">
                                <div class="card-body">
                                    <div class="product-cart">
                                        <div class="product-images-cart">
                                            <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$dt_cart['thumbnail']}}"
                                                class="me-4 cart-list-image" alt="">
                                        </div>

                                        <div class="product-content-cart">
                                            <h6><a href="{{route('product',$dt_cart['slug'])}}">{{$dt_cart['name']}}</a></h6>
                                            <p class="item-bill"><span>{{ \App\CPU\Helpers::currency_converter(($dt_cart['price']-$dt_cart['discount'])*$dt_cart['quantity']) }} </span> <s>{{ \App\CPU\Helpers::currency_converter($dt_cart['price']*$dt_cart['quantity']) }} </s></p>
                                            <div class="varient">
                                            @foreach(json_decode($dt_cart['variations'],true) as $key1 =>$variation)
                                            
                                                <div class="fs-12"><b>{{$key1}}</b> : {{$variation}}</div>
                                            
                                            @endforeach
                                            </div>
                                            @if ($shippingMethod=='inhouse_shipping')
                                                <?php

                                                $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

                                                ?>
                                            @else
                                                <?php
                                                    if ($dt_cart->seller_is == 'admin') {
                                                        $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                                    } else {
                                                        $seller_shipping = \App\Model\ShippingType::where('seller_id', $dt_cart->seller_id)->first();
                                                        $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                                                    }
                                                ?>
                                            @endif
                                            @if ( $shipping_type != 'order_wise')
                                            <div class="fs-12">{{ translate('shipping_cost') }} : {{ \App\CPU\Helpers::currency_converter($dt_cart['shipping_cost']) }}</div>
                                            @endif
                                            <div class="d-none align-items-center pay-and-reward">
                                                <p>or Pay $100 + </p>
                                                <p><img src="{{ theme_asset('assets/images/product-detail-images/reward-icon.svg')}}" alt="">20</p>
                                            </div>

                                            <div
                                                class="d-flex align-items-center justify-content-between flex-wrap inside-cart-button">
                                                <div class="qunty">
                                                    @php($minimum_order=\App\CPU\ProductManager::get_product($dt_cart['product_id']))
                                                    <div class="quantity quantity--style-two d-inline-flex">
                                                        <span class="quantity__minus " onclick="updateCartQuantityList('{{ $minimum_order->minimum_order_qty ?? 1 }}', '{{$dt_cart['id']}}', '-1', '{{ isset($minimum_order->minimum_order_qty) && $dt_cart['quantity'] == $minimum_order->minimum_order_qty ? 'delete':'minus' }}')">
                                                            <i class="{{ $dt_cart['quantity'] == (isset($dt_cart->product->minimum_order_qty) ? $dt_cart->product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash' }}"></i>
                                                        </span>
                                                        <input type="text" class="quantity__qty" value="{{$dt_cart['quantity']}}" name="quantity[{{ $dt_cart['id'] }}]" id="cartQuantity{{$dt_cart['id']}}"
                                                            onchange="updateCartQuantityList('{{ $minimum_order->minimum_order_qty ?? 1 }}', '{{$dt_cart['id']}}', '0')" data-min="{{ isset($dt_cart->product->minimum_order_qty) ? $dt_cart->product->minimum_order_qty : 1 }}">
                                                        <span class="quantity__plus" onclick="updateCartQuantityList('{{ $minimum_order->minimum_order_qty ?? 1 }}', '{{$dt_cart['id']}}', '1')">
                                                            <i class="bi bi-plus"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-content">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="212" height="180" viewBox="0 0 212 180" fill="none">
                                    <path d="M145.085 113.742H59.956C58.8621 113.742 57.9327 112.956 57.7539 111.876L47.3585 49.2535C47.2513 48.6029 47.43 47.9451 47.859 47.4447C48.2808 46.9442 48.91 46.6582 49.5606 46.6582H155.473C156.131 46.6582 156.753 46.9442 157.175 47.4447C157.597 47.9451 157.783 48.61 157.675 49.2535L147.28 111.876C147.108 112.948 146.172 113.742 145.085 113.742ZM61.8506 109.274H143.19L152.842 51.1123H52.1987L61.8506 109.274Z" fill="#0CA3A3"/>
                                    <path d="M127.955 112.254H77.093C76.7141 112.254 76.3924 111.961 76.3495 111.582L70.1366 48.9596C70.1151 48.7522 70.1866 48.5449 70.3296 48.3876C70.4726 48.2303 70.6728 48.1445 70.8801 48.1445H134.168C134.375 48.1445 134.575 48.2303 134.718 48.3876C134.861 48.5449 134.925 48.7522 134.911 48.9596L128.698 111.582C128.655 111.961 128.334 112.254 127.955 112.254ZM77.7579 110.76H127.275L133.338 49.6245H71.6951L77.7579 110.76Z" fill="#0CA3A3"/>
                                    <path d="M111.232 112.253H93.8156C93.4153 112.253 93.0864 111.932 93.0721 111.531L90.9487 48.9089C90.9415 48.7087 91.0202 48.5085 91.156 48.3655C91.299 48.2225 91.4921 48.1367 91.6922 48.1367H113.362C113.563 48.1367 113.756 48.2154 113.899 48.3655C114.042 48.5085 114.113 48.7087 114.106 48.9089L111.983 111.531C111.961 111.932 111.632 112.253 111.232 112.253ZM94.5306 110.759H110.51L112.583 49.6238H92.4501L94.5306 110.759Z" fill="#0CA3A3"/>
                                    <path d="M142.447 149.26H68.7997C62.0934 149.26 56.6455 143.805 56.6455 137.105C56.6455 130.399 62.1006 124.951 68.7997 124.951H140.552L156.624 28.7762C156.803 27.6966 157.732 26.9102 158.826 26.9102H166.455C167.684 26.9102 168.685 27.9111 168.685 29.1408C168.685 30.3705 167.684 31.3714 166.455 31.3714H160.714L144.649 127.547C144.47 128.626 143.541 129.413 142.447 129.413H68.7997C64.56 129.413 61.1068 132.866 61.1068 137.105C61.1068 141.345 64.56 144.798 68.7997 144.798H142.447C143.676 144.798 144.677 145.799 144.677 147.029C144.677 148.259 143.676 149.26 142.447 149.26Z" fill="#0CA3A3"/>
                                    <path d="M80.3319 160.562C83.479 160.562 86.0301 158.011 86.0301 154.864C86.0301 151.717 83.479 149.166 80.3319 149.166C77.1849 149.166 74.6338 151.717 74.6338 154.864C74.6338 158.011 77.1849 160.562 80.3319 160.562Z" fill="#0CA3A3"/>
                                    <path d="M83.0913 154.865C83.0913 153.335 81.8545 152.098 80.3245 152.098C78.7945 152.098 77.5576 153.335 77.5576 154.865C77.5576 156.395 78.7945 157.631 80.3245 157.631C81.8545 157.631 83.0913 156.395 83.0913 154.865Z" fill="white"/>
                                    <path d="M127.254 160.562C130.401 160.562 132.952 158.011 132.952 154.864C132.952 151.717 130.401 149.166 127.254 149.166C124.107 149.166 121.556 151.717 121.556 154.864C121.556 158.011 124.107 160.562 127.254 160.562Z" fill="#0CA3A3"/>
                                    <path d="M127.254 157.631C128.782 157.631 130.021 156.393 130.021 154.865C130.021 153.336 128.782 152.098 127.254 152.098C125.726 152.098 124.487 153.336 124.487 154.865C124.487 156.393 125.726 157.631 127.254 157.631Z" fill="white"/>
                                    <path d="M152.499 69.1844H52.5707C52.156 69.1844 51.8271 68.8483 51.8271 68.4408C51.8271 68.0261 52.1632 67.6973 52.5707 67.6973H152.499C152.914 67.6973 153.243 68.0333 153.243 68.4408C153.243 68.8483 152.914 69.1844 152.499 69.1844Z" fill="#0CA3A3"/>
                                    <path d="M148.616 90.9422H56.4164C56.0017 90.9422 55.6729 90.6061 55.6729 90.1986C55.6729 89.7839 56.0089 89.4551 56.4164 89.4551H148.616C149.031 89.4551 149.36 89.7911 149.36 90.1986C149.36 90.6061 149.031 90.9422 148.616 90.9422Z" fill="#0CA3A3"/>
                                    <path d="M184.943 35.2754H124.723C123.479 35.2754 122.464 36.2835 122.464 37.5346C122.464 38.7787 123.472 39.7939 124.723 39.7939H184.943C186.187 39.7939 187.203 38.7858 187.203 37.5346C187.195 36.2906 186.187 35.2754 184.943 35.2754Z" fill="#E3E1EC"/>
                                    <path d="M114.671 35.2754H99.4712C98.2271 35.2754 97.2119 36.2835 97.2119 37.5346C97.2119 38.7787 98.22 39.7939 99.4712 39.7939H114.671C115.915 39.7939 116.93 38.7858 116.93 37.5346C116.93 36.2906 115.915 35.2754 114.671 35.2754Z" fill="#E3E1EC"/>
                                    <path d="M187.338 175.191H127.118C125.874 175.191 124.858 176.199 124.858 177.451C124.858 178.695 125.866 179.71 127.118 179.71H187.338C188.582 179.71 189.597 178.702 189.597 177.451C189.59 176.199 188.582 175.191 187.338 175.191Z" fill="#E3E1EC"/>
                                    <path d="M117.066 175.191H101.866C100.622 175.191 99.6064 176.199 99.6064 177.451C99.6064 178.695 100.615 179.71 101.866 179.71H117.066C118.31 179.71 119.325 178.702 119.325 177.451C119.325 176.199 118.31 175.191 117.066 175.191Z" fill="#E3E1EC"/>
                                    <path d="M2.56665 161.514H45.4279C46.3145 161.514 47.0366 160.792 47.0366 159.906C47.0366 159.019 46.3145 158.297 45.4279 158.297H2.56665C1.68011 158.297 0.958008 159.019 0.958008 159.906C0.958008 160.799 1.68011 161.514 2.56665 161.514Z" fill="#E3E1EC"/>
                                    <path d="M52.5842 161.514H63.4014C64.288 161.514 65.0101 160.792 65.0101 159.906C65.0101 159.019 64.288 158.297 63.4014 158.297H52.5842C51.6977 158.297 50.9756 159.019 50.9756 159.906C50.9756 160.799 51.6977 161.514 52.5842 161.514Z" fill="#E3E1EC"/>
                                    <path d="M148.989 169.694H191.85C192.736 169.694 193.458 168.972 193.458 168.085C193.458 167.199 192.736 166.477 191.85 166.477H148.989C148.102 166.477 147.38 167.199 147.38 168.085C147.38 168.972 148.095 169.694 148.989 169.694Z" fill="#E3E1EC"/>
                                    <path d="M199.006 169.694H209.823C210.71 169.694 211.432 168.972 211.432 168.085C211.432 167.199 210.71 166.477 209.823 166.477H199.006C198.12 166.477 197.397 167.199 197.397 168.085C197.397 168.972 198.112 169.694 199.006 169.694Z" fill="#E3E1EC"/>
                                    <path d="M103.31 0H73.2038C72.5818 0 72.0742 0.507616 72.0742 1.12962C72.0742 1.75163 72.5818 2.25925 73.2038 2.25925H103.31C103.932 2.25925 104.44 1.75163 104.44 1.12962C104.44 0.507616 103.94 0 103.31 0Z" fill="#E3E1EC"/>
                                    <path d="M68.1778 0H60.5779C59.9558 0 59.4482 0.507616 59.4482 1.12962C59.4482 1.75163 59.9558 2.25925 60.5779 2.25925H68.1778C68.7998 2.25925 69.3074 1.75163 69.3074 1.12962C69.3074 0.507616 68.7998 0 68.1778 0Z" fill="#E3E1EC"/>
                                    <path d="M82.7977 165.354H52.6911C52.0691 165.354 51.5615 165.861 51.5615 166.483C51.5615 167.105 52.0691 167.613 52.6911 167.613H82.7977C83.4197 167.613 83.9273 167.105 83.9273 166.483C83.9273 165.861 83.4197 165.354 82.7977 165.354Z" fill="#E3E1EC"/>
                                    <path d="M47.6651 165.354H40.0652C39.4432 165.354 38.9355 165.861 38.9355 166.483C38.9355 167.105 39.4432 167.613 40.0652 167.613H47.6651C48.2871 167.613 48.7947 167.105 48.7947 166.483C48.7947 165.861 48.2871 165.354 47.6651 165.354Z" fill="#E3E1EC"/>
                                </svg>
                                <h3>{{translate('Your_Cart_is_empty')}}</h3>
                                <a href="{{ route('products',['page'=>1]) }}" class="btn-login for-empty">{{translate('Shop_Now')}}</a>
                            </div>
                        </div>
                    @endif
                    
                </div>

                <div class="col-md-4">
                    @php($current_url=request()->segment(count(request()->segments())))
                    @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
                    @php($product_price_total=0)
                    @php($total_tax=0)
                    @php($total_shipping_cost=0)
                    @php($order_wise_shipping_discount=\App\CPU\CartManager::order_wise_shipping_discount())
                    @php($total_discount_on_product=0)
                    @php($cart = \App\Model\Cart::where(['cart_group_id' => session()->get('buy_now')])->get())
                    @php($cart_group_ids=\App\CPU\CartManager::get_cart_group_ids())
                    @php($shipping_cost=\App\CPU\CartManager::get_shipping_cost(session()->get('buy_now')))
                    @if($cart->count() > 0)
                        @foreach($cart as $key => $cartItem)
                            @php($product_price_total+=$cartItem['price']*$cartItem['quantity'])
                            @php($total_tax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
                            @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
                        @endforeach

                        @php($total_shipping_cost=$shipping_cost)
                        <div class="card bg-light-gery border-0 br-12 colorchnage sticky-top">
                            <div class="card-body ">
                                <div class="coin-abvilabe d-none">
                                    <div class="coin-img">
                                        <img src="images/Cart/coin-avilable.svg" alt="" class="">
                                    </div>
                                    <div class="form-check">
                                        <div class="d-flex gap-3">
                                            <input class="form-check-input me-2" type="checkbox" value="" id="defaultCheck1">
                                        </div>
                                        <label class="form-check-label black-text fw-600 mb-0" for="defaultCheck1">You have 250 coins available</label>
                                        <p class="text-muted mb-0">or Pay $100 + 20</p>
                                    </div>

                                </div>
                                <!-- <hr> -->

                                <div class="d-flex align-items-center justify-content-between mb-3 d-none">
                                    <p class="black-text fw-600 mb-0">Offer & Benefits</p> <a href="" class="ticket-img-content"><img
                                            src="{{ theme_asset('assets/images/Cart/Ticket.svg')}}" alt=""> View
                                        Offer</a>
                                </div>


                                <!-- <form class="d-flex mt-3">
                                    <div class="d-flex">
                                    <input class="form-control me-3" type="search" placeholder="Voucher Number"
                                        aria-label="Search">
                                    <button class="btn btn-primary px-4" type="button">Apply</button>
                                                </div>
                                </form> -->
                                @if(session()->has('coupon_discount'))
                                    @php($coupon_discount = session()->has('coupon_discount')?session('coupon_discount'):0)
                                    <div class="d-flex justify-content-between">
                                        <span class="cart_title">{{\App\CPU\translate('coupon_discount')}}</span>
                                        <span class="cart_value" id="coupon-discount-amount">
                                            - {{\App\CPU\Helpers::currency_converter($coupon_discount+$order_wise_shipping_discount)}}
                                        </span>
                                    </div>
                                    @php($coupon_dis=session('coupon_discount'))
                                @else
                                    <div class="pt-2">
                                        <form class="needs-validation " action="javascript:" method="post" novalidate id="coupon-code-ajax">
                                            <div class="form-group d-flex">
                                                <input class="form-control input_code" type="text" name="code" placeholder="{{\App\CPU\translate('Coupon code')}}"
                                                    required>
                                            

                                                <button class="btn btn--primary btn-block color__text__green" type="button" onclick="couponCode()">{{\App\CPU\translate('apply')}}
                                            </button>
                                            </div>
                                            <div class="invalid-feedback">{{\App\CPU\translate('please_provide_coupon_code')}}</div>
                                        
                                            <span id="coupon-apply" data-url="{{ route('coupon.apply') }}"></span>
                                        </form>
                                    </div>
                                    @php($coupon_dis=0)
                                @endif

                                <hr>

                                <p class="mb-3 fw-600"> Delivery Type</p>

                                @if($cart->count() > 0 && $shippingMethod=='inhouse_shipping1')
                                        <?php
                                        $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                        ?>
                                    @if ($shipping_type == 'order_wise')
                                        @php($shippings=\App\CPU\Helpers::get_shipping_methods(1,'admin'))
                                        @php($choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$dt_cart['cart_group_id']])->first())

                                        @if(isset($choosen_shipping)==false)
                                            @php($choosen_shipping['shipping_method_id']=0)
                                        @endif

                                        <div class="delivery-type">
                                            @foreach($shippings as $skey => $shipping)
                                                <div class="icon-visit mt-2">
                                                    <div class="form-check">
                                                        <input class="design-new-radio-btn" type="radio" name="{{$shipping['id']}}"
                                                            id="{{$shipping['id']}}" value="{{$shipping['id']}}"
                                                            {{$choosen_shipping['shipping_method_id']==$shipping['id']?'checked': '' }}
                                                            {{$choosen_shipping['shipping_method_id']==0&&$skey==0?'checked':''}}
                                                            onchange="set_shipping_id(this.value,'all_cart_group')">
                                                        <label class="form-check-label fw-600" for="{{$shipping['id']}}">
                                                            <small>{{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}</small>
                                                        </label>
                                                    </div>
                                                </div>

                                            @endforeach
                                        </div>

                                    @endif
                                @endif

                                <hr>
                                <p class="mb-3 fw-600">Price Detail</p>

                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-muted">Price ({{$cart->count()}} items)</p>
                                    <p class="black-text fw-700">{{\App\CPU\Helpers::currency_converter($product_price_total - $total_discount_on_product)}}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-muted">Delivery fee</p>
                                    <p class="black-text fw-700">{{\App\CPU\Helpers::currency_converter($total_shipping_cost)}}</p>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <div>{{ translate('tax') }}</div>
                                    <div>{{\App\CPU\Helpers::currency_converter($total_tax)}}</div>
                                </div>
                                <!-- <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-muted mb-0">{{ translate('Product_Discount') }}</p>
                                    <p class="black-text fw-700 mb-0">{{\App\CPU\Helpers::currency_converter($total_discount_on_product)}}</p>
                                </div> -->
                                @if(session()->has('coupon_discount'))
                                    @php($coupon_discount = session()->has('coupon_discount')?session('coupon_discount'):0)
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <div>{{ translate('coupon_discount') }}</div>
                                        <div class="text-primary">- {{\App\CPU\Helpers::currency_converter($coupon_discount+$order_wise_shipping_discount)}}</div>
                                    </div>
                                    @php($coupon_dis=session('coupon_discount'))
                                @endif
                                <hr>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="fw-600">Total Amount</p>
                                    <p class="black-text fw-700 text-light-green">{{\App\CPU\Helpers::currency_converter($product_price_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</p>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-4">
                                    <a href="{{ route('home') }}" class="btn-save-later"><i class="bi bi-chevron-double-left fs-10"></i> {{ translate('Continue_Shopping') }}</a>
                                    @if(!Request::is('checkout-payment'))
                                        <a {{ Request::is('buy-cart')?"onclick=checkout()":"" }} {{ Request::is('checkout-details')?"onclick=proceed_to_next()":"" }} {{ in_array($current_url, ["updateQuantity", "remove"]) ? "onclick=checkout()":"" }} class="proceed-to-buy">{{ translate('Proceed_to_Next') }}</a>
                                    @endif
                                </div>
                                <!-- <a href="cart-check-out.php" class="proceed-to-buy">Proceed to buy</a> -->
                            </div>
                        </div>
                    @else
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="row gy-3 d-none">
        <!-- Order summery Content -->
        {{-- @include('theme-views.partials._order-summery') --}}
    </div>
    
    <!-- card slider -->
    <section class="card-section mt-md-5">
        
    </section>

</div>

@push('script')
<script>
    cartQuantityInitialize();

    function set_shipping_id(id, cart_group_id) {
        $.get({
            url: '{{url('/')}}/customer/set-shipping-method',
            dataType: 'json',
            data: {
                id: id,
                cart_group_id: cart_group_id
            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                location.reload();
            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }

    function updateCartQuantityList(minimum_order_qty, key, incr, e) {
        let quantity = parseInt($("#cartQuantity" + key).val())+parseInt(incr);
        let ex_quantity = $("#cartQuantity" + key);
        if(minimum_order_qty > quantity && e != 'delete' ) {
            swal.fire('{{translate("minimum_order_quantity_cannot_be_less_than_")}}' + minimum_order_qty, '', 'error')
            $("#cartQuantity" + key).val(minimum_order_qty);
            return false;
        }

        if (ex_quantity.val() == ex_quantity.data('min') && e == 'delete') {
            $.post("{{ route('cart.remove') }}", {
                _token: '{{ csrf_token() }}',
                key: key
            },
            function (response) {
                updateNavCart();
                swal.fire("{{translate('Item has been removed from cart ')}}", '', 'info')
                
                let segment_array = window.location.pathname.split('/');
                let segment = segment_array[segment_array.length - 1];
                if (segment === 'checkout-payment' || segment === 'checkout-details') {
                    location.reload();
                }
                $('#cart-summary').empty().html(response.data);
            });
        }else{
            $.post('{{route('cart.updateQuantity')}}', {
                _token: '{{csrf_token()}}',
                key,
                quantity
            }, function (response) {
                if (response.status == 0) {

                    swal.fire(`${response.message}`, '', 'error')

                    // toastr.error(response.message, {
                    //     CloseButton: true,
                    //     ProgressBar: true
                    // });
                    $("#cartQuantity" + key).val(response['qty']);
                } else {
                    if (response['qty'] == ex_quantity.data('min')) {
                        ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-trash3-fill text-danger fs-10"></i>')
                    } else {
                        ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-dash"></i>')
                    }
                    updateNavCart();
                    console.log('response ', response)
                    $('#cart-summary').empty().html(response);
                }
            });
        }
    }

    function checkout() {
        let order_note = $('#order_note').val();
        let shipping_method = $('#order_note').val();
        //console.log(order_note);
        $.post({
            url: "{{route('order_note')}}?group_id=" + "{{ session()->get('buy_now') }}",
            data: {
                _token: '{{csrf_token()}}',
                order_note: order_note,

            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                let url = "{{ route('checkout-details') }}?group_id=" + "{{ session()->get('buy_now') }}";
                location.href = url;

            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }
</script>
@endpush
