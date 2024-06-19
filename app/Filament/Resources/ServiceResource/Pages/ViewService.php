<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewService extends ViewRecord
{
    protected static string $resource = ServiceResource::class;

    protected static string $view = 'filament.resources.services.pages.view-service';

    public function getExampleJson()
    {
        $service = $this->record;
        $template = $service->template;
        $json = [
                'data' => $template->sample_placeholders,
                "to" => "email1@testemail.com",
                "transaction_id" => "TESTNUMBER111111",
            ];

        return $json;            
    }
}
