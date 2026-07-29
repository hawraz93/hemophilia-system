<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">تۆماری بەدواداچوون و پەیوەندییەکان</h1>
        <p class="text-xs text-slate-500 mt-1">تۆماری گشتی پەیوەندییە تەلەفۆنی، واتسئەپ و سەردانەکان بۆ نەخۆشەکان</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
        <x-input wire:model.live.debounce.300ms="search" placeholder="گەڕان بە ناوی نەخۆش یان کۆد..." icon="magnifying-glass" class="w-full sm:w-80" />
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-4">ناوی نەخۆش</th>
                        <th class="p-4">بەروار</th>
                        <th class="p-4">جۆری پەیوەندی</th>
                        <th class="p-4">دۆخی پەیوەندی</th>
                        <th class="p-4">تێبینی و ئەنجام</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($contacts as $c)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-4 font-bold text-slate-900 dark:text-white">
                                <a href="{{ route('patients.show', $c->patient_id) }}" class="hover:text-red-600">
                                    {{ $c->patient?->full_name }}
                                </a>
                                <span class="block text-xs font-normal text-slate-400">{{ $c->patient?->patient_code }}</span>
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">
                                {{ $c->contact_date->format('Y-m-d') }}
                            </td>
                            <td class="p-4 font-bold text-slate-800 dark:text-white">
                                {{ $c->channel->label() }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded text-xs font-bold {{ $c->is_successful ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $c->is_successful ? 'سەرکەوتوو' : 'بێوەڵام' }}
                                </span>
                            </td>
                            <td class="p-4 text-xs text-slate-600 dark:text-slate-300">
                                {{ $c->outcome_notes ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">
                                هیچ پەیوەندییەک نەدۆزرایەوە.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
