<?php

namespace App\Livewire\Layouts;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
abstract class AdminLayout extends Component
{
    public function render()
    {
        abort_if(! $this->roleOrPermission(['admin', 'moderator', 'super-duper-admin']), 403);

        return view('livewire.layouts.layout');
    }

    public function roleOrPermission(string|array $rolesOrPermissions = [], $guard_name = 'web'): bool
    {
        return auth()->user()->hasAnyRole($rolesOrPermissions, $guard_name) 
            OR auth()->user()->hasAnyPermission($rolesOrPermissions, $guard_name) 
            OR auth()->user()->canAny($rolesOrPermissions, $guard_name);
    }
}