<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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

        $throttleKey = Str::lower($this->email_or_username).'|'.request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email_or_username', "هەوڵی زۆرت داوە. تکایە دوای {$seconds} چرکە دووبارە هەوڵ بدەرەوە.");

            return;
        }

        $loginType = filter_var($this->email_or_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $this->email_or_username, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            RateLimiter::clear($throttleKey);
            session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        RateLimiter::hit($throttleKey, 60);
        $this->addError('email_or_username', 'زانیارییەکان یەکناگرنەوە یان هەژمارەکە ڕاگرابوو.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
