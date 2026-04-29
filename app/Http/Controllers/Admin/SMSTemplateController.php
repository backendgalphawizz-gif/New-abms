<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\SMSTemplate;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SMSTemplateController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'title.required' => 'Title is Required!',
            'subject.required' => ' Subject is Required!',
            'message.required' => 'Message is Required!',

        ]);
        $contact = new SMSTemplate;
        $contact->title = $request->title;
        $contact->slug = strtolower(str_replace(' ', '-', $request->slug));
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        return response()->json(['status' => true, 'message' => 'Template saved success']);
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $contacts = SMSTemplate::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%")->orWhere('subject', 'like', "%{$value}%")->orWhere('message', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $contacts = new SMSTemplate();
        }
        $contacts = $contacts->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.sms-templates.list', compact('contacts','search'));

    }

    public function view($id) {
        $contact = SMSTemplate::findOrFail($id);
        return view('admin-views.sms-templates.view', compact('contact'));
    }

    public function create(Request $request) {
        return view('admin-views.sms-templates.create');
    }

    public function update(Request $request, $id)
    {
        $contact = SMSTemplate::find($id);
        $contact->feedback = $request->feedback;
        $contact->seen = 1;
        $contact->update();
        Toastr::success('Feedback  Update successfully!');
        return redirect()->route('admin.contact.list');
    }

    public function destroy(Request $request)
    {
        $contact = SMSTemplate::find($request->id);
        $contact->delete();

        return response()->json();
    }

    public function send_mail(Request $request, $id)
    {
        $contact = SMSTemplate::findOrFail($id);
        $data = array('body' => $request['mail_body']);
        Mail::send('sms-templates.customer-message', $data, function ($message) use ($contact, $request) {
            $message->to($contact['email'], BusinessSetting::where(['type' => 'company_name'])->first()->value)
                ->subject($request['subject']);
        });

        SMSTemplate::where(['id' => $id])->update([
            'reply' => json_encode([
                'subject' => $request['subject'],
                'body' => $request['mail_body']
            ])
        ]);

        Toastr::success('Mail sent successfully!');
        return back();
    }
}
