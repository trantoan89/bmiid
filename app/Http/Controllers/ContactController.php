<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedBack;
 
class ContactController extends Controller
{
    /**
     * Send feedback to email address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:50',
            'name' => 'required|max:50',
            'message' => 'required|max:500',
        ]);
        
        Mail::to(env('MAIL_FEEDBACK'))->send(new FeedBack($validated));
        
        return redirect()->back()->with('screen_msg', 'Message is sent');
    }
}