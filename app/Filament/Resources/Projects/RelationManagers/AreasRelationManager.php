<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\ActiveStatus;
use App\Filament\Resources\Projects\RelationManagers\Concerns\AuthorizesProjectRelation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AreasRelationManager extends RelationManager
{
    use AuthorizesProjectRelation;

    protected static string $relationship = 'areas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('code')->required()->maxLength(64),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('sort_order')->label('Sort order')->integer()->minValue(0)->default(0),
                Select::make('status')->options(ActiveStatus::options())->required()->default(ActiveStatus::Active->value),
                Textarea::make('description')->rows(3)->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('assets_count')->counts('assets')->label('Assets')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ActiveStatus $state): string => $state->label())
                    ->color(fn (ActiveStatus $state): string => $state->color()),
            ])
            ->filters([
                SelectFilter::make('status')->options(ActiveStatus::options()),
            ])
            ->headerActions([
                CreateAction::make()->visible(fn (): bool => $this->canProject('project_structure.create')),
            ])
            ->recordActions([
                EditAction::make()->visible(fn (): bool => $this->canProject('project_structure.update')),
                DeleteAction::make()->visible(fn (): bool => $this->canProject('project_structure.delete')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])->visible(fn (): bool => $this->canProject('project_structure.delete')),
            ])
            ->defaultSort('sort_order');
    }
}
