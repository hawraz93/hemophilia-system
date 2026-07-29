<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class Login extends \Livewire\Component
{
    public string $email_or_username = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email_or_username' => 'required|string',
        'password' => 'required|string',
    ];

    public function login()
    {
        $this->validate();

        $loginType = filter_var($this->email_or_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $this->email_or_username, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        $this->addError('email_or_username', 'زانیارییەکان یەکناگرنەوە یان هەژمارەکە ڕاگرابوو.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
