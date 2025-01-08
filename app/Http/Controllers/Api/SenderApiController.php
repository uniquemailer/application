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
use Closure;
use Illuminate\Support\Facades\Pipeline;

class SenderApiController extends ApiController
{
    public function notfound()
    {
        return response(null, 404)->header('Content-Type', 'application/json');
    }
 
    public function send(SendEmailRequest $request, Service $service, LogService $logService, EmailService $emailService)
    {
        $transactionId = Pipeline::send(new Email())
            ->through([
                function (Email $email, Closure $next) use ($service) {
                    $email->setService($service);
                    return $next($email);
                },
                function (Email $email, Closure $next) use ($request) {
                    $email->createContent($request->data);
                    return $next($email);
                },
                function (Email $email, Closure $next) use ($request) {
                    $email->setTransactionId($request->transaction_id);
                    return $next($email);
                },
                function (Email $email, Closure $next) use ($request) {
                    $email->setTransactionId($request->transaction_id);
                    return $next($email);
                },
                function (Email $email, Closure $next) use ($request, $logService) {
                    $transactionId = $email->getTransactionId();
                    $service = $email->getService();
                    $user = Auth::user();
                    $logService->log($request, $email->getSensitiveKeys(), $transactionId, $service->id, $user);
                    return $next($email);
                },
            ])
            ->then(function (Email $email) use($emailService, $request, $service) {
                $to_emails = $email->getEmailsFromRequest($request->to);
                $contactGroups = $service->contactGroups()->with('contacts')->get();
  
                $receipt = (new Receipt())
                    ->setToEmails($to_emails)
                    ->setGroupEmails($contactGroups);
                 
                $emailService->sendQueue($receipt, $email);
                
                return $email->getTransactionId();
            });

        return response()->json(['message' => 'The email saved to queue', 'queue_id' => $transactionId], 201);
    }
}
