<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Email;
use Mustache_Engine;
 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;

class PostTextMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email_model;
    public $template_engine;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Email $email_model)
    {
        $this->email_model = $email_model;
        $this->template_engine = new Mustache_Engine;
    }

    public function getTextTemplate(): ?string
    {
        $template = $this->email_model->getTemplate();
        return $template->text_template;
    }


    public function content(): Content
    {
        $data = $this->email_model->getPlaceholders();
        return new Content(
            htmlString: $this->getTextTemplate(),
            with: $data
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    /* 
    public function build()
    {
        $data = $this->email_model->getPlaceholders();
        $email_body = $this->template_engine->render(
            $this->getTextTemplate(),
            $data
        );
        
        return $this
            ->subject($this->email_model->getSubject())
            ->text($email_body)
            ->with($data);
    } */
}