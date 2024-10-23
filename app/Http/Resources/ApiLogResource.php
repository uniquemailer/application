<?php

namespace App\Http\Resources;

use App\Models\EmailAudit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
 
            'transaction_id' => $this->transaction_id,
            'request' => $this->request,
            'user' => $this->user_name,
            'service' => $this->service_name,
            'created_at' => $this->created_at,
            'status' => $this->status ? 'ACCEPTED' : 'WAITING',
            'email_audit' => EmailAuditResource::collection(EmailAudit::where('transaction_id', $this->transaction_id)->get())
        ];
    }
}
