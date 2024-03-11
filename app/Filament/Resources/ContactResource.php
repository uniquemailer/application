<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use App\Models\ContactGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('contact_group_id')
                    ->options(
                        ContactGroup::query()
                            ->pluck('name', 'id')
                            ->toArray()
                    )->label('Contact Group')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('first_name'),
                Forms\Components\TextInput::make('last_name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Contact::with('contact_group'))
            ->columns([
                Tables\Columns\TextColumn::make('contact_name')
                    ->searchable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('contact_group.name'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('contact_group_id')
                    ->placeholder('Filter by Contact Group')
                    ->label('Contact Group')
                    ->options(
                        ContactGroup::query()
                            ->pluck('name', 'id')
                            ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
