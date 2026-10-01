<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use App\Enums\CompanyAddressType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('type')
                    ->options(CompanyAddressType::options())
                    ->required()
                    ->default(CompanyAddressType::Office->value),
                Toggle::make('is_primary')->label('Primary address'),
                TextInput::make('address_line_1')
                    ->label('Address line 1')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('address_line_2')
                    ->label('Address line 2')
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('city')->maxLength(128),
                TextInput::make('state')->label('State / Emirate')->maxLength(128),
                TextInput::make('postal_code')->label('Postal code')->maxLength(64),
                TextInput::make('country')->maxLength(128),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (CompanyAddressType $state): string => $state->label()),
                TextColumn::make('address_line_1')->label('Address')->searchable(),
                TextColumn::make('city')->searchable(),
                TextColumn::make('country')->searchable(),
                IconColumn::make('is_primary')->label('Primary')->boolean(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
