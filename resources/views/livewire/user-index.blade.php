<div class="space-y-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">بەڕێوەبردنی بەکارهێنەران، بەکئەپ و Audit Log</h1>
            <p class="text-xs text-slate-500 mt-1">زیادکردنی بەکارهێنەرانی سیستەم، پاشەکەوتکردنی داتابەیس (Backup) و تۆماری گۆڕانکارییەکان</p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="createBackup" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-md shadow-indigo-600/20">
                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                <span>وەرگرتنی بەکئەپی داتابەیس (Backup)</span>
            </button>

            <x-button primary icon="user-plus" wire:click="openModal" class="font-bold">
                زیادکردنی بەکارهێنەری نوێ
            </x-button>
        </div>
    </div>

    <!-- Users Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($users as $user)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between space-y-4">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 dark:bg-rose-950 dark:text-rose-300 font-black flex items-center justify-center text-lg shrink-0">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ $user->name }}</h3>
                            <p class="text-xs text-slate-500 font-mono">{{ $user->email }}</p>
                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">@ {{ $user->username }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-700/80">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                            {{ $user->role?->label() }}
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $user->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-200 text-slate-600' }}">
                            {{ $user->is_active ? 'چالاک' : 'ناچالاک' }}
                        </span>
                    </div>

                    @if($user->id !== auth()->id())
                        <div class="flex items-center gap-1">
                            <button wire:click="openResetModal({{ $user->id }})" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition" title="گۆڕینی وشەی نهێنی">
                                <x-icon name="key" class="w-4 h-4" />
                            </button>
                            <button wire:click="toggleActive({{ $user->id }})" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/40 transition" title="گۆڕینی دۆخی بەکارهێنەر">
                                <x-icon name="arrow-path" class="w-4 h-4" />
                            </button>
                            <button wire:click="deleteUser({{ $user->id }})" wire:confirm="دڵنیایت لە سڕینەوەی ئەم بەکارهێنەرە؟" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition" title="سڕینەوەی بەکارهێنەر">
                                <x-icon name="trash" class="w-4 h-4" />
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Backup Manager Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
            <h3 class="font-extrabold text-slate-900 dark:text-white text-base flex items-center gap-2">
                <x-icon name="circle-stack" class="w-5 h-5 text-indigo-600" />
                <span>پاشەکەوتی داتابەیسەکان (Database Backups)</span>
            </h3>

            <button wire:click="createBackup" class="px-3.5 py-1.5 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-300 rounded-xl font-bold text-xs hover:bg-indigo-100 transition border border-indigo-200">
                + دروستکردنی بەکئەپی نوێ
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-3">ناونیشانی فایلی بەکئەپ</th>
                        <th class="p-3">بەرواری دروستکردن</th>
                        <th class="p-3">قەبارەی فایل</th>
                        <th class="p-3 text-center">داگرتن</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($backupFiles as $file)
                        <tr>
                            <td class="p-3 font-mono font-bold text-slate-800 dark:text-slate-200">{{ $file['name'] }}</td>
                            <td class="p-3 text-xs text-slate-500 font-mono">{{ $file['date'] }}</td>
                            <td class="p-3 font-mono text-xs text-indigo-600 font-bold">{{ $file['size'] }}</td>
                            <td class="p-3 text-center">
                                <button wire:click="downloadBackup('{{ $file['name'] }}')" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs transition inline-flex items-center gap-1 shadow-2xs">
                                    <x-icon name="arrow-down-tray" class="w-3.5 h-3.5" />
                                    <span>داگرتن</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-400 text-xs">هیچ فایلی بەکئەپێک دروست نەکراوە. دوگمەی "وەرگرتنی بەکئەپی داتابەیس" دابگرە.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Audit Logs Section -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
        <h3 class="font-extrabold text-slate-900 dark:text-white text-base flex items-center gap-2">
            <x-icon name="shield-check" class="w-5 h-5 text-red-600" />
            <span>تۆماری گۆڕانکارییەکان (Audit Log)</span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-3">بەروار</th>
                        <th class="p-3">بەکارهێنەر</th>
                        <th class="p-3">جۆری ڕووداو</th>
                        <th class="p-3">مۆدێلی گۆڕدراو</th>
                        <th class="p-3">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($auditLogs as $log)
                        <tr>
                            <td class="p-3 text-xs text-slate-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="p-3 font-bold text-slate-800 dark:text-white">{{ $log->user?->name ?? 'سیستەم' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-slate-100 text-slate-800">
                                    {{ $log->event }}
                                </span>
                            </td>
                            <td class="p-3 text-xs font-mono text-slate-600 dark:text-slate-300">
                                {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                            </td>
                            <td class="p-3 text-xs font-mono text-slate-500">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">هیچ ڕووداوێک تۆمار نەکراوە.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Modal -->
    <x-modal-card title="زیادکردنی بەکارهێنەری نوێ" wire:model="showCreateModal" max-width="md">
        <div class="space-y-4">
            <x-input wire:model="name" label="ناوی ناوخۆیی (Full Name) *" placeholder="کارمەند..." />
            <x-input wire:model="username" label="ناوی بەکارهێنەر (Username) *" placeholder="user123" />
            <x-input type="email" wire:model="email" label="ئیمەیڵ *" placeholder="user@hemophilia.org" />
            <x-password wire:model="password" label="وشەی نهێنی (Password) *" />
            <x-native-select wire:model="role" label="دەسەڵاتی بەکارهێنەر">
                @foreach(\App\Enums\UserRole::cases() as $r)
                    <option value="{{ $r->value }}">{{ $r->label() }}</option>
                @endforeach
            </x-native-select>
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="پاشەکەوتکردن" wire:click="save" spinner="save" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </x-slot:footer>
    </x-modal-card>

    <!-- Reset Password Modal -->
    <x-modal-card title="گۆڕینی وشەی نهێنی بەکارهێنەر" wire:model="showResetModal" max-width="md">
        <div class="space-y-4">
            <x-password wire:model="new_password" label="وشەی نهێنی نوێ *" placeholder="******" />
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="نوێکردنەوە" wire:click="resetPassword" spinner="resetPassword" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </x-slot:footer>
    </x-modal-card>
</div>
