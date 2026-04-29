<div class="inline-page-menu my-4">
    <ul class="list-unstyled">

        <li class="{{ Request::is('seller/business-settings/vendor-page/vendor-privacy-policy') ?'active':'' }}"><a href="{{route('seller.business-settings.vendor.page',['vendor-privacy-policy'])}}">{{\App\CPU\translate('privacy_policy')}}</a></li>
        <li class="{{ Request::is('seller/business-settings/vendor-page/vendor-terms-policy') ?'active':'' }}"><a href="{{route('seller.business-settings.vendor.page',['vendor-terms-policy'])}}">{{\App\CPU\translate('Terms_and_condition')}}</a></li>
        <li class="{{ Request::is('seller/business-settings/list') ?'active':'' }}"><a href="{{route('seller.business-settings.list')}}">{{\App\CPU\translate('FAQ')}}</a></li>
        <!-- <li class="{{ Request::is('seller/business-settings/vendor-page/vendor-refund-policy') ?'active':'' }}"><a href="{{route('seller.business-settings.vendor.page',['vendor-refund-policy'])}}">{{\App\CPU\translate('refund_policy')}}</a></li>
        <li class="{{ Request::is('seller/business-settings/vendor-page/vendor-return-policy') ?'active':'' }}"><a href="{{route('seller.business-settings.vendor.page',['vendor-return-policy'])}}">{{\App\CPU\translate('return_policy')}}</a></li>
        <li class="{{ Request::is('seller/business-settings/vendor-page/vendor-cancellation-policy') ?'active':'' }}"><a href="{{route('seller.business-settings.vendor.page',['vendor-cancellation-policy'])}}">{{\App\CPU\translate('cancellation_policy')}}</a></li>
        <li class="{{ Request::is('seller/business-settings/vendor-page/vendor-shipping-policy') ?'active':'' }}"><a href="{{route('seller.business-settings.vendor.page',['vendor-shipping-policy'])}}">{{\App\CPU\translate('shipping_policy')}}</a></li>
        -->

    </ul>
</div>
