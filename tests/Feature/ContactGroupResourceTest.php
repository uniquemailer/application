<?php

use App\Filament\Resources\ContactGroupResource;
use App\Filament\Resources\ContactResource;
use App\Filament\Resources\ServiceResource;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Service;
use App\Models\Template;

use function Pest\Livewire\livewire;


it('can render page', function () {
    $this->get(ContactGroupResource::getUrl('index'))->assertSuccessful();
});


it('can create a contact group', function () {
    $fake = fake()->name;
    livewire(ContactGroupResource\Pages\CreateContactGroup::class)
        ->fillForm([
            'name' => $fake,

        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ContactGroup::class, [
        'name' => $fake
    ]);
});

it('can not create a contact group', function () {

    livewire(ContactGroupResource\Pages\CreateContactGroup::class)
        ->fillForm([
            'name' => '',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});


it('can edit a contact group', function () {

    $contactGroupData = ContactGroup::factory()->create();
    $fake = fake()->name;
    livewire(
        ContactGroupResource\Pages\EditContactGroup::class,
        [
            'record' => $contactGroupData->getRouteKey()
        ]
    )
        ->fillForm([
            'name' => $fake,
        ])
        ->call('save')
        ->assertHasNoErrors();

    expect($contactGroupData->refresh())
        ->name->toBe($fake);

    $this->assertDatabaseHas(ContactGroup::class, [
        'name' => $fake,
        'id' => $contactGroupData['id']
    ]);
});
