<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\ApiLog as ApiLogModel;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

class ApiLog extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.api-log';

    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['admin']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ApiLogModel::query()->with('user')->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('transaction_id'),
                TextColumn::make('user_name')->label('User'),
                TextColumn::make('service_name'),
                TextColumn::make('created_at')->date('d M Y H:s'),
            ])
            ->filters([
                // ...
            ])
            ->actions([

                Action::make('request')
                    ->modalContent(function(ApiLogModel $record){
                        $request = json_decode($record->request);
                        return new HtmlString('<pre>'. json_encode($request, JSON_PRETTY_PRINT) .'</pre>');
                    })->closeModalByClickingAway()
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),

                Action::make('delete')
                    ->action(fn (ApiLogModel $record) => $record->delete())
                    ->requiresConfirmation()
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
