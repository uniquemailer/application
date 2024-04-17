<?php

use App\Filament\Resources\ServiceResource;
use App\Models\Service;
use App\Models\Template;

use function Pest\Livewire\livewire;


it('can render page', function () {
    $this->get(ServiceResource::getUrl('index'))->assertSuccessful();
});


it('can create', function () {
    Template::factory()->create();
    $newData = Service::factory()->make();

    livewire(ServiceResource\Pages\CreateService::class)
        ->fillForm([
            'name' => $newData->name,
            'template_id' => $newData->template_id,
            'email_type' => 'HTML'
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Service::class, [
        'name' => $newData->name,
        'template_id' => $newData->template_id,
        'email_type' => $newData->email_type,
    ]);
});

it('can retrieve a service', function () {
    Template::factory()->create();
    $newData = Service::factory()->create();

    livewire(
        ServiceResource\Pages\EditService::class,
        [
            'record' => $newData->getRouteKey(),
        ]
    )
        ->assertFormSet([
            'name' => $newData->name,
            'template_id' => $newData->template_id,
            'email_type' => $newData->email_type,
        ]);
});
