<?php

namespace App\Services;

use App\Helpers\Email;
use App\Contracts\Receipt;
use App\Mail\PostHtmlMail;
use App\Mail\PostTextMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Mustache_Engine;

class EmailService
{
    public function sendQueue(Receipt $receipt, Email $email)
    {
        $email_collection = $receipt->getEmailCollection();
        $chunks = $email_collection->chunk(1);
 
        $template_engine = new Mustache_Engine();
       
        foreach ($chunks as $chunk) {
            Log::debug('Sending to the queue', [
                'TransactionId' => $email->getTransactionId(),
                'To' => $chunk
            ]);
            if ($email->getEmailType() === 'HTML'){
                Mail::to($chunk)->queue((new PostHtmlMail($email))->onQueue('emails'));
            }else{
                Mail::to($chunk)->queue((new PostTextMail($email))->onQueue('emails'));
/*                 $data = $email->getPlaceholders();
                $email_body = $template_engine->render(
                    $this->getTextTemplate($email),
                    $data
                );

                Mail::raw($email_body, function (Message $message) use($chunk) {
                    $message->to($chunk[0]);
                })->onQueue('emails'); */

            }
        }
    }

    public function getTextTemplate($email): ?string
    {
        $template = $email->getTemplate();
        return $template->text_template;
    }
}
