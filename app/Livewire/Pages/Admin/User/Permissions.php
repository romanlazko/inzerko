<?php

namespace App\Livewire\Pages\Admin\User;

use App\Jobs\CreateSeedersJob;
use App\Livewire\Actions\SeedAction;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Seeder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Permissions extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->defaultGroup('guard_name')
            ->groups([
                Group::make('guard_name')
                    ->getTitleFromRecordUsing(fn (Permission $record): string => ucfirst(str_replace('_', ' ', ($record->guard_name))))
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->query(Permission::with('roles'))
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                    ->description(fn (Permission $record) => $record->comment)
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->badge(),
                TextColumn::make('guard_name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->headerActions([
                SeedAction::make('permissions')
                    ->seedTables([
                        'permissions',
                        'roles',
                        'role_has_permissions',
                        'model_has_roles',
                        'model_has_permissions',
                    ]),
                CreateAction::make('create')
                    ->button()
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->datalist(Permission::distinct('name')->pluck('name')->toArray()),
                        TextInput::make('comment')
                            ->required(),
                        TextInput::make('guard_name')
                            ->required()
                            ->default('web')
                            ->datalist(Permission::distinct('guard_name')->pluck('guard_name')->toArray()),
                        Select::make('roles')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('roles', 'name'),
                    ])
                    ->visible($this->roleOrPermission(['create', 'manage'], 'permission'))
                    ->slideOver(),
            ])
            ->actions([
                EditAction::make()
                    ->button()
                    ->hiddenLabel()
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->datalist(Permission::distinct('name')->pluck('name')->toArray()),
                        TextInput::make('comment')
                            ->required(),
                        TextInput::make('guard_name')
                            ->required()
                            ->default('web')
                            ->datalist(Permission::distinct('guard_name')->pluck('guard_name')->toArray()),
                        Select::make('roles')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('roles', 'name'),
                    ])
                    ->visible($this->roleOrPermission(['update', 'manage'], 'permission'))
                    ->slideOver(),
                DeleteAction::make()
                    ->button()
                    ->hiddenLabel()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'permission')),
            ])
            ->recordAction('edit');
    }
}
