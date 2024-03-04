<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Email;
use Mustache_Engine;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostHtmlMail extends Mailable implements ShouldQueue
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

    public function getHtmlTemplate(): ?string
    {
        $template = $this->email_model->getTemplate();
        return $template->html_template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->email_model->getPlaceholders();
        $email_body = $this->template_engine->render(
            $this->getHtmlTemplate(),
            $data
        );

        return $this
            ->subject($this->email_model->getSubject())
            ->html($email_body)
            ->with($data);
    }
}
