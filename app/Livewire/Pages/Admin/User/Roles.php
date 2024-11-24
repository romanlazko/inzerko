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
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Orangehill\Iseed\Facades\Iseed;

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
                    ->state(fn (Role $role) => $role->permissions->sortBy('guard_name')->map(fn (Permission $permission) => "{$permission->name}_{$permission->guard_name}"))
                    ->badge(),
                TextColumn::make('guard_name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->headerActions([
                SeedAction::make('roles')
                    ->seedTables([
                        'permissions',
                        'roles',
                        'role_has_permissions',
                        'model_has_roles',
                        'model_has_permissions',
                    ]),
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
                    ->icon('heroicon-o-plus-circle')
                    ->visible($this->roleOrPermission(['create', 'manage'], 'role'))
                    ->slideOver(),
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
                    ->visible($this->roleOrPermission(['update', 'manage'], 'role'))
                    ->slideOver(),
                DeleteAction::make()
                    ->button()
                    ->hiddenLabel()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'role'))
            ])
            ->recordAction('edit');
    }
}
