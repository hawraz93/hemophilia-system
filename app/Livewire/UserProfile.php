<?php

namespace App\Livewire;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserProfile extends Component
{
    public string $name = '';
    public string $email = '';
    public string $username = '';

    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
        ]);

        session()->flash('message', 'زانیارییە کەسییەکان بە سەرکەوتوویی نوێکرانەوە.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'وشەی نهێنی کۆن هەڵەیە.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('password_message', 'وشەی نهێنی بە سەرکەوتوویی نوێکرایەوە.');
    }

    public function render()
    {
        $myLogs = AuditLog::where('user_id', Auth::id())->latest()->take(20)->get();
        return view('livewire.user-profile', compact('myLogs'));
    }
}
