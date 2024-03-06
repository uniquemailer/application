<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\DB;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $to = $event->message->getTo();
        $emailModel = $event->data['email_model'];

        $template = $emailModel->getTemplate();
        $service = $emailModel->getService();
        $sensitive_placeholders = $template->sensitive_placeholders;
        $placeholders = $emailModel->getPlaceholders();
        $message = $event->message->getBody();
        $body = $message->getBody();

        foreach($placeholders as $key => $value){
            if (in_array($key, $sensitive_placeholders)){
                $fill = str_repeat('*', strlen ($value)); 
                $body = str_replace($value, $fill, $body);
            }
        }

        $emails = [];
        foreach($to as $t){
            $emails[] = $t->getAddress();
        }
        //$emails = substr($emails, 0, strlen($emails)-2);
        DB::table('email_audits')->insert(
            [
                'message' => $body,
                'to' => json_encode($emails),
                'subject'=> $event->message->getSubject(),
                'service' => $service->name,
                'template' => $template->name,
                'transaction_id' => $emailModel->getTransactionId(),
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                ]
        );

        DB::table('api_audits')->where('transaction_id', $emailModel->getTransactionId())->update(['status' => 1]);

    }
}
