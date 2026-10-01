<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\RoleScope;
use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'memberships';

    protected static ?string $title = 'Project Team';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('role_id')
                    ->label('Project role')
                    ->relationship(
                        name: 'role',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query->where('scope', RoleScope::Project->value),
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('started_at')
                    ->label('Start date')
                    ->default(today()),
                DatePicker::make('ended_at')
                    ->label('End date')
                    ->afterOrEqual('started_at'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')->label('Member')->searchable()->sortable(),
                TextColumn::make('user.company.legal_name')->label('Company')->toggleable(),
                TextColumn::make('role.name')->label('Project role')->badge()->sortable(),
                TextColumn::make('started_at')->label('Start')->date()->toggleable(),
                TextColumn::make('ended_at')->label('End')->date()->toggleable(),
                IconColumn::make('is_active')->label('Active')->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn (): bool => $this->canManageProject()),
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
