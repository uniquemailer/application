<?php

namespace App\Filament\Pages;

use App\Models\EmailAudit;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\HtmlString;

class EmailLog extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static string $view = 'filament.pages.send-box-log';

    protected static ?int $navigationSort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(EmailAudit::query()->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('transaction_id'),
                TextColumn::make('to'),
                TextColumn::make('service'),
                TextColumn::make('template'),
                TextColumn::make('created_at')->date('d M Y H:s'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('message')
                    ->modalHeading(fn (EmailAudit $record) => $record->subject)
                    ->modalContent(function (EmailAudit $record) {
                        return new HtmlString('<pre>' . $record->message . '</pre>');
                    })->closeModalByClickingAway()
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),

                Action::make('delete')
                    ->action(fn (EmailAudit $record) => $record->delete())
                    ->requiresConfirmation()
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
