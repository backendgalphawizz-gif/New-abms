<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\VendorHelpTopic;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class VendorHelpTopicController extends Controller
{
    public function add_new()
    {

        return view('admin-views.vendor-help-topics.add-new');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer'   => 'required',
            'ranking'   => 'required',
        ], [
            'question.required' => 'Question name is required!',
            'answer.required'   => 'Question answer is required!',

        ]);
        $helps = new VendorHelpTopic;
        $helps->question = $request->question;
        $helps->answer = $request->answer;
        $request->has('status')? $helps->status = 1 : $helps->status = 0;
        $helps->ranking = $request->ranking;
        $helps->save();

        Toastr::success('FAQ added successfully!');
        return back();
    }
    public function status($id)
    {

        $helps = VendorHelpTopic::findOrFail($id);
        if ($helps->status == 1) {
            $helps->update(["status" => 0]);

        } else {
            $helps->update(["status" => 1]);

        }
        return response()->json(['success' => 'Status Change']);

    }
    public function edit($id)
    {
        $helps = VendorHelpTopic::findOrFail($id);
        return response()->json($helps);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'answer'   => 'required',
        ], [
            'question.required' => 'Question name is required!',
            'answer.required'   => 'Question answer is required!',

        ]);
        $helps = VendorHelpTopic::find($id);
        $helps->question = $request->question;
        $helps->answer = $request->answer;
        $helps->ranking = $request->ranking;
        $helps->update();
        Toastr::success('FAQ Update successfully!');
        return back();
    }

    function list() {
        $helps = VendorHelpTopic::latest()->get();
        return view('admin-views.vendor-help-topics.list', compact('helps'));
    }

    public function destroy(Request $request)
    {

        $helps = VendorHelpTopic::find($request->id);
        $helps->delete();
        return response()->json();
    }

}
