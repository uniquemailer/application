<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ServiceResource;
use App\Models\Service;

class ServiceController extends ApiController
{

    public function index()
    {
        $services = Service::paginate();
        return ServiceResource::collection($services);
    }

    public function show(Service $service)
    {
        if ($this->include('template')) {
            return new ServiceResource($service->load('template'));
        }
        return new ServiceResource($service);
    }
}
