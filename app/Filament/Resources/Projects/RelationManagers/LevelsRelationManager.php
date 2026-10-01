<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\ActiveStatus;
use App\Filament\Resources\Projects\RelationManagers\Concerns\AuthorizesProjectRelation;
use App\Models\ProjectAsset;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LevelsRelationManager extends RelationManager
{
    use AuthorizesProjectRelation;

    protected static string $relationship = 'levels';

    public function form(Schema $schema): Schema
    {
        $projectId = $this->getOwnerRecord()->getKey();

        return $schema
            ->columns(2)
            ->components([
                Select::make('project_asset_id')
                    ->label('Asset / Building')
                    ->options(fn (): array => ProjectAsset::query()
                        ->where('project_id', $projectId)
                        ->with('area')
                        ->orderBy('sort_order')
                        ->get()
                        ->mapWithKeys(fn (ProjectAsset $asset): array => [
                            $asset->getKey() => trim(($asset->area?->name ? $asset->area->name.' — ' : '').$asset->name),
                        ])
                        ->all())
                    ->searchable()
                    ->required(),
                TextInput::make('code')->required()->maxLength(64),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('elevation')->numeric()->suffix('m'),
                TextInput::make('sort_order')->label('Sort order')->integer()->minValue(0)->default(0),
                Select::make('status')->options(ActiveStatus::options())->required()->default(ActiveStatus::Active->value),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('asset.area.name')->label('Area')->toggleable(),
                TextColumn::make('asset.name')->label('Asset')->sortable(),
                TextColumn::make('elevation')->suffix(' m')->toggleable(),
                TextColumn::make('locations_count')->counts('locations')->label('Locations')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ActiveStatus $state): string => $state->label())
                    ->color(fn (ActiveStatus $state): string => $state->color()),
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
