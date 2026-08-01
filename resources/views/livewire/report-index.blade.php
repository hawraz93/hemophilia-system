<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">تۆماری ڕاپۆرتەکان و دەرکردنی داتا</h1>
            <p class="text-xs text-slate-500 mt-1">دروستکردنی ڕاپۆرتی تایبەتمەند بە دیاریکردنی ستوونە چاپکراوەکان لە لایەن ئەدمینەوە</p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="exportExcel" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-md shadow-emerald-600/20">
                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                <span>داگرتن بە فایلی Excel (Excel Export)</span>
            </button>

            <button onclick="window.print()" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-black transition flex items-center gap-2 shadow">
                <x-icon name="printer" class="w-4 h-4" />
                <span>چاپکردنی ڕاپۆرت (Print / PDF)</span>
            </button>
        </div>
    </div>

    <!-- Report Type Selector -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm space-y-3 print:hidden">
        <label class="text-xs font-extrabold text-slate-500 block mb-1">جۆری ڕاپۆرت هەڵبژێرە:</label>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2">
            <button wire:click="$set('reportType', 'all_patients')" class="p-2.5 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'all_patients' ? 'bg-red-600 text-white border-red-600 shadow' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                هەموو نەخۆشەکان
            </button>

            <button wire:click="$set('reportType', 'hemophilia_a')" class="p-2.5 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'hemophilia_a' ? 'bg-red-600 text-white border-red-600 shadow' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                هیمۆفیلیای A
            </button>

            <button wire:click="$set('reportType', 'hemophilia_b')" class="p-2.5 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'hemophilia_b' ? 'bg-red-600 text-white border-red-600 shadow' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                هیمۆفیلیای B
            </button>

            <button wire:click="$set('reportType', 'von_willebrand')" class="p-2.5 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'von_willebrand' ? 'bg-indigo-600 text-white border-indigo-600 shadow' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                ڤۆن ویلی براند
            </button>

            <button wire:click="$set('reportType', 'other_bleeding')" class="p-2.5 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'other_bleeding' ? 'bg-purple-600 text-white border-purple-600 shadow' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                خوێنبەربوونی تر
            </button>

            <button wire:click="$set('reportType', 'red_list')" class="p-2.5 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'red_list' ? 'bg-rose-600 text-white border-rose-600 shadow' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                لیستی سوور
            </button>

            <button wire:click="$set('reportType', 'incomplete_data')" class="p-2.5 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'incomplete_data' ? 'bg-amber-500 text-white border-amber-500 shadow' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                داتای ناتەواو
            </button>

            <button wire:click="$set('reportType', 'assistance_summary')" class="p-2.5 rounded-xl text-xs font-bold transition border text-center {{ $reportType === 'assistance_summary' ? 'bg-emerald-600 text-white border-emerald-600 shadow' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                ڕاپۆرتی هاوکاری
            </button>
        </div>
    </div>

    <!-- Admin Column Selection Panel (Only shown for patient reports) -->
    @if(in_array($reportType, ['all_patients', 'hemophilia_a', 'hemophilia_b', 'von_willebrand', 'other_bleeding', 'red_list', 'incomplete_data']))
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm space-y-3 print:hidden">
            <div class="flex items-center justify-between border-b pb-2">
                <div class="flex items-center gap-2">
                    <x-icon name="adjustments-horizontal" class="w-4 h-4 text-rose-600" />
                    <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200">دەسەڵاتی ئەدمین: دیاریکردنی ستوونە چاپکراوەکان و ئێکسپۆرتی Excel</span>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="selectAllColumns" class="text-[11px] font-bold text-indigo-600 hover:underline">دیاریکردنی هەمووان</button>
                    <span class="text-slate-300">•</span>
                    <button wire:click="deselectAllColumns" class="text-[11px] font-bold text-slate-500 hover:underline">لابردنی سەرجەم</button>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 text-xs">
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('patient_code')" {{ $selectedColumns['patient_code'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">کۆدی نەخۆش</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('full_name')" {{ $selectedColumns['full_name'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">ناوی سیانی</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('phone')" {{ $selectedColumns['phone'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">ژمارەی مۆبایل</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('party_affiliation')" {{ $selectedColumns['party_affiliation'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">پەیوەندخوازە بە (حیزبی)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('voting_card_number')" {{ $selectedColumns['voting_card_number'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">کارتی دەنگدان</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('national_id')" {{ $selectedColumns['national_id'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">کارتی نیشتمانی</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('address')" {{ $selectedColumns['address'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">شوێنی نیشتەجێبوون</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('dob')" {{ $selectedColumns['dob'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">موالید / تەمەن</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('hemophilia_type')" {{ $selectedColumns['hemophilia_type'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">جۆری نەخۆشی</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('membership_type')" {{ $selectedColumns['membership_type'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">پلەی ئەندامێتی</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100">
                    <input type="checkbox" wire:click="toggleColumn('list_status')" {{ $selectedColumns['list_status'] ? 'checked' : '' }} class="rounded text-rose-600 focus:ring-rose-500">
                    <span class="font-bold">دۆخی ئەندام (تەواو/سەوز)</span>
                </label>
            </div>
        </div>
    @endif

    <!-- Report Printable Area -->
    <div id="printable-report" class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4 print:p-0 print:border-none print:shadow-none">
        <div class="text-center border-b pb-4">
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی</h2>
            <p class="text-xs text-slate-500 font-bold mt-1">
                ڕاپۆرتی فەرمی — به بەرواری: {{ date('Y-m-d') }}
            </p>
        </div>

        <div class="overflow-x-auto">
            @if(in_array($reportType, ['all_patients', 'hemophilia_a', 'hemophilia_b', 'von_willebrand', 'other_bleeding', 'red_list', 'incomplete_data']))
                <table class="w-full text-end text-sm border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-600 font-bold border-b">
                        <tr>
                            <th class="p-2.5">#</th>
                            @if($selectedColumns['patient_code']) <th class="p-2.5">کۆدی نەخۆش</th> @endif
                            @if($selectedColumns['full_name']) <th class="p-2.5">ناوی تەواو</th> @endif
                            @if($selectedColumns['phone']) <th class="p-2.5">ژمارەی مۆبایل</th> @endif
                            @if($selectedColumns['party_affiliation']) <th class="p-2.5">پەیوەندخوازە بە</th> @endif
                            @if($selectedColumns['voting_card_number']) <th class="p-2.5">کارتی دەنگدان</th> @endif
                            @if($selectedColumns['national_id']) <th class="p-2.5">کارتی نیشتمانی</th> @endif
                            @if($selectedColumns['address']) <th class="p-2.5">شوێنی نیشتەجێبوون</th> @endif
                            @if($selectedColumns['dob']) <th class="p-2.5">موالید / تەمەن</th> @endif
                            @if($selectedColumns['hemophilia_type']) <th class="p-2.5">جۆری نەخۆشی</th> @endif
                            @if($selectedColumns['membership_type']) <th class="p-2.5">پلەی ئەندامێتی</th> @endif
                            @if($selectedColumns['list_status']) <th class="p-2.5 text-center">دۆخ</th> @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($data as $index => $p)
                            <tr>
                                <td class="p-2.5 text-xs text-slate-400 font-mono">{{ $index + 1 }}</td>

                                @if($selectedColumns['patient_code'])
                                    <td class="p-2.5 font-mono font-bold text-slate-700 dark:text-slate-200">{{ $p->patient_code }}</td>
                                @endif

                                @if($selectedColumns['full_name'])
                                    <td class="p-2.5 font-extrabold text-slate-900 dark:text-white">{{ $p->full_name }}</td>
                                @endif

                                @if($selectedColumns['phone'])
                                    <td class="p-2.5 font-mono text-slate-700 dark:text-slate-300">{{ $p->phone }}</td>
                                @endif

                                @if($selectedColumns['party_affiliation'])
                                    <td class="p-2.5">
                                        @if($p->party_affiliation)
                                            <span class="px-2 py-0.5 rounded text-xs font-extrabold border inline-block {{ $p->party_affiliation->badgeClasses() }}">
                                                {{ $p->party_affiliation->label() }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs">—</span>
                                        @endif
                                    </td>
                                @endif

                                @if($selectedColumns['voting_card_number'])
                                    <td class="p-2.5 font-mono text-xs">{{ $p->voting_card_number ?? '—' }}</td>
                                @endif

                                @if($selectedColumns['national_id'])
                                    <td class="p-2.5 font-mono text-xs">{{ $p->national_id ?? '—' }}</td>
                                @endif

                                @if($selectedColumns['address'])
                                    <td class="p-2.5 text-xs text-slate-600 dark:text-slate-300">{{ $p->governorate }} - {{ $p->district }}</td>
                                @endif

                                @if($selectedColumns['dob'])
                                    <td class="p-2.5 text-xs font-mono">{{ $p->dob?->format('Y-m-d') ?? '—' }} ({{ $p->age }} ساڵ)</td>
                                @endif

                                @if($selectedColumns['hemophilia_type'])
                                    <td class="p-2.5 text-rose-600 font-bold text-xs">{{ $p->hemophilia_type?->label() }}</td>
                                @endif

                                @if($selectedColumns['membership_type'])
                                    <td class="p-2.5 text-indigo-600 font-bold text-xs">{{ $p->membership_type?->label() ?? 'ئەندامی ئاسایی' }}</td>
                                @endif

                                @if($selectedColumns['list_status'])
                                    <td class="p-2.5 text-center">
                                        <x-badge :color="$p->list_status->color()" :label="$p->list_status->label()" />
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="p-8 text-center text-slate-400">هیچ نەخۆشێک نەدۆزرایەوە.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif($reportType === 'assistance_summary')
                <table class="w-full text-end text-sm border-collapse">
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
            @endif
        </div>
    </div>
</div>
