<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\EmailTemplate;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
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
        $contact = new EmailTemplate;
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
            $contacts = EmailTemplate::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%")->orWhere('subject', 'like', "%{$value}%")->orWhere('message', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $contacts = new EmailTemplate();
        }
        $contacts = $contacts->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.email-templates.list', compact('contacts','search'));

    }

    public function view($id) {
        $contact = EmailTemplate::findOrFail($id);
        return view('admin-views.email-templates.view', compact('contact'));
    }

    public function create(Request $request) {
        return view('admin-views.email-templates.create');
    }

    public function update(Request $request, $id)
    {
        $contact = EmailTemplate::find($id);
        $contact->feedback = $request->feedback;
        $contact->seen = 1;
        $contact->update();
        Toastr::success('Feedback  Update successfully!');
        return redirect()->route('admin.contact.list');
    }

    public function destroy(Request $request)
    {
        $contact = EmailTemplate::find($request->id);
        $contact->delete();

        return response()->json();
    }

    public function send_mail(Request $request, $id)
    {
        $contact = EmailTemplate::findOrFail($id);
        $data = array('body' => $request['mail_body']);
        Mail::send('email-templates.customer-message', $data, function ($message) use ($contact, $request) {
            $message->to($contact['email'], BusinessSetting::where(['type' => 'company_name'])->first()->value)
                ->subject($request['subject']);
        });

        EmailTemplate::where(['id' => $id])->update([
            'reply' => json_encode([
                'subject' => $request['subject'],
                'body' => $request['mail_body']
            ])
        ]);

        Toastr::success('Mail sent successfully!');
        return back();
    }
}
