<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLoggerService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserIndex extends Component
{
    public bool $showCreateModal = false;

    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'staff';

    public function mount()
    {
        $this->authorize('manage-users');
    }

    public function openModal()
    {
        $this->reset(['name', 'username', 'email', 'password', 'role']);
        $this->showCreateModal = true;
    }

    public function save()
    {
        $this->authorize('manage-users');

        $assignable = array_map(fn (UserRole $r) => $r->value, UserRole::assignableBy(auth()->user()->role));

        $this->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in($assignable)],
        ], [
            'role.in' => 'تۆ ناتوانیت ئەم دەسەڵاتە بدەیت بە بەکارهێنەر.',
        ]);

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'is_active' => true,
        ]);

        AuditLoggerService::log('user_created', $user, null, $user->only(['name', 'username', 'email', 'role']));

        $this->showCreateModal = false;
        session()->flash('message', 'بەکارهێنەری نوێ بە سەرکەوتوویی دروستکرا.');
    }

    public bool $showResetModal = false;
    public ?int $selectedUserId = null;
    public string $new_password = '';

    /**
     * Find a user the current admin is allowed to manage (never themselves).
     */
    private function manageableUser(int $userId): User
    {
        $this->authorize('manage-users');

        $user = User::findOrFail($userId);
        abort_if($user->id === auth()->id() || ! auth()->user()->canManage($user), 403);

        return $user;
    }

    public function toggleActive($userId)
    {
        $user = $this->manageableUser($userId);

        $user->is_active = !$user->is_active;
        $user->save();

        AuditLoggerService::log($user->is_active ? 'user_activated' : 'user_deactivated', $user);

        session()->flash('message', 'دۆخی بەکارهێنەر نویستکرایەوە.');
    }

    public function deleteUser($userId)
    {
        $user = $this->manageableUser($userId);

        AuditLoggerService::log('user_deleted', $user, $user->only(['name', 'username', 'email', 'role']));
        $user->delete();

        session()->flash('message', 'بەکارهێنەر بە سەرکەوتوویی سڕدرایەوە.');
    }

    public function openResetModal($userId)
    {
        $this->manageableUser($userId);

        $this->selectedUserId = $userId;
        $this->new_password = '';
        $this->showResetModal = true;
    }

    public function resetPassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:8',
        ]);

        $user = $this->manageableUser($this->selectedUserId);
        $user->password = Hash::make($this->new_password);
        $user->save();

        AuditLoggerService::log('user_password_reset', $user);

        $this->showResetModal = false;
        session()->flash('message', 'وشەی نهێنی بەکارهێنەر بە سەرکەوتوویی گۆڕدرا.');
    }

    public function createBackup()
    {
        $this->authorize('manage-users');

        $exitCode = Artisan::call('app:backup-database');

        if ($exitCode === 0) {
            session()->flash('message', 'بەکئەپی نوێی داتابەیس بە سەرکەوتوویی دروستکرا.');
        } else {
            session()->flash('error', 'دروستکردنی بەکئەپ سەرکەوتوو نەبوو: '.trim(Artisan::output()));
        }
    }

    public function downloadBackup(string $filename)
    {
        $this->authorize('manage-users');

        $path = storage_path('app/backups/' . basename($filename));
        if (File::exists($path)) {
            return response()->download($path);
        }
        session()->flash('error', 'فایلی بەکئەپ نەدۆزرایەوە.');
    }

    public function render()
    {
        $users = User::all();
        $auditLogs = AuditLog::with('user')->latest()->take(30)->get();

        $backupFiles = [];
        $backupDir = storage_path('app/backups');
        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            foreach ($files as $file) {
                $backupFiles[] = [
                    'name' => $file->getFilename(),
                    'size' => round($file->getSize() / 1024, 2) . ' KB',
                    'date' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
            // Sort latest first
            usort($backupFiles, fn($a, $b) => strcmp($b['date'], $a['date']));
        }

        return view('livewire.user-index', compact('users', 'auditLogs', 'backupFiles'));
    }
}
