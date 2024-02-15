<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DOMDocument;

class CapEmailController extends Controller
{
    public function generateHtmlContent(Request $request)
    {
        Log::info('Request payload:', $request->all());
        if (!$request->has('html')) {
            return response()->json(['error' => 'HTML content is missing in the request'], 400);
        }
        $htmlContent = $request->html;
        if (empty($htmlContent)) {
            return response()->json(['error' => 'HTML content is empty'], 400);
        }
        $htmlContent = $this->processHtmlContent($htmlContent);
        return response()->json(['html' => $htmlContent]);
    }

    public function sendEmail(Request $request)
{
    try {
        $response = $this->generateHtmlContent($request);
        $html = $response->getContent();
        $recipients = ['padillakryss@gmail.com', 'krbartolome@vxi.com.ph'];
        $subject = 'PH TA Capacity File - as of ' . date('Y-m-d');
        Mail::send('email', ['html' => $html], function ($message) use ($html, $recipients, $subject) {
            $message->from('TA.Insights@vxi.com', 'TA Report');
            $message->to($recipients);
            $message->subject($subject);
            $message->setBody($html, 'text/html');
        });
        return response()->json(['message' => 'Email sent successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
    }
}

    private function processHtmlContent($htmlContent)
    {
        $doc = new DOMDocument();
        $doc->loadHTML($htmlContent);
        $tables = $doc->getElementsByTagName('table');
        foreach ($tables as $table) {
        }
        $modifiedHtmlContent = $doc->saveHTML();
        return $modifiedHtmlContent;
    }
}
