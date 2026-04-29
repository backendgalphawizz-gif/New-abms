@php
    $shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method');
    $cart = \App\Model\Cart::where(['customer_id' => auth('customer')->id()])->get()->groupBy('cart_group_id');
    $carts = \App\Model\Cart::where(['customer_id' => auth('customer')->id()])->get();
    $savelists = \App\Model\Savelist::where(['customer_id' => auth('customer')->id()])->get();
    $coupon_dis = 0;
@endphp
<div class="container-fluid productcontent">
    <h4 class="d-none">{{ translate('Cart_List') }}</h4>

    <section class="">
        <div class="">
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
                                                <div class="btn btn-flex-lep">
                                                    <a href="javascript:void(0)" class="btn-save-later save-later-product" data-productid="{{ $dt_cart['product_id'] }}">Save for later</a> 
                                                    <a href="javascript:void(0)" class="btn-save-later" onclick="updateCartQuantityList('1', '{{$dt_cart['id']}}', '-1', 'delete')">Remove from cart</a> 
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
                            <svg width="212" height="180" viewBox="0 0 212 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_668_407)">
                                <path d="M145.085 113.742H59.956C58.8621 113.742 57.9327 112.956 57.7539 111.876L47.3585 49.2535C47.2513 48.6029 47.43 47.9451 47.859 47.4447C48.2808 46.9442 48.91 46.6582 49.5606 46.6582H155.473C156.131 46.6582 156.753 46.9442 157.175 47.4447C157.597 47.9451 157.783 48.61 157.675 49.2535L147.28 111.876C147.108 112.948 146.172 113.742 145.085 113.742ZM61.8506 109.274H143.19L152.842 51.1123H52.1987L61.8506 109.274Z" fill="#B81C18"/>
                                <path d="M127.955 112.254H77.093C76.7141 112.254 76.3924 111.961 76.3495 111.582L70.1366 48.9596C70.1151 48.7522 70.1866 48.5449 70.3296 48.3876C70.4726 48.2303 70.6728 48.1445 70.8801 48.1445H134.168C134.375 48.1445 134.575 48.2303 134.718 48.3876C134.861 48.5449 134.925 48.7522 134.911 48.9596L128.698 111.582C128.655 111.961 128.334 112.254 127.955 112.254ZM77.7579 110.76H127.275L133.338 49.6245H71.6951L77.7579 110.76Z" fill="#B81C18"/>
                                <path d="M111.233 112.253H93.8166C93.4163 112.253 93.0874 111.932 93.0731 111.531L90.9497 48.9089C90.9425 48.7087 91.0212 48.5085 91.157 48.3655C91.3 48.2225 91.4931 48.1367 91.6932 48.1367H113.363C113.564 48.1367 113.757 48.2154 113.9 48.3655C114.043 48.5085 114.114 48.7087 114.107 48.9089L111.984 111.531C111.962 111.932 111.633 112.253 111.233 112.253ZM94.5316 110.759H110.511L112.584 49.6238H92.4511L94.5316 110.759Z" fill="#B81C18"/>
                                <path d="M142.446 149.26H68.7987C62.0924 149.26 56.6445 143.805 56.6445 137.105C56.6445 130.399 62.0996 124.951 68.7987 124.951H140.551L156.623 28.7762C156.802 27.6966 157.731 26.9102 158.825 26.9102H166.454C167.683 26.9102 168.684 27.9111 168.684 29.1408C168.684 30.3705 167.683 31.3714 166.454 31.3714H160.713L144.648 127.547C144.469 128.626 143.54 129.413 142.446 129.413H68.7987C64.559 129.413 61.1058 132.866 61.1058 137.105C61.1058 141.345 64.559 144.798 68.7987 144.798H142.446C143.675 144.798 144.676 145.799 144.676 147.029C144.676 148.259 143.675 149.26 142.446 149.26Z" fill="#B81C18"/>
                                <path d="M80.3329 160.562C83.48 160.562 86.0311 158.011 86.0311 154.864C86.0311 151.717 83.48 149.166 80.3329 149.166C77.1859 149.166 74.6348 151.717 74.6348 154.864C74.6348 158.011 77.1859 160.562 80.3329 160.562Z" fill="#B81C18"/>
                                <path d="M83.0903 154.865C83.0903 153.335 81.8535 152.098 80.3235 152.098C78.7935 152.098 77.5566 153.335 77.5566 154.865C77.5566 156.395 78.7935 157.631 80.3235 157.631C81.8535 157.631 83.0903 156.395 83.0903 154.865Z" fill="#FFF9F9"/>
                                <path d="M127.255 160.562C130.402 160.562 132.953 158.011 132.953 154.864C132.953 151.717 130.402 149.166 127.255 149.166C124.108 149.166 121.557 151.717 121.557 154.864C121.557 158.011 124.108 160.562 127.255 160.562Z" fill="#B81C18"/>
                                <path d="M127.253 157.631C128.781 157.631 130.02 156.393 130.02 154.865C130.02 153.336 128.781 152.098 127.253 152.098C125.725 152.098 124.486 153.336 124.486 154.865C124.486 156.393 125.725 157.631 127.253 157.631Z" fill="#FFF9F9"/>
                                <path d="M152.498 69.1844H52.5698C52.1551 69.1844 51.8262 68.8483 51.8262 68.4408C51.8262 68.0261 52.1623 67.6973 52.5698 67.6973H152.498C152.913 67.6973 153.242 68.0333 153.242 68.4408C153.242 68.8483 152.913 69.1844 152.498 69.1844Z" fill="#B81C18"/>
                                <path d="M148.617 90.9422H56.4173C56.0026 90.9422 55.6738 90.6061 55.6738 90.1986C55.6738 89.7839 56.0098 89.4551 56.4173 89.4551H148.617C149.032 89.4551 149.361 89.7911 149.361 90.1986C149.361 90.6061 149.032 90.9422 148.617 90.9422Z" fill="#B81C18"/>
                                <path d="M184.944 35.2754H124.724C123.48 35.2754 122.465 36.2835 122.465 37.5346C122.465 38.7787 123.473 39.7939 124.724 39.7939H184.944C186.188 39.7939 187.204 38.7858 187.204 37.5346C187.196 36.2906 186.188 35.2754 184.944 35.2754Z" fill="#FFF9F9"/>
                                <path d="M114.67 35.2754H99.4702C98.2261 35.2754 97.2109 36.2835 97.2109 37.5346C97.2109 38.7787 98.219 39.7939 99.4702 39.7939H114.67C115.914 39.7939 116.929 38.7858 116.929 37.5346C116.929 36.2906 115.914 35.2754 114.67 35.2754Z" fill="#FFF9F9"/>
                                <path d="M187.337 175.191H127.117C125.873 175.191 124.857 176.199 124.857 177.451C124.857 178.695 125.865 179.71 127.117 179.71H187.337C188.581 179.71 189.596 178.702 189.596 177.451C189.589 176.199 188.581 175.191 187.337 175.191Z" fill="#FFF9F9"/>
                                <path d="M117.065 175.191H101.865C100.621 175.191 99.6055 176.199 99.6055 177.451C99.6055 178.695 100.614 179.71 101.865 179.71H117.065C118.309 179.71 119.324 178.702 119.324 177.451C119.324 176.199 118.309 175.191 117.065 175.191Z" fill="#FFF9F9"/>
                                <path d="M2.56763 161.514H45.4289C46.3155 161.514 47.0376 160.792 47.0376 159.906C47.0376 159.019 46.3155 158.297 45.4289 158.297H2.56763C1.68109 158.297 0.958984 159.019 0.958984 159.906C0.958984 160.799 1.68109 161.514 2.56763 161.514Z" fill="#FFF9F9"/>
                                <path d="M52.5852 161.514H63.4024C64.289 161.514 65.0111 160.792 65.0111 159.906C65.0111 159.019 64.289 158.297 63.4024 158.297H52.5852C51.6987 158.297 50.9766 159.019 50.9766 159.906C50.9766 160.799 51.6987 161.514 52.5852 161.514Z" fill="#FFF9F9"/>
                                <path d="M148.99 169.694H191.851C192.737 169.694 193.459 168.972 193.459 168.085C193.459 167.199 192.737 166.477 191.851 166.477H148.99C148.103 166.477 147.381 167.199 147.381 168.085C147.381 168.972 148.096 169.694 148.99 169.694Z" fill="#FFF9F9"/>
                                <path d="M199.005 169.694H209.822C210.709 169.694 211.431 168.972 211.431 168.085C211.431 167.199 210.709 166.477 209.822 166.477H199.005C198.119 166.477 197.396 167.199 197.396 168.085C197.396 168.972 198.111 169.694 199.005 169.694Z" fill="#FFF9F9"/>
                                <path d="M103.31 0H73.2038C72.5818 0 72.0742 0.507616 72.0742 1.12962C72.0742 1.75163 72.5818 2.25925 73.2038 2.25925H103.31C103.932 2.25925 104.44 1.75163 104.44 1.12962C104.44 0.507616 103.94 0 103.31 0Z" fill="#FFF9F9"/>
                                <path d="M68.1769 0H60.577C59.9549 0 59.4473 0.507616 59.4473 1.12962C59.4473 1.75163 59.9549 2.25925 60.577 2.25925H68.1769C68.7989 2.25925 69.3065 1.75163 69.3065 1.12962C69.3065 0.507616 68.7989 0 68.1769 0Z" fill="#FFF9F9"/>
                                <path d="M82.7967 165.354H52.6901C52.0681 165.354 51.5605 165.861 51.5605 166.483C51.5605 167.105 52.0681 167.613 52.6901 167.613H82.7967C83.4187 167.613 83.9263 167.105 83.9263 166.483C83.9263 165.861 83.4187 165.354 82.7967 165.354Z" fill="#FFF9F9"/>
                                <path d="M47.6651 165.354H40.0652C39.4432 165.354 38.9355 165.861 38.9355 166.483C38.9355 167.105 39.4432 167.613 40.0652 167.613H47.6651C48.2871 167.613 48.7947 167.105 48.7947 166.483C48.7947 165.861 48.2871 165.354 47.6651 165.354Z" fill="#FFF9F9"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_668_407">
                                <rect width="212" height="180" fill="white"/>
                                </clipPath>
                                </defs>
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
                    @php($cart=\App\CPU\CartManager::get_cart())
                    @php($cart_group_ids=\App\CPU\CartManager::get_cart_group_ids())
                    @php($shipping_cost=\App\CPU\CartManager::get_shipping_cost())
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
                                @php($subscription=\App\Model\SellerSubscription::where(['seller_id' => auth('customer')->user()->id, 'type' => 1])->where('expiry_date', '>=', date('Y-m-d'))->first())
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
                                                            @if(!empty($subscription))
                                                                <small>{{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter(0)}}</small>
                                                            @else
                                                                <small>{{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}</small>
                                                            @endif
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
                                    <p class="text-muted">MRP (1 items)</p>
                                    <p class="black-text fw-700">{{\App\CPU\Helpers::currency_converter($product_price_total)}}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-muted">Delivery fee</p>
                                    <p class="black-text fw-700">{{\App\CPU\Helpers::currency_converter($total_shipping_cost)}}</p>
                                </div>
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <div>{{ translate('tax') }}</div>
                                    <div>{{\App\CPU\Helpers::currency_converter($total_tax)}}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-muted mb-0">{{ translate('Product_Discount') }}</p>
                                    <p class="black-text fw-700 mb-0">{{\App\CPU\Helpers::currency_converter($total_discount_on_product)}}</p>
                                </div>
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
                                    <a {{ Request::is('shop-cart')?"onclick=checkout()":"" }} {{ Request::is('checkout-details')?"onclick=proceed_to_next()":"" }} {{ in_array($current_url, ["updateQuantity", "remove"]) ? "onclick=checkout()":"" }} class="proceed-to-buy">{{ translate('Proceed_to_Next') }}</a>
                                    @endif
                                </div>
                                <!-- <a href="cart-check-out.php" class="proceed-to-buy">Proceed to buy</a> -->
                            </div>
                        </div>
                    @else
                    @endif
                </div>

                
                <div class="col-lg-12">
                    <div class="">
                        <h4 class="heading-tore">Saved Items </h4>
                        @if($savelists->isNotEmpty())
                            <div class="owl-carousel cart-item-carousel owl-theme">
                                @foreach($savelists as $save)
                                    <div class="item">
                                        <div class="hero-banner-images">
                                            <div class="card view-card-item-white-theme">

                                                <div class="images-item-white-theme imgset">
                                                    <img onerror="this.src='{{ theme_asset('assets/img/image-place-holder.png') }}'"
                                                        src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$save->product->thumbnail}}"
                                                        class="me-4 cart-list-image" alt="">
                                                </div>
                                                <div class="item-detail-cart">

                                                    <div class="d-flex justify-content-between  align-items-center cartlike-height-moblie">
                                                        <div class="item-offspan">
                                                            <span><i class="fa-solid fa-star"></i>
                                                                <p class="black-text mb-0">4.3</p>
                                                            </span>
                                                        </div>
                                                    
                                                    </div>

                                                    <div class="content-item-cart">
                                                        <p class="name-item black-text">{{ $save->product->name ?? "" }}</p>
                                                        @php($disount = $save->product->discount_type == 'flat' ? $save->product->discount : (($save->product->unit_price*$save->product->discount) / 100))
                                                        <p class="item-bill"><span>{{ \App\CPU\BackEndHelper::usd_to_currency($save->product->unit_price - $disount) }} </span> <s>{{ \App\CPU\BackEndHelper::usd_to_currency($save->product->unit_price) }}</s></p>
                                                        <div class="d-flex">
                                                            <a href="javascript:void(0)" class="btn-add-item white-theme-btn w-50 me-2 remove-save-later" data-productid="{{ $save->product->id }}">Remove</a>
                                                            <a href="{{route('product', $save->product->slug)}}" class="btn-add-cart2">View</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <h5>{{ translate('Saved items list empty!') }}</h5>
                        @endif
                    </div>
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
            url: "{{route('order_note')}}",
            data: {
                _token: '{{csrf_token()}}',
                order_note: order_note,

            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                let url = "{{ route('checkout-details') }}";
                location.href = url;

            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }
</script>
@endpush
