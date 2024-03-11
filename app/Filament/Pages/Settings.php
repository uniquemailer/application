<?php

namespace App\Filament\Pages;

use App\Models\EmailAudit;
use App\Models\ApiLog;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string $view = 'filament.pages.settings';

    protected static ?int $navigationSort = 50;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['admin']);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Data Audit')
                    ->schema([
                        Checkbox::make('delete_api_logs')
                            ->helperText('It will delete all API logs older than 7 days'),
                        Checkbox::make('delete_email_logs')
                            ->helperText('It will delete all email logs older than 7 days'),
                        Checkbox::make('expire_all_api_keys')
                            ->helperText('This action will de-active all forms'),
                    ])->columns(1)
            ])->statePath('data');
    }

    public function submit(): void
    {
        $this->form->getState();
        foreach ($this->data as $key => $value) {
            $this->$key();
        }
    }
    private function delete_email_logs()
    {
        EmailAudit::whereDate('created_at', '<=', now()->subDays(7))->delete();
        Notification::make()
            ->title('Old email logs deleted')
            ->success()
            ->send();
    }
    private function delete_api_logs()
    {
        ApiLog::whereDate('created_at', '<=', now()->subDays(7))->delete();
        Notification::make()
            ->title('Old APi logs deleted')
            ->success()
            ->send();
    }
    private function expire_all_api_keys()
    {
        DB::table('personal_access_tokens')
            ->where('expires_at', null)
            ->update(['expires_at' => now()]);
        Notification::make()
            ->title('All active API tokens are expired now!')
            ->success()
            ->send();
    }
}
