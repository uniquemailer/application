<?php

use App\Filament\Resources\ContactGroupResource;
use App\Filament\Resources\ContactResource;
use App\Filament\Resources\ServiceResource;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Service;
use App\Models\Template;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;

use function Pest\Livewire\livewire;

beforeEach(function () {
    /*     seed();
    $this->adminUser = User::whereEmail('test@example.com')->first();
    actingAs($this->adminUser); */
});


it('can render page', function () {
    $this->get(ContactResource::getUrl('index'))->assertSuccessful();
});

it('can create a contact', function () {
    $contactData = Contact::factory()->make();
    livewire(ContactResource\Pages\CreateContact::class)
        ->fillForm([
            'email' => $contactData->email,
            'first_name' => $contactData->first_name,
            'last_name' => $contactData->last_name,
            'contact_group_id' => $contactData->contact_group_id

        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Contact::class, [
        'email' => $contactData->email,
        'first_name' => $contactData->first_name,
        'last_name' => $contactData->last_name,
        'contact_group_id' => $contactData->contact_group_id
    ]);
});

it('can edit a contact group', function () {
    $contactData = Contact::factory()->create();
    $fakeData = Contact::factory()->make();
    livewire(
        ContactResource\Pages\EditContact::class,
        [
            'record' => $contactData->getRouteKey()
        ]
    )
        ->fillForm([
            'email' => $fakeData->email,
            'first_name' => $fakeData->first_name,
            'last_name' => $fakeData->last_name,
            'contact_group_id' => $fakeData->contact_group_id
        ])
        ->call('save')
        ->assertHasNoErrors();

    expect($contactData->refresh())
        ->email->toBe($fakeData->email)
        ->first_name->toBe($fakeData->first_name)
        ->last_name->toBe($fakeData->last_name)
        ->contact_group_id->toBe($fakeData->contact_group_id);
});

it('can not create a contact', function () {
    livewire(ContactResource\Pages\CreateContact::class)
        ->fillForm([
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'contact_group_id' => ''
        ])
        ->call('create')
        ->assertHasFormErrors(
            [
                'email' => 'required',
                'contact_group_id' => 'required'
            ]
        );
});

it('can not create a contact because email is not valid', function () {
    livewire(ContactResource\Pages\CreateContact::class)
        ->fillForm([
            'email' => fake()->name,
            'contact_group_id' => ContactGroup::factory()->create()
        ])
        ->call('create')
        ->assertHasFormErrors(
            [
                'email' => 'email'
            ]
        );
});
