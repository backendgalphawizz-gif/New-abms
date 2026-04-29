<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\VendorHelpTopic;
use App\Model\Product;
use App\Model\SocialMedia;
use Brian2694\Toastr\Facades\Toastr;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpseclib3\Crypt\RSA\Formats\Keys\JWK;
use Carbon\Carbon;

class BusinessSettingsController extends Controller
{
      public function vendor_page($page)
    {
        $pages = array(
            'vendor-privacy-policy',
            'vendor-refund-policy',
            'vendor-terms-policy',
            'vendor-return-policy',
            'vendor-cancellation-policy',
            'vendor-shipping-policy',
            'vendor-security-policy-policy',
            'vendor-payment-policy',
            'vendor-condition-of-use-policy',
            'vendor-security-information',
            'vendor-product-listing-policy',
            'vendor-commission-and-fees-policy',
            'vendor-order-fulfillment-policy',
            'vendor-communication-policy'
        );

        if(in_array($page, $pages)){
            $data = BusinessSetting::where('type', $page)->first();
            return view('seller-views.business-settings.vendor-page', compact('page', 'data'));
        }

        Toastr::error('invalid_page');
        return redirect()->back();
    }

    function list($page = null) {
    $helps = VendorHelpTopic::latest()->get();

    $pages = [
        'vendor-privacy-policy',
        'vendor-refund-policy',
        'vendor-terms-policy',
        'vendor-return-policy',
        'vendor-cancellation-policy',
        'vendor-shipping-policy',
        'vendor-security-policy-policy',
        'vendor-payment-policy',
        'vendor-condition-of-use-policy',
        'vendor-security-information',
        'vendor-product-listing-policy',
        'vendor-commission-and-fees-policy',
        'vendor-order-fulfillment-policy',
        'vendor-communication-policy'
    ];

  if (in_array($page, $pages)) {
        $data = BusinessSetting::where('type', $page)->first();
    } else {
        $data = null;
    }

    return view('seller-views.vendor-help-topics.list', compact('helps', 'page', 'data'));
}
}