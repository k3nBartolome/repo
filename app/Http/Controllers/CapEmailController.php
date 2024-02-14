<?php

namespace App\Http\Controllers;

use App\Mail\CapEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CapEmailController extends Controller {
    public function sendEmail(Request $request) {
        $html = $request->html;
    
        Mail::send('email', ['html' => $html, ], function ($message) {
            $message->from('TA.Insights@vxi.com', 'TA Report');
            $message->to(['padillakryss@gmail.com', 'krbartolome@vxi.com.ph','arielito.pascua@vxi.com']);
            $message->subject('PH TA Capacity File - as of ' . date('Y-m-d'));
        });
    
        return response()->json(['message' => 'Email sent successfully']);
    }
    
    
}

