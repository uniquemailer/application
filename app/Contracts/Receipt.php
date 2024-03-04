<?php

namespace App\Contracts;

use App\Models\ContactGroup;
use Illuminate\Support\Collection;

class Receipt
{
    protected array $emails = [];

    public function setToEmails(array $emails)
    {
        foreach ($emails as $email) {
            $this->emails[]  = $email;
        }
        return $this;
    }
 
    public function setGroupEmails($contactGroups)
    {
        foreach ($contactGroups as $contactGroup) {
            foreach ($contactGroup->contacts as $contact) {
                $this->emails[]  = $contact->email;
            }
        }
        return $this;
    }

    public function getEmailCollection()
    {
        return collect($this->emails);
    }
}
