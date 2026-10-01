<?php

namespace App\Filament\Resources\Companies\RelationManagers;

use App\Enums\CompanyDocumentType;
use App\Support\Filament\FormDefaults;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('type')
                    ->options(CompanyDocumentType::options())
                    ->required(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('document_number')
                    ->label('Document number')
                    ->maxLength(128),
                DatePicker::make('issued_at')
                    ->label('Issued date'),
                DatePicker::make('expires_at')
                    ->label('Expiry date')
                    ->afterOrEqual('issued_at'),
                FileUpload::make('file_path')
                    ->label('File')
                    ->disk(FormDefaults::documentDisk())
                    ->directory('documents/companies')
                    ->maxSize(FormDefaults::maxUploadSize())
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->required()
                    ->downloadable()
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (CompanyDocumentType $state): string => $state->label()),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('document_number')->label('Document #')->searchable(),
                TextColumn::make('issued_at')->label('Issued')->date()->sortable(),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->date()
                    ->sortable()
                    ->color(fn ($record): string => $record->isExpired() ? 'danger' : ($record->isExpiringSoon() ? 'warning' : 'gray')),
            ])
            ->filters([
                SelectFilter::make('type')->options(CompanyDocumentType::options()),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
