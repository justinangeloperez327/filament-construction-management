<?php

namespace App\Filament\Resources\Designations;

use App\Enums\ActiveStatus;
use App\Filament\Resources\Designations\Pages\CreateDesignation;
use App\Filament\Resources\Designations\Pages\EditDesignation;
use App\Filament\Resources\Designations\Pages\ListDesignations;
use App\Models\Designation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DesignationResource extends Resource
{
    protected static ?string $model = Designation::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('code')->required()->maxLength(32)->unique(ignoreRecord: true),
                Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->options(ActiveStatus::options())
                    ->required()
                    ->default(ActiveStatus::Active->value),
                Textarea::make('description')->rows(3)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('department.name')->label('Department')->sortable(),
                TextColumn::make('users_count')->counts('users')->label('Users')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ActiveStatus $state): string => $state->label())
                    ->color(fn (ActiveStatus $state): string => $state->color()),
            ])
            ->filters([
                SelectFilter::make('department')->relationship('department', 'name'),
                SelectFilter::make('status')->options(ActiveStatus::options()),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDesignations::route('/'),
            'create' => CreateDesignation::route('/create'),
            'edit' => EditDesignation::route('/{record}/edit'),
        ];
    }
}
