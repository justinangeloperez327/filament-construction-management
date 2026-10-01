<?php

namespace App\Filament\Resources\Companies;

use App\Enums\ActiveStatus;
use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Companies\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\Companies\RelationManagers\ContactsRelationManager;
use App\Filament\Resources\Companies\RelationManagers\DocumentsRelationManager;
use App\Models\Company;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Companies';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'legal_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company')
                    ->columns(2)
                    ->schema([
                        TextInput::make('legal_name')
                            ->label('Legal name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('trading_name')
                            ->label('Trading name')
                            ->maxLength(255),
                        TextInput::make('code')
                            ->required()
                            ->maxLength(64)
                            ->unique(ignoreRecord: true),
                        Select::make('status')
                            ->options(ActiveStatus::options())
                            ->required()
                            ->default(ActiveStatus::Active->value),
                        Select::make('types')
                            ->label('Classifications')
                            ->relationship('types', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Section::make('Registration')
                    ->columns(2)
                    ->schema([
                        TextInput::make('registration_number')
                            ->label('Registration number')
                            ->maxLength(128),
                        TextInput::make('vat_number')
                            ->label('VAT / TRN number')
                            ->maxLength(128),
                    ]),
                Section::make('Contact')
                    ->columns(2)
                    ->schema([
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(64),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('website')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('city')
                            ->maxLength(128),
                        TextInput::make('country')
                            ->maxLength(128),
                    ]),
                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('legal_name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('trading_name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('types.name')
                    ->label('Classifications')
                    ->badge(),
                TextColumn::make('city')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('country')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ActiveStatus $state): string => $state->label())
                    ->color(fn (ActiveStatus $state): string => $state->color())
                    ->sortable(),
                TextColumn::make('contacts_count')
                    ->counts('contacts')
                    ->label('Contacts')
                    ->sortable(),
                TextColumn::make('documents_count')
                    ->counts('documents')
                    ->label('Documents')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ActiveStatus::options()),
                SelectFilter::make('types')
                    ->label('Classification')
                    ->relationship('types', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('legal_name');
    }

    public static function getRelations(): array
    {
        return [
            ContactsRelationManager::class,
            AddressesRelationManager::class,
            DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }
}
