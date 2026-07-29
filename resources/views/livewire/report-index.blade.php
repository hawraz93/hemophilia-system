<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">تۆماری ڕاپۆرتەکان و دەرکردنی داتا</h1>
            <p class="text-xs text-slate-500 mt-1">دروستکردنی ڕاپۆرتی گشتی، هیمۆفیلیا A/B، لیستی سوور، هاوکارییەکان و پرینتکردن</p>
        </div>

        <button onclick="window.print()" class="px-4 py-2 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-black transition flex items-center gap-2 shadow">
            <x-icon name="printer" class="w-4 h-4" />
            <span>چاپکردنی ڕاپۆرت (Print / PDF)</span>
        </button>
    </div>

    <!-- Report Type Selector -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
        <label class="text-xs font-bold text-slate-500 block mb-2">جۆری ڕاپۆرت هەڵبژێرە:</label>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2">
            <button wire:click="$set('reportType', 'all_patients')" class="p-3 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'all_patients' ? 'bg-red-600 text-white border-red-600' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                هەموو نەخۆشەکان
            </button>

            <button wire:click="$set('reportType', 'hemophilia_a')" class="p-3 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'hemophilia_a' ? 'bg-red-600 text-white border-red-600' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                هیمۆفیلیا A
            </button>

            <button wire:click="$set('reportType', 'hemophilia_b')" class="p-3 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'hemophilia_b' ? 'bg-red-600 text-white border-red-600' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                هیمۆفیلیا B
            </button>

            <button wire:click="$set('reportType', 'red_list')" class="p-3 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'red_list' ? 'bg-rose-600 text-white border-rose-600' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                لیستی سوور
            </button>

            <button wire:click="$set('reportType', 'incomplete_data')" class="p-3 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'incomplete_data' ? 'bg-amber-500 text-white border-amber-500' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                داتای ناتەواو
            </button>

            <button wire:click="$set('reportType', 'assistance_summary')" class="p-3 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'assistance_summary' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                ڕاپۆرتی هاوکاری
            </button>

            <button wire:click="$set('reportType', 'membership_summary')" class="p-3 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'membership_summary' ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                ڕاپۆرتی ئەندامێتی
            </button>
        </div>
    </div>

    <!-- Report Printable Area -->
    <div id="printable-report" class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
        <div class="text-center border-b pb-4">
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی</h2>
            <p class="text-xs text-slate-500 font-bold mt-1">
                ڕاپۆرتی {{ str_replace('_', ' ', $reportType) }} — به بەرواری: {{ date('Y-m-d') }}
            </p>
        </div>

        <div class="overflow-x-auto">
            @if(in_array($reportType, ['all_patients', 'hemophilia_a', 'hemophilia_b', 'red_list', 'incomplete_data']))
                <table class="w-full text-end text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                        <tr>
                            <th class="p-3">کۆد</th>
                            <th class="p-3">ناوی تەواو</th>
                            <th class="p-3">جۆری هیمۆفیلیا</th>
                            <th class="p-3">گروپی خوێن</th>
                            <th class="p-3">ژ. مۆبایل</th>
                            <th class="p-3">ناونیشان</th>
                            <th class="p-3 text-center">لیست</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($data as $p)
                            <tr>
                                <td class="p-3 font-mono font-bold text-slate-600">{{ $p->patient_code }}</td>
                                <td class="p-3 font-bold text-slate-900 dark:text-white">{{ $p->full_name }}</td>
                                <td class="p-3 text-red-600 font-bold">{{ $p->hemophilia_type?->label() }}</td>
                                <td class="p-3 font-bold">{{ $p->blood_group?->value ?? '—' }}</td>
                                <td class="p-3 font-mono">{{ $p->phone }}</td>
                                <td class="p-3 text-xs text-slate-500">{{ $p->governorate }} - {{ $p->district }}</td>
                                <td class="p-3 text-center">
                                    <x-badge :color="$p->list_status->color()" :label="$p->list_status->label()" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">هیچ نەخۆشێک نەدۆزرایەوە.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($reportType === 'assistance_summary')
                <table class="w-full text-end text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                        <tr>
                            <th class="p-3">ژ. هاوکاری</th>
                            <th class="p-3">ناوی نەخۆش</th>
                            <th class="p-3">بەروار</th>
                            <th class="p-3">جۆری هاوکاری</th>
                            <th class="p-3">سەرچاوە</th>
                            <th class="p-3">بڕی پارە (IQD)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($data as $aid)
                            <tr>
                                <td class="p-3 font-mono font-bold">{{ $aid->assistance_number }}</td>
                                <td class="p-3 font-bold">{{ $aid->patient?->full_name }}</td>
                                <td class="p-3 text-slate-500">{{ $aid->assistance_date->format('Y-m-d') }}</td>
                                <td class="p-3 font-bold">{{ $aid->category->label() }}</td>
                                <td class="p-3">{{ $aid->source_funder ?? '—' }}</td>
                                <td class="p-3 font-black text-emerald-600">{{ number_format($aid->amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">هیچ تۆمارێک نییە.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($reportType === 'membership_summary')
                <table class="w-full text-end text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                        <tr>
                            <th class="p-3">ژ. ئەندامێتی</th>
                            <th class="p-3">ناوی نەخۆش</th>
                            <th class="p-3">بەرواری پارەدان</th>
                            <th class="p-3">ژ. وەسڵ</th>
                            <th class="p-3">بڕی دراو (IQD)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($data as $pay)
                            <tr>
                                <td class="p-3 font-mono font-bold">{{ $pay->patient?->membership_number ?? '—' }}</td>
                                <td class="p-3 font-bold">{{ $pay->patient?->full_name }}</td>
                                <td class="p-3 text-slate-500">{{ $pay->payment_date->format('Y-m-d') }}</td>
                                <td class="p-3 font-mono">{{ $pay->receipt_number ?? '—' }}</td>
                                <td class="p-3 font-black text-emerald-600">{{ number_format($pay->amount_paid) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-400">هیچ تۆمارێک نییە.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
