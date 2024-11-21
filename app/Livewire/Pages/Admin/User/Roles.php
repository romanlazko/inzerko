<?php

namespace App\Livewire\Pages\Admin\User;

use App\Jobs\CreateSeedersJob;
use App\Livewire\Layouts\AdminTableLayout;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Roles extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Role::query())
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('permissions')
                    ->state(fn (Role $role) => $role->permissions->map(fn (Permission $permission) => "{$permission->name}_{$permission->guard_name}"))
                    ->badge(),
                TextColumn::make('guard_name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->headerActions([
                Action::make('Save Seeders')
                    ->action(function () {
                        CreateSeedersJob::dispatch([
                            'permissions',
                            'roles',
                            'role_has_permissions',
                            'model_has_roles',
                            'model_has_permissions',
                        ]);
                    })
                    ->visible($this->roleOrPermission(['manage'], 'role')),
                CreateAction::make('create')
                    ->button()
                    ->form([
                        TextInput::make('name')
                            ->required(),
                        Select::make('guard_name')
                            ->options([
                                'web' => 'web',
                                'telegram' => 'telegram',
                            ])
                            ->required()
                            ->default('web'),
                        Select::make('permissions')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn (Permission $permission) => "{$permission->name}_{$permission->guard_name}")
                            ->relationship('permissions', 'name'),
                    ])
                    ->visible($this->roleOrPermission(['create', 'manage'], 'role')),
            ])
            ->actions([
                EditAction::make('edit')
                    ->button()
                    ->hiddenLabel()
                    ->form([
                        TextInput::make('name')
                            ->required(),
                        Select::make('guard_name')
                            ->options([
                                'web' => 'web',
                                'telegram' => 'telegram',
                            ])
                            ->required()
                            ->default('web'),
                        Select::make('permissions')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn (Permission $permission) => "{$permission->name}_{$permission->guard_name}")
                            ->relationship('permissions', 'name'),
                    ])
                    ->visible($this->roleOrPermission(['update', 'manage'], 'role')),
                DeleteAction::make()
                    ->button()
                    ->hiddenLabel()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'role'))
            ])
            ->recordAction('edit');
    }
}
