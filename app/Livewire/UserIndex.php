<?php

namespace App\Livewire;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserIndex extends Component
{
    public bool $showCreateModal = false;

    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'staff';

    public function openModal()
    {
        $this->reset(['name', 'username', 'email', 'password', 'role']);
        $this->showCreateModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'is_active' => true,
        ]);

        $this->showCreateModal = false;
        session()->flash('message', 'بەکارهێنەری نوێ بە سەرکەوتوویی دروستکرا.');
    }

    public bool $showResetModal = false;
    public ?int $selectedUserId = null;
    public string $new_password = '';

    public function toggleActive($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->id === auth()->id()) {
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();
        session()->flash('message', 'دۆخی بەکارهێنەر نویستکرایەوە.');
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->id === auth()->id()) {
            return;
        }

        $user->delete();
        session()->flash('message', 'بەکارهێنەر بە سەرکەوتوویی سڕدرایەوە.');
    }

    public function openResetModal($userId)
    {
        $this->selectedUserId = $userId;
        $this->new_password = '';
        $this->showResetModal = true;
    }

    public function resetPassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:6',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->showResetModal = false;
        session()->flash('message', 'وشەی نهێنی بەکارهێنەر بە سەرکەوتوویی گۆڕدرا.');
    }

    public function render()
    {
        $users = User::all();
        $auditLogs = AuditLog::with('user')->latest()->take(30)->get();

        return view('livewire.user-index', compact('users', 'auditLogs'));
    }
}
