<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{

    protected static ?int $navigationSort = 1;
    
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                    Forms\Components\TextInput::make('slug')->visibleOn('view')
                    ->label('Enpoint URL')
                    ->required(),

                Forms\Components\Select::make('template_id')
                    ->options(
                        Template::query()
                            ->pluck('name', 'id')
                            ->toArray()
                    )->label('Template')
                    ->required(), 
                    
                Forms\Components\Select::make('email_type')
                    ->label('Email format')
                    ->options([
                        'HTML' => 'HTML', 
                        'TEXT' => 'TEXT'
                    ])
                    ->required(),

                Forms\Components\Select::make('contact_groups')
                    ->multiple()
                    ->searchable()
                    ->label('Contact Group')
                    ->relationship('contactGroups', 'name')
                   
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Service::with(['template', 'contactGroups']))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('template_name')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contactGroups.name')
                    ->badge()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
            'view' => Pages\ViewService::route('/{record}'),
        ];
    }
}
