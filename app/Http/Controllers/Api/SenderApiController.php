<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Email;
use App\Contracts\Receipt;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendEmailRequest;
use App\Services\EmailService;
use App\Services\LogService;

class SenderApiController extends ApiController
{
    public function notfound()
    {
        return response(null, 404)->header('Content-Type', 'application/json');
    }

    public function send(SendEmailRequest $request, Service $service, LogService $logService, EmailService $emailService)
    {
        $email = new Email();
        $email->setService($service);
        $email->createContent($request->data);
        $email->setTransactionId($request->transaction_id);

        $to_emails = $email->getEmailsFromRequest($request->to);
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
