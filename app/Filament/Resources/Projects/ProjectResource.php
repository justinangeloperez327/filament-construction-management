<?php

namespace App\Filament\Resources\Projects;

use App\Enums\ProjectStatus;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Pages\ViewProject;
use App\Filament\Resources\Projects\RelationManagers\AreasRelationManager;
use App\Filament\Resources\Projects\RelationManagers\AssetsRelationManager;
use App\Filament\Resources\Projects\RelationManagers\ContactsRelationManager;
use App\Filament\Resources\Projects\RelationManagers\LevelsRelationManager;
use App\Filament\Resources\Projects\RelationManagers\LocationsRelationManager;
use App\Filament\Resources\Projects\RelationManagers\MembersRelationManager;
use App\Filament\Resources\Projects\RelationManagers\StakeholdersRelationManager;
use App\Filament\Resources\Projects\RelationManagers\WorkPackagesRelationManager;
use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Projects';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project')
                    ->columns(2)
                    ->schema([
                        TextInput::make('project_number')
                            ->label('Project number')
                            ->required()
                            ->maxLength(64)
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('short_name')
                            ->label('Short name')
                            ->maxLength(128),
                        TextInput::make('project_type')
                            ->label('Project type')
                            ->maxLength(128),
                        Select::make('status')
                            ->options(ProjectStatus::options())
                            ->required()
                            ->default(ProjectStatus::Planning->value),
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('Location')
                    ->columns(2)
                    ->schema([
                        TextInput::make('location')->maxLength(255)->columnSpanFull(),
                        TextInput::make('city')->maxLength(128),
                        TextInput::make('country')->maxLength(128),
                    ]),
                Section::make('Commercial')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contract_value')
                            ->label('Contract value')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('currency')
                            ->required()
                            ->default(config('construction.currency', 'AED'))
                            ->length(3),
                    ]),
                Section::make('Programme')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('start_date'),
                        DatePicker::make('planned_completion_date')
                            ->label('Planned completion')
                            ->afterOrEqual('start_date'),
                        DatePicker::make('actual_completion_date')
                            ->label('Actual completion')
                            ->afterOrEqual('start_date'),
                        DatePicker::make('defects_liability_end_date')
                            ->label('Defects liability end'),
                    ]),
                Section::make('Progress')
                    ->columns(2)
                    ->schema([
                        TextInput::make('planned_progress')
                            ->label('Planned progress %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0),
                        TextInput::make('actual_progress')
                            ->label('Actual progress %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project_number')
                    ->label('Project #')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client')
                    ->state(fn (Project $record): ?string => $record->clientName())
                    ->toggleable(),
                TextColumn::make('project_managers')
                    ->label('Project Manager')
                    ->state(fn (Project $record): string => $record->projectManagerNames())
                    ->toggleable(),
                TextColumn::make('city')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ProjectStatus $state): string => $state->label())
                    ->color(fn (ProjectStatus $state): string => $state->color())
                    ->sortable(),
                TextColumn::make('actual_progress')
                    ->label('Progress')
                    ->suffix('%')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),
                TextColumn::make('planned_completion_date')
                    ->label('Planned completion')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ProjectStatus::options()),
                SelectFilter::make('country')
                    ->options(fn (): array => Project::query()
                        ->whereNotNull('country')
                        ->distinct()
                        ->orderBy('country')
                        ->pluck('country', 'country')
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('project_number');
    }

    public static function getRelations(): array
    {
        return [
            MembersRelationManager::class,
            StakeholdersRelationManager::class,
            ContactsRelationManager::class,
            AreasRelationManager::class,
            AssetsRelationManager::class,
            LevelsRelationManager::class,
            LocationsRelationManager::class,
            WorkPackagesRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with([
                'memberships.user',
                'memberships.role',
                'stakeholders.company',
            ]);

        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        return $query->visibleTo($user);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
