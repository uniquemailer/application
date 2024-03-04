<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Helpers\Email;
use App\Contracts\Receipt;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailService;
use App\Services\LogService;

class ApiController extends Controller
{
    public function notfound()
    {
        return response(null, 404)->header('Content-Type', 'application/json');
    }

    public function send(Request $request, Service $service, LogService $logService, EmailService $emailService)
    {
        $email = new Email();
        $email->setService($service);
        $email->createContent($request->data);
        $email->setTransactionId($request->transaction_id);

        $to_emails = $email->getEmailsFromRequest($request);
        $transactionId = $email->getTransactionId();

        $user = Auth::user();
        
        $logService->log($request, $email->getSensitiveKeys(), $transactionId, $service->id, $user);
       
        $contactGroups = $service->contactGroups()->with('contacts')->get();
        $receipt = (new Receipt())
                    ->setToEmails($to_emails)
                    ->setGroupEmails($contactGroups);

        $emailService->sendQueue($receipt, $email);
        
        return response()->json(['message' => 'The email saved to queue', 'queue_id' => $transactionId], 201); 
    }    
}
