<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">ئەندامێتی و مێژووی رسومات</h1>
            <p class="text-xs text-slate-500 mt-1">بەدواداچوونی ئەندامێتی، پارەدانی سالانە و وەسلەکانی کۆمەڵە</p>
        </div>
    </div>

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
