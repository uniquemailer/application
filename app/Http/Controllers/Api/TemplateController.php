<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TemplateResource;
use App\Models\Template;

class TemplateController extends ApiController
{

    public function index()
    {
        $templates = Template::paginate();
        return TemplateResource::collection($templates);
    }
 
}
