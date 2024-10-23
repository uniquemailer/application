<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ApiLogResource;
use App\Http\Resources\EmailAuditResource;
use App\Http\Resources\TemplateResource;
use App\Models\ApiLog;
use App\Models\EmailAudit;
use App\Models\Template;

class AuditLogController extends ApiController
{

    public function emails()
    {
        $logs = EmailAudit::paginate();
        return EmailAuditResource::collection($logs);
    }
 
    public function api_log()
    {
        $logs = ApiLog::paginate();
        return ApiLogResource::collection($logs);
    }

    public function transaction(string $id)
    {
        $logs = ApiLog::where('transaction_id', $id)->first();
        return new ApiLogResource($logs);
    }
}
