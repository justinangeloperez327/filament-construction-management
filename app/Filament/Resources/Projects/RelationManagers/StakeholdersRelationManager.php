<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\ProjectStakeholderRole;
use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StakeholdersRelationManager extends RelationManager
{
    protected static string $relationship = 'stakeholders';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'legal_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('role')
                    ->options(ProjectStakeholderRole::options())
                    ->required(),
                Toggle::make('is_primary')
                    ->label('Primary for this role')
                    ->default(false),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.legal_name')->label('Company')->searchable()->sortable(),
                TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn (ProjectStakeholderRole $state): string => $state->label())
                    ->color(fn (ProjectStakeholderRole $state): string => $state->color()),
                IconColumn::make('is_primary')->label('Primary')->boolean(),
            ])
            ->filters([
                SelectFilter::make('role')->options(ProjectStakeholderRole::options()),
            ])
            ->headerActions([
                CreateAction::make()->visible(fn (): bool => $this->canManageProject()),
            ])
            ->recordActions([
                EditAction::make()->visible(fn (): bool => $this->canManageProject()),
                DeleteAction::make()->visible(fn (): bool => $this->canManageProject()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])->visible(fn (): bool => $this->canManageProject()),
            ]);
    }

    private function canManageProject(): bool
    {
        $user = auth()->user();
        $project = $this->getOwnerRecord();

        return $user !== null
            && $project instanceof Project
            && $project->userHasPermission($user, 'projects.update');
    }
}
