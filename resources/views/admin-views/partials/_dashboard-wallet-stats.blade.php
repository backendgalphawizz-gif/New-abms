

<div class="col-lg-4">
    <!-- Card -->
    <div class="card h-100 d-flex justify-content-center align-items-center">
        <div class="card-body d-flex flex-column gap-10 align-items-center justify-content-center">
            <img width="48" class="mb-2" src="{{asset('/public/assets/back-end/img/inhouse-earning.png')}}" alt="">
           
            <!-- <h3 class="for-card-count mb-0 fz-24">$ {{ (\App\CPU\BackEndHelper::usd_to_currency($data['total_earning']))}}<h3> -->
            <h3 class="for-card-count mb-0 fz-24">${{ $data['total_earning'] ?? 0 }}</h3>
            <div class="text-capitalize mb-30">
                {{\App\CPU\translate('Total Earning')}}
            </div>
        </div>
    </div>
    <!-- End Card -->
</div>
<div class="col-lg-4">
    <!-- Card -->
    <div class="card h-100 d-flex justify-content-center align-items-center">
        <div class="card-body d-flex flex-column gap-10 align-items-center justify-content-center">
            <img width="48" class="mb-2" src="{{asset('/public/assets/back-end/img/pa.png')}}" alt="">
            <h3 class="mb-1 fz-24">${{ $data['pending_amount'] ?? 0}}</h3>
            <div class="text-capitalize mb-0">{{\App\CPU\translate('pending_amount')}}</div>
        </div>
    </div>
    <!-- End Card -->
</div>

