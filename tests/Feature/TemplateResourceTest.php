<?php

use App\Filament\Resources\TemplateResource;
use App\Models\Template;

use function Pest\Livewire\livewire;

it('can retrieve a template', function () {
    $template = Template::factory()->create();

    livewire(
        TemplateResource\Pages\EditTemplate::class,
        [
            'record' => $template->getRouteKey(),
        ]
    )
        ->assertFormSet([
            'name' => $template->name,
            'subject' => $template->subject,
            'placeholders' => $template->placeholders,
        ]);
});