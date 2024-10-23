<?php

namespace App\Services;

use App\Helpers\Email;
use App\Contracts\Receipt;
use App\Mail\PostHtmlMail;
use App\Mail\PostTextMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Sends an email to a list of recipients, split into chunks of 1 and queues them.
     *
     * @param Receipt $receipt
     * @param Email $email
     */
    public function sendQueue(Receipt $receipt, Email $email)
    {
        // Split the email collection into chunks of 1 recipient
        $email_collection = $receipt->getEmailCollection();
        $groupEmails = $receipt->getGroupEmailCollection();
        $chunks = $email_collection->chunk(1);

        // Loop through the chunks and send an email to each one
        foreach ($chunks as $chunk) {
            // Log the transaction id and the recipients being sent to
            Log::debug('Sending to the queue', [
                'TransactionId' => $email->getTransactionId(),
                'To' => $chunk
            ]);

            // Determine the type of email to send based on the email type
            if ($email->getEmailType() === 'HTML'){
                // Send an HTML email using the PostHtmlMail mailable
                Mail::to($chunk)->queue((new PostHtmlMail($email))->onQueue('emails'));
            }else{
                // Send a text email using the PostTextMail mailable
                Mail::to($chunk)->queue((new PostTextMail($email))->onQueue('emails'));
            }
        }

        Log::debug('Sending the email tp the group contact list', [
            'TransactionId' => $email->getTransactionId(),
            'To' => $groupEmails
        ]);

        if ($email->getEmailType() === 'HTML'){
            // Send an HTML email using the PostHtmlMail mailable
            Mail::to($groupEmails)->queue((new PostHtmlMail($email))->onQueue('emails'));
        }else{
            // Send a text email using the PostTextMail mailable
            Mail::to($groupEmails)->queue((new PostTextMail($email))->onQueue('emails'));
        }
    }
}
