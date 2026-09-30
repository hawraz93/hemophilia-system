<div class="space-y-6">
    <x-flash-messages />

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">تۆماری گشتی هاوکاری و یارمەتییەکان</h1>
            <p class="text-xs text-slate-500 mt-1">تۆمارکردن و بەدواداچوونی گشت هاوکارییە دارایی، دەرمانی، و بەشەخۆراکەکان</p>
        </div>

        @if(!auth()->user()->isViewer())
            <x-button primary icon="plus" wire:click="openModal" class="font-bold">
                تۆمارکردنی هاوکاری نوێ
            </x-button>
        @endif
    </div>

    <!-- Filters & Stats -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <x-input wire:model.live.debounce.300ms="search" placeholder="گەڕان بە ناوی نەخۆش یان ژمارەی هاوکاری..." icon="magnifying-glass" class="w-full sm:w-72" />
            
            <x-native-select wire:model.live="filter_category" class="w-full sm:w-56">
                <option value="">هەموو جۆرەکانی هاوکاری</option>
                <option value="financial">هاوکاری دارایی</option>
                <option value="medication">دەرمان</option>
                <option value="food">خواردن و بەشەخۆراک</option>
                <option value="medical_supplies">کەرەستەی پزشکی</option>
                <option value="surgery">نەشتەرگەری</option>
                <option value="transport">گواستنەوە</option>
                <option value="other">هاوکاری تر</option>
            </x-native-select>
        </div>

        <div class="bg-emerald-50 dark:bg-emerald-950/40 px-4 py-2 rounded-xl border border-emerald-200 dark:border-emerald-800 text-center sm:text-end shrink-0">
            <span class="text-xs text-emerald-700 dark:text-emerald-400 font-bold block">کۆی بڕی هاوکارییە فلتەرکراوەکان</span>
            <span class="text-lg font-black text-emerald-900 dark:text-emerald-100">{{ number_format($totalAmount) }} IQD</span>
        </div>
    </div>

    <!-- Assistance Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-4">ژمارەی هاوکاری</th>
                        <th class="p-4">ناوی نەخۆش</th>
                        <th class="p-4">بەروار</th>
                        <th class="p-4">جۆری هاوکاری</th>
                        <th class="p-4">سەرچاوەی هاوکاری</th>
                        <th class="p-4">بڕی پارە (IQD)</th>
                        <th class="p-4">تێبینی</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($assistances as $aid)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-4 font-mono font-bold text-slate-600 dark:text-slate-300">
                                {{ $aid->assistance_number }}
                            </td>
                            <td class="p-4 font-bold text-slate-900 dark:text-white">
                                <a href="{{ route('patients.show', $aid->patient_id) }}" class="hover:text-red-600">
                                    {{ $aid->patient?->full_name }}
                                </a>
                                <span class="block text-xs font-normal text-slate-400">{{ $aid->patient?->patient_code }}</span>
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">
                                {{ $aid->assistance_date->format('Y-m-d') }}
                            </td>
                            <td class="p-4 font-bold text-slate-800 dark:text-white">
                                {{ $aid->category->label() }}
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-300">
                                {{ $aid->source_funder ?? '—' }}
                            </td>
                            <td class="p-4 font-black text-emerald-600">
                                {{ number_format($aid->amount) }}
                            </td>
                            <td class="p-4 text-xs text-slate-500 max-w-xs truncate">
                                {{ $aid->notes ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                هیچ هاوکارییەک نەدۆزرایەوە.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $assistances->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal-card title="تۆمارکردنی هاوکاری نوێ" wire:model="showCreateModal" max-width="lg">
        <div class="space-y-4">
            <x-native-select wire:model="patient_id" label="هەڵبژاردنی نەخۆش *">
                <option value="">نەخۆش هەڵبژێرە...</option>
                @foreach($allPatients as $p)
                    <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->patient_code }})</option>
                @endforeach
            </x-native-select>

            <x-datetime-picker wire:model="assistance_date" label="بەرواری هاوکاری *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />

            <x-native-select wire:model="category" label="جۆری هاوکاری">
                <option value="financial">هاوکاری دارایی</option>
                <option value="medication">دەرمان</option>
                <option value="food">خواردن و بەشەخۆراک</option>
                <option value="medical_supplies">کەرەستەی پزشکی</option>
                <option value="surgery">نەشتەرگەری</option>
                <option value="transport">گواستنەوە</option>
                <option value="other">هاوکاری تر</option>
            </x-native-select>

            <x-currency wire:model="amount" label="بڕی پارە (IQD) *" thousands="," :precision="0" placeholder="0" />
            <x-input wire:model="source_funder" label="سەرچاوەی هاوکاری" placeholder="خێرخواز / رێکخراو" />
            <x-textarea wire:model="notes" label="تێبینی" />
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="پاشەکەوتکردن" wire:click="save" spinner="save" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </x-slot:footer>
    </x-modal-card>
</div>
