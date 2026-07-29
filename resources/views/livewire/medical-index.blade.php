<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">تۆماری چاودێری تەندروستی و پزیشکی</h1>
        <p class="text-xs text-slate-500 mt-1">تۆمارکردنی سەردانەکانی نەخۆشخانە، ژەمە فاکتەرەکان، داخڵبوون و تاقیکردنەوەکان</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
        <x-input wire:model.live.debounce.300ms="search" placeholder="گەڕان بە ناوی نەخۆش، نەخۆشخانە یان دۆزی فاکتەر..." icon="magnifying-glass" class="w-full sm:w-80" />
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-4">ناوی نەخۆش</th>
                        <th class="p-4">بەروار</th>
                        <th class="p-4">جۆری تۆمار</th>
                        <th class="p-4">نەخۆشخانە</th>
                        <th class="p-4">Factor Name / Dose</th>
                        <th class="p-4">وردەکاری</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-4 font-bold text-slate-900 dark:text-white">
                                <a href="{{ route('patients.show', $log->patient_id) }}" class="hover:text-red-600">
                                    {{ $log->patient?->full_name }}
                                </a>
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">
                                {{ $log->log_date->format('Y-m-d') }}
                            </td>
                            <td class="p-4 font-bold text-red-600">
                                {{ $log->log_type->label() }}
                            </td>
                            <td class="p-4 text-slate-700 dark:text-slate-300">
                                {{ $log->hospital_name ?? '—' }}
                            </td>
                            <td class="p-4 font-mono font-bold text-slate-700 dark:text-slate-300">
                                {{ $log->factor_name_dose ?? '—' }}
                            </td>
                            <td class="p-4 text-xs text-slate-500 max-w-xs truncate">
                                {{ $log->details ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                هیچ تۆمارێکی پزیشکی نەدۆزرایەوە.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $logs->links() }}
        </div>
    </div>
</div>
