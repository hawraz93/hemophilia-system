<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">ئەندامێتی و مێژووی رسومات</h1>
            <p class="text-xs text-slate-500 mt-1">بەدواداچوونی ئەندامێتی، پارەدانی سالانە و وەسلەکانی کۆمەڵە</p>
        </div>
    </div>

    <!-- Association funds -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-slate-500 block">کۆی داهاتی ئەندامێتی</span>
            <span class="text-lg font-black text-blue-700 dark:text-blue-300">{{ number_format($finance['membership_income']) }} IQD</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-slate-500 block">هاوکاری دراو لە داهاتی ئەندامێتی</span>
            <span class="text-lg font-black text-rose-600">− {{ number_format($finance['spent_from_membership']) }} IQD</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-emerald-300 dark:border-emerald-800 shadow-sm">
            <span class="text-xs font-bold text-slate-500 block">ماوەی داهاتی ئەندامێتی</span>
            <span class="text-lg font-black text-emerald-600">{{ number_format($finance['membership_balance']) }} IQD</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-emerald-300 dark:border-emerald-800 shadow-sm">
            <span class="text-xs font-bold text-slate-500 block">باڵانسی داهاتی گشتی کۆمەڵە</span>
            <span class="text-lg font-black text-emerald-700 dark:text-emerald-300">{{ number_format($finance['general_balance']) }} IQD</span>
            <span class="text-[10px] text-slate-400 block">دوای هاوکارییەکانی داهاتی گشتی: −{{ number_format($finance['spent_from_general']) }}</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-slate-500 block">ئەندامانی لێخۆشبوو (٠ دینار)</span>
            <span class="text-lg font-black text-amber-600">{{ $finance['exempt_members'] }}</span>
        </div>
    </div>

    @if($internalSpending->isNotEmpty())
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">دوایین هاوکارییەکان لە داهاتی کۆمەڵە</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-end text-sm">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($internalSpending as $aid)
                            <tr>
                                <td class="p-3 font-mono text-slate-500">{{ $aid->assistance_date->format('Y-m-d') }}</td>
                                <td class="p-3 font-bold">
                                    <a href="{{ route('patients.show', $aid->patient_id) }}" class="hover:text-red-600">{{ $aid->patient?->full_name }}</a>
                                </td>
                                <td class="p-3 text-xs font-bold">{{ $aid->funding_source->shortLabel() }}</td>
                                <td class="p-3 font-black text-rose-600">− {{ number_format($aid->amount) }}</td>
                                <td class="p-3 text-xs text-slate-500">{{ $aid->notes ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <x-input wire:model.live.debounce.300ms="search" placeholder="گەڕان بە ناوی نەخۆش یان ژمارەی ئەندامێتی..." icon="magnifying-glass" class="w-full sm:w-80" />

        <div class="bg-blue-50 dark:bg-blue-950/40 px-4 py-2 rounded-xl border border-blue-200 dark:border-blue-800 text-center sm:text-end shrink-0">
            <span class="text-xs text-blue-700 dark:text-blue-400 font-bold block">کۆی کۆکراوەی رسوماتی ئەندامێتی</span>
            <span class="text-lg font-black text-blue-900 dark:text-blue-100">{{ number_format($totalCollected) }} IQD</span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-4">ژمارەی ئەندامێتی</th>
                        <th class="p-4">ناوی نەخۆش</th>
                        <th class="p-4">بەرواری پارەدان</th>
                        <th class="p-4">ژمارەی وەسڵ</th>
                        <th class="p-4">بڕی بڕدراو (IQD)</th>
                        <th class="p-4">تێبینی</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-4 font-mono font-bold text-slate-600 dark:text-slate-300">
                                {{ $pay->patient?->membership_number ?? '—' }}
                            </td>
                            <td class="p-4 font-bold text-slate-900 dark:text-white">
                                <a href="{{ route('patients.show', $pay->patient_id) }}" class="hover:text-red-600">
                                    {{ $pay->patient?->full_name }}
                                </a>
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">
                                {{ $pay->payment_date->format('Y-m-d') }}
                            </td>
                            <td class="p-4 font-mono text-slate-700 dark:text-slate-300">
                                {{ $pay->receipt_number ?? '—' }}
                            </td>
                            <td class="p-4 font-black text-emerald-600">
                                {{ number_format($pay->amount_paid) }}
                                @if($pay->is_exempt)
                                    <span class="ms-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950 dark:text-amber-300">لێخۆشبوون</span>
                                @endif
                            </td>
                            <td class="p-4 text-xs text-slate-500">
                                {{ $pay->notes ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                هیچ دراوێک تۆمار نەکراوە.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $payments->links() }}
        </div>
    </div>
</div>
