<?php

namespace App\Filament\Resources\Teams;

use App\Enums\ActiveStatus;
use App\Filament\Resources\Teams\Pages\CreateTeam;
use App\Filament\Resources\Teams\Pages\EditTeam;
use App\Filament\Resources\Teams\Pages\ListTeams;
use App\Models\Team;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->required()->maxLength(255)->unique(ignoreRecord: true),
                TextInput::make('code')->required()->maxLength(32)->unique(ignoreRecord: true),
                Select::make('status')
                    ->options(ActiveStatus::options())
                    ->required()
                    ->default(ActiveStatus::Active->value),
                Textarea::make('description')->rows(3)->columnSpanFull(),
                Select::make('users')
                    ->relationship('users', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('users_count')->counts('users')->label('Members')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ActiveStatus $state): string => $state->label())
                    ->color(fn (ActiveStatus $state): string => $state->color()),
            ])
            ->filters([
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
            'index' => ListTeams::route('/'),
            'create' => CreateTeam::route('/create'),
            'edit' => EditTeam::route('/{record}/edit'),
        ];
    }
}
