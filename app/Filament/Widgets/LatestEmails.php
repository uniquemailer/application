<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\EmailAudit;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class LatestEmails extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(EmailAudit::query()->orderByDesc('created_at')->limit(50))
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
 
            ])
            ->bulkActions([
                // ...
            ])->paginated(false);
    }
 
}
