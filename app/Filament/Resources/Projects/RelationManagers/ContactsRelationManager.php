<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\CompanyContact;
use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'projectContacts';

    protected static ?string $title = 'Project Contacts';

    public function form(Schema $schema): Schema
    {
        $companyIds = $this->getOwnerRecord()
            ->stakeholders()
            ->pluck('company_id');

        return $schema
            ->columns(2)
            ->components([
                Select::make('company_contact_id')
                    ->label('Contact')
                    ->options(fn (): array => CompanyContact::query()
                        ->whereIn('company_id', $companyIds)
                        ->with('company')
                        ->get()
                        ->sortBy('name')
                        ->mapWithKeys(fn (CompanyContact $contact): array => [
                            $contact->getKey() => "{$contact->name} — {$contact->company->legal_name}",
                        ])
                        ->all())
                    ->searchable()
                    ->required(),
                TextInput::make('project_role')
                    ->label('Project role / responsibility')
                    ->maxLength(255),
                Toggle::make('is_primary')
                    ->label('Primary project contact')
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
                TextColumn::make('contact.name')->label('Contact')->searchable()->sortable(),
                TextColumn::make('contact.company.legal_name')->label('Company'),
                TextColumn::make('project_role')->label('Project role')->toggleable(),
                TextColumn::make('contact.email')->label('Email')->copyable()->toggleable(),
                TextColumn::make('contact.mobile')->label('Mobile')->copyable()->toggleable(),
                IconColumn::make('is_primary')->label('Primary')->boolean(),
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
