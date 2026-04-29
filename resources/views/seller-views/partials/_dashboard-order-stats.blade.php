<div class="col-sm-6 col-lg-3 col-6">
    <!-- Card -->
    <a class="order-stats order-stats_pending carddivsec" href="{{route('seller.orders.list',['pending'])}}">
        <div class="order-stats__content">
            <div class="sellericon">
            <img src="{{asset('/public/assets/back-end/img/pending-icon.png')}}" alt="" class="">
            </div>
            <div class="cardtext">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('pending')}}</h6>
            <span class="order-stats__title">{{$data['pending']}}</span>
            </div>
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 col-6">
    <!-- Card -->
    <a class="order-stats order-stats_confirmed carddivsec" href="{{route('seller.orders.list',['confirmed'])}}">
        <div class="order-stats__content">
          <div class="confirmicon">
            <img src="{{asset('/public/assets/back-end/img/check.png')}}" alt="" class="">
            </div>
              <div class="cardtext">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('Confirmed')}}</h6>
            <span class="order-stats__title">{{$data['confirmed']}}</span>
              </div>
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 col-6 d-none">
    <!-- Card -->
    <a class="order-stats order-stats_packaging carddivsec" href="{{route('seller.orders.list',['processing'])}}">
        <div class="order-stats__content">
          <div class="packingicon">
            <img src="{{asset('/public/assets/back-end/img/packing-icon.png')}}" alt="" class="">
            </div>
              <div class="cardtext">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('packaging')}}</h6>
            <span class="order-stats__title">{{$data['processing']}}</span>
              </div>
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 col-6">
   
    <a class="order-stats order-stats_packaging carddivsec" href="{{route('seller.orders.list',['shipped'])}}">
        <div class="order-stats__content">
          <div class="packingicon">
            <img src="{{asset('/public/assets/back-end/img/packing-icon.png')}}" alt="" class="">
            </div>
              <div class="cardtext">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('Shipped')}}</h6>
            <span class="order-stats__title">{{$data['shipped']}}</span>
              </div>
        </div>
    </a>
   
</div>
<div class="col-sm-6 col-lg-3 col-6">
    <!-- Card -->
    <a class="order-stats order-stats_out-for-delivery carddivsec" href="{{route('seller.orders.list',['out_for_delivery'])}}">
        <div class="order-stats__content">
              <div class="outdelivered">
            <img src="{{asset('/public/assets/back-end/img/out-delivered.png')}}" alt="" class="">
            </div>
              <div class="cardtext">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('Out_For_Delivery')}}</h6>
            <span class="order-stats__title">{{$data['out_for_delivery']}}</span>
              </div>
        </div>
    </a>
    <!-- End Card -->
</div>


<div class="col-sm-6 col-lg-3 col-6">
    <!-- Card -->
    <a class="order-stats order-stats_delivered carddivsec" href="{{route('seller.orders.list',['delivered'])}}">
        <div class="order-stats__content">
            <div class="deliveredicon">
            <img src="{{asset('/public/assets/back-end/img/deliveredicon.png')}}" alt="" class="">
            </div>
              <div class="cardtext">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('delivered')}}</h6>
            <span class="order-stats__title">{{$data['delivered']}}</span>
              </div>
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 col-6">
    <!-- Card -->
    <a class="order-stats order-stats_canceled carddivsec" href="{{route('seller.orders.list',['canceled'])}}">
        <div class="order-stats__content">
            <div class="cancelicon">
            <img src="{{asset('/public/assets/back-end/img/close.png')}}" alt="" class="">
            </div>
              <div class="cardtext">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('canceled')}}</h6>
            <span class="order-stats__title">{{$data['canceled']}}</span>
              </div>
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 col-6">
    <!-- Card -->
    <a class="order-stats order-stats_returned carddivsec" href="{{route('seller.orders.list',['returned'])}}">
        <div class="order-stats__content">
          <div class="returnicon">
            <img src="{{asset('/public/assets/back-end/img/returnicon.png')}}" alt="" class="">
            </div>
              <div class="cardtext">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('returned')}}</h6>
            <span class="order-stats__title">{{$data['returned']}}</span>
              </div>
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 col-6 ">
    <!-- Card -->
    <a class="order-stats order-stats_failed carddivsec" href="{{route('seller.orders.list',['failed'])}}">
        <div class="order-stats__content">
          <div class="failedicon">
            <img src="{{asset('/public/assets/back-end/img/failed.png')}}" alt="" class="">
            </div>
              <div class="cardtext">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('Failed_To_Delivery')}}</h6>
            <span class="order-stats__title">{{$data['failed']}}</span>
              </div>
        </div>
    </a>
    <!-- End Card -->
</div>
