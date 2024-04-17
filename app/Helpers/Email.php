<?php

namespace App\Helpers;

use App\Models\Template;
use Illuminate\Support\Str;
use App\Models\Service; 

class Email
{
    protected $template;
    protected $service;
    protected $placeholders;
    protected $transaction_id;
    protected $subject;
    protected $sensitive_keys;
    protected $email_type = 'HTML';

    /**
     * Get the value of sensitive keys
     */
    public function getSensitiveKeys()
    {
        return $this->sensitive_keys;
    }

    /**
     * Set the value of sensitive keys
     *
     * @return  self
     */
    public function setSensitiveKeys($keys)
    {
        $this->sensitive_keys = $keys;

        return $this;
    }

    /**
     * Get the value of transaction_id
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * Set the value of transaction_id
     *
     * @return  self
     */
    public function setTransactionId($transaction_id = null)
    {
        $this->transaction_id = (empty($transaction_id) ? Str::random(40) : $transaction_id);

        return $this;
    }

    /**
     * Get the value of subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the value of subject
     *
     * @return  self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the value of placeholders
     */
    public function getPlaceholders()
    {
        return $this->placeholders;
    }

    /**
     * Set the value of placeholders
     *
     * @return  self
     */
    public function setPlaceholders($placeholders)
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * Get the value of Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set the value of Service
     */
    public function setService(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Set the value of Template
     */
    public function setTemplate(Template $template)
    {
        $this->template = $template;
    }

    /**
     * Get the value of Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function createContent(array $request)
    {
        $service = $this->service;
        $template = $service->template;
        $this->setSensitiveKeys($template->sensitive_placeholders);
        $placeholders = array_merge($template->placeholders, $template->sensitive_placeholders);
        $variables = $this->updateVariableList($placeholders, $request);

        $this->setTransactionId();
        $this->setSubject($service->template->subject);
        $this->setTemplate($service->template);
        $this->setPlaceholders($variables);
        $this->email_type = $service->email_type;
    } 
    
    private function updateVariableList($placeholders, array $request)
    {
        $variables = [];

        foreach ($placeholders as $placeholder) {
            if (isset($request[$placeholder])) {
                $variables[$placeholder] = $request[$placeholder];
            } else {
                $variables[$placeholder] = 'Not Defined';
            }
        }
        return $variables;
    }

    public function getEmailsFromRequest(array $request): array
    {
        $to_emails = [];
         
        if (is_array($request)) {
            foreach ($request as $toemail) {
                $to_emails[] = trim($toemail);
            }
        }

        return $to_emails;
    }

    public function getEmailType()
    {
        return $this->email_type;
    }
}
