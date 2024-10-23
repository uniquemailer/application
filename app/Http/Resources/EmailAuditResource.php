<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailAuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'to' => $this->to,
            'subject' => $this->subject,
            'service' => $this->service,
            'template' => $this->template,
            'transaction_id' => $this->transaction_id,
            'created_at' => $this->created_at
        ];
    }
}
