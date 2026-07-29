<div class="space-y-8">
    <!-- Header Banner -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-rose-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-rose-600/30 shrink-0">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ auth()->user()->name }}</h1>
                <p class="text-xs text-slate-500 font-mono">{{ auth()->user()->email }} | @ {{ auth()->user()->username }}</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-xs font-black px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                        {{ auth()->user()->role->label() }}
                    </span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                        هەژماری چالاک
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Profile Settings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information Form -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-5">
            <h3 class="font-extrabold text-slate-900 dark:text-white text-base flex items-center gap-2 border-b pb-3">
                <x-icon name="user" class="w-5 h-5 text-rose-600" />
                <span>گۆڕینی زانیارییە کەسییەکان</span>
            </h3>

            @if (session()->has('message'))
                <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="updateProfile" class="space-y-4">
                <x-input wire:model="name" label="ناوی تەواو *" placeholder="ناوی کارمەند..." />
                <x-input wire:model="username" label="ناوی بەکارهێنەر (Username) *" placeholder="user123" />
                <x-input type="email" wire:model="email" label="ئیمەیڵ *" placeholder="email@hemophilia.org" />

                <div class="pt-2 flex justify-end">
                    <x-button type="submit" primary label="نوێکردنەوەی زانیارییەکان" spinner="updateProfile" class="font-bold shadow-md shadow-rose-600/20" />
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-5">
            <h3 class="font-extrabold text-slate-900 dark:text-white text-base flex items-center gap-2 border-b pb-3">
                <x-icon name="key" class="w-5 h-5 text-rose-600" />
                <span>گۆڕینی وشەی نهێنی (Password)</span>
            </h3>

            @if (session()->has('password_message'))
                <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                    {{ session('password_message') }}
                </div>
            @endif

            <form wire:submit="updatePassword" class="space-y-4">
                <x-password wire:model="current_password" label="وشەی نهێنی کۆن (ئیستا) *" placeholder="••••••••" />
                <x-password wire:model="new_password" label="وشەی نهێنی نوێ *" placeholder="••••••••" />
                <x-password wire:model="new_password_confirmation" label="دووبارەکردنەوەی وشەی نهێنی نوێ *" placeholder="••••••••" />

                <div class="pt-2 flex justify-end">
                    <x-button type="submit" primary label="گۆڕینی وشەی نهێنی" spinner="updatePassword" class="font-bold shadow-md shadow-rose-600/20" />
                </div>
            </form>
        </div>
    </div>

    <!-- User Activity History -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
        <h3 class="font-extrabold text-slate-900 dark:text-white text-base flex items-center gap-2">
            <x-icon name="clock" class="w-5 h-5 text-rose-600" />
            <span>مێژووی دواین چالاکییەکانم</span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-3">بەروار</th>
                        <th class="p-3">جۆری چالاکی</th>
                        <th class="p-3">مۆدێل</th>
                        <th class="p-3">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($myLogs as $log)
                        <tr>
                            <td class="p-3 text-xs font-mono text-slate-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
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
                            <td colspan="4" class="p-6 text-center text-slate-400">هیچ چالاکییەک لێرە دا نەدۆزرایەوە.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
