<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\GeneralStatus;
use App\Enums\ProjectStakeholderRole;
use App\Filament\Resources\Projects\RelationManagers\Concerns\AuthorizesProjectRelation;
use App\Models\Discipline;
use App\Models\ProjectCompany;
use App\Models\Trade;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WorkPackagesRelationManager extends RelationManager
{
    use AuthorizesProjectRelation;

    protected static string $relationship = 'workPackages';

    public function form(Schema $schema): Schema
    {
        $projectId = $this->getOwnerRecord()->getKey();

        return $schema
            ->columns(2)
            ->components([
                TextInput::make('code')->required()->maxLength(64),
                TextInput::make('title')->required()->maxLength(255),
                Select::make('discipline_id')
                    ->label('Discipline')
                    ->options(fn (): array => Discipline::query()
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('trade_id', null)),
                Select::make('trade_id')
                    ->label('Trade')
                    ->options(fn (Get $get): array => Trade::query()
                        ->when(
                            $get('discipline_id'),
                            fn ($query, $disciplineId) => $query->where('discipline_id', $disciplineId),
                            fn ($query) => $query->whereRaw('1 = 0'),
                        )
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->disabled(fn (Get $get): bool => blank($get('discipline_id'))),
                Select::make('contractor_company_id')
                    ->label('Contractor')
                    ->options(fn (): array => ProjectCompany::query()
                        ->where('project_id', $projectId)
                        ->whereIn('role', [
                            ProjectStakeholderRole::MainContractor->value,
                            ProjectStakeholderRole::Contractor->value,
                            ProjectStakeholderRole::Subcontractor->value,
                        ])
                        ->with('company')
                        ->get()
                        ->mapWithKeys(fn (ProjectCompany $stakeholder): array => [
                            $stakeholder->company_id => $stakeholder->company->legal_name,
                        ])
                        ->all())
                    ->searchable(),
                Select::make('status')
                    ->options(GeneralStatus::options())
                    ->required()
                    ->default(GeneralStatus::Draft->value),
                DatePicker::make('start_date'),
                DatePicker::make('end_date')->afterOrEqual('start_date'),
                Textarea::make('description')->rows(3)->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('discipline.name')->label('Discipline')->sortable(),
                TextColumn::make('trade.name')->label('Trade')->toggleable(),
                TextColumn::make('contractor.legal_name')->label('Contractor')->toggleable(),
                TextColumn::make('start_date')->date()->toggleable(),
                TextColumn::make('end_date')->date()->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (GeneralStatus $state): string => $state->label())
                    ->color(fn (GeneralStatus $state): string => $state->color()),
            ])
            ->filters([
                SelectFilter::make('discipline')->relationship('discipline', 'name'),
                SelectFilter::make('status')->options(GeneralStatus::options()),
            ])
            ->headerActions([
                CreateAction::make()->visible(fn (): bool => $this->canProject('work_packages.create')),
            ])
            ->recordActions([
                EditAction::make()->visible(fn (): bool => $this->canProject('work_packages.update')),
                DeleteAction::make()->visible(fn (): bool => $this->canProject('work_packages.delete')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])->visible(fn (): bool => $this->canProject('work_packages.delete')),
            ])
            ->defaultSort('code');
    }
}
