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
            'slug' => $newData->slug,
            'template_id' => $newData->template_id,
            'email_type' => 'HTML'
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Service::class, [
        'name' => $newData->name,
        'slug' => $newData->slug,
        'template_id' => $newData->template_id,
        'email_type' => $newData->email_type,
    ]);
});

/* 
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceResourceTest extends TestCase
{
 
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
} */
