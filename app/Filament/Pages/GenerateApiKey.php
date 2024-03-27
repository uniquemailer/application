<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class GenerateApiKey extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?int $navigationSort = 14;

    protected static string $view = 'filament.pages.generate-api-key';

    public string $key;


    public function mount()
    {
        $this->key = '**********************************';
    }

    public function generateApiKey()
    {
        $user = Auth::user();
        $created_token = $user->createToken('api-client', ['send']);
        $this->key = $created_token->plainTextToken;
    }
}
