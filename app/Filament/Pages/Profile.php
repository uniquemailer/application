<?php

namespace App\Filament\Pages;

use App\Models\User;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 14;

    protected static string $view = 'filament.pages.profile';

    
    public User $user;

    public ?array $data = [];

    public function mount(): void
    {
        $this->user = auth()->user();
        
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(250),

                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->maxLength(250)
                    ->unique(User::class, 'email', ignoreRecord: true),

                TextInput::make('password')
                    ->password()
                    ->revealable(),

                TextInput::make('password_confirmation')
                    ->password()
                    ->autocomplete('password')
            ])
            ->model(auth()->user())
            ->statePath('data');
    }

    public function submit(): void
    {
        $this->form->getState();

        $state = array_filter([
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'password' => $this->data['password'] ? Hash::make($this->data['password']) : null,
        ]);

        $this->user->update($state);
        Notification::make() 
            ->title('Your profile has been updated.')
            ->success()
            ->send(); 
    }
}
