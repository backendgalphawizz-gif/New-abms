<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\VendorHelpTopic;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class VendorHelpTopicController extends Controller
{
    

    function list() {
        $helps = VendorHelpTopic::latest()->get();
        return view('seller-views.vendor-help-topics.list', compact('helps'));
    }


}
