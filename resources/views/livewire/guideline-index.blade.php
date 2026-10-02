<div class="space-y-6">
    <x-flash-messages />

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">ڕێنماییەکانی کۆمەڵە</h1>
            <p class="text-xs text-slate-500 mt-1">فایلە گشتییەکانی کۆمەڵە (مەرجەکانی ئەندامبوون، بڕیارەکان، داواکارییە فەرمییەکان...) بە شێوەی PDF</p>
        </div>

        @can('edit-records')
            <x-button primary icon="plus" wire:click="openCreateModal" class="font-bold shadow-lg shadow-rose-600/25">
                زیادکردنی ڕێنمایی / بڕیار
            </x-button>
        @endcan
    </div>

    @if (session()->has('message'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 text-xs font-bold">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row items-center gap-4">
        <x-input wire:model.live.debounce.300ms="search" placeholder="گەڕان بە ناونیشان یان ژمارەی نوسراو..." icon="magnifying-glass" class="w-full sm:w-80" />

        <x-native-select wire:model.live="year" class="w-full sm:w-44">
            <option value="">هەموو ساڵەکان</option>
            @foreach($years as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </x-native-select>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-4">بەروار</th>
                        <th class="p-4">ناونیشان</th>
                        <th class="p-4">ژمارەی نوسراو</th>
                        <th class="p-4">تێبینی</th>
                        <th class="p-4 text-center">فایل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($guidelines as $g)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-4 font-mono text-slate-600 dark:text-slate-300 whitespace-nowrap">{{ $g->document_date->format('Y/m/d') }}</td>
                            <td class="p-4 font-bold text-slate-900 dark:text-white">{{ $g->title }}</td>
                            <td class="p-4 font-mono text-slate-600 dark:text-slate-300">{{ $g->reference_number ?: '—' }}</td>
                            <td class="p-4 text-xs text-slate-500 max-w-xs">{{ $g->description ?: '—' }}</td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('guidelines.file', $g) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 font-bold text-xs hover:bg-rose-100 transition flex items-center gap-1" title="کردنەوەی PDF">
                                        <x-icon name="document-text" class="w-4 h-4" />
                                        <span>PDF</span>
                                    </a>

                                    @can('edit-records')
                                        <button wire:click="edit({{ $g->id }})" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 transition" title="دەستکاریکردن">
                                            <x-icon name="pencil-square" class="w-4 h-4" />
                                        </button>
                                    @endcan

                                    @can('delete-records')
                                        <button wire:click="delete({{ $g->id }})" wire:confirm="ئایا دڵنیایت لە سڕینەوەی ئەم ڕێنماییە و فایلەکەی؟" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 transition" title="سڕینەوە">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">هیچ ڕێنمایی یان بڕیارێک تۆمار نەکراوە.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $guidelines->links() }}
        </div>
    </div>

    <x-modal-card :title="$editingId ? 'دەستکاریکردنی ڕێنمایی' : 'زیادکردنی ڕێنمایی / بڕیاری کۆمەڵە'" wire:model="showModal" max-width="lg">
        <div class="space-y-4">
            <x-input wire:model="title" label="ناونیشان *" placeholder="نموونە: مەرجەکانی ئەندامبوون لە کۆمەڵە" />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-datetime-picker wire:model="document_date" label="بەرواری نوسراو *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
                <x-input wire:model="reference_number" label="ژمارەی نوسراو" placeholder="ئارەزوومەندانە" />
            </div>

            <x-textarea wire:model="description" label="تێبینی / کورتە" placeholder="نموونە: بڕیاری دەرکردنی ئەندام لە کۆمەڵە..." />

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    فایلی PDF {{ $editingId ? '(بۆ گۆڕینی فایلەکە)' : '*' }}
                </label>
                <input type="file" wire:model="file" accept="application/pdf" class="block w-full text-sm text-slate-600 dark:text-slate-300 file:me-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100" />
                <div wire:loading wire:target="file" class="text-xs text-slate-500 mt-1">بارکردن...</div>
                @error('file') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="پاشەکەوتکردن" wire:click="save" spinner="save" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </x-slot:footer>
    </x-modal-card>
</div>
