<div class="space-y-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">بەڕێوەبردنی بەکارهێنەران و Audit Log</h1>
            <p class="text-xs text-slate-500 mt-1">زیادکردنی بەکارهێنەرانی سیستەم، دەسەڵاتەکان (ئەدمین، کارمەند، بینەر) و تۆماری گۆڕانکارییەکان</p>
        </div>

        <x-button primary icon="user-plus" wire:click="openModal" class="font-bold">
            زیادکردنی بەکارهێنەری نوێ
        </x-button>
    </div>

    <!-- Users Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($users as $user)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center text-lg">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ $user->name }}</h3>
                        <p class="text-xs text-slate-500 font-mono">{{ $user->email }} ({{ $user->username }})</p>
                        <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-700">
                            {{ $user->role->label() }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
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
    <x-modal wire:model="showCreateModal">
        <x-card title="زیادکردنی بەکارهێنەری نوێ">
            <form wire:submit="save" class="space-y-4">
                <x-input wire:model="name" label="ناوی ناوخۆیی (Full Name) *" placeholder="کارمەند..." />
                <x-input wire:model="username" label="ناوی بەکارهێنەر (Username) *" placeholder="user123" />
                <x-input type="email" wire:model="email" label="ئیمەیڵ *" placeholder="user@hemophilia.org" />
                <x-password wire:model="password" label="وشەی نهێنی (Password) *" />
                <x-native-select wire:model="role" label="دەسەڵاتی بەکارهێنەر">
                    <option value="admin">ئەدمین (دەسەڵاتی تەواو)</option>
                    <option value="staff">کارمەند (زیادکردن و نوێکردنەوە)</option>
                    <option value="viewer">بینەر (تەنها بینین)</option>
                </x-native-select>

                <div class="flex justify-end gap-2 pt-2">
                    <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                    <x-button type="submit" primary label="پاشەکەوتکردن" class="font-bold" />
                </div>
            </form>
        </x-card>
    </x-modal>
</div>
