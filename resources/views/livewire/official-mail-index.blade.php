<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">بەڵگەنامەکانی هاتوو و ڕۆشتوو</h1>
            <p class="text-xs text-slate-500 mt-1">ئەرشیف و تۆماری گشتی نوسراوە فەرمییەکانی کۆمەڵە بەپێی بەشی هاتوو و ڕۆشتوو</p>
        </div>

        @if(!auth()->user()->isViewer())
            <x-button primary icon="plus" wire:click="$set('showModal', true)" class="font-bold">
                تۆمارکردنی نوسراوی نوێ
            </x-button>
        @endif
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row items-center gap-4">
        <x-input wire:model.live.debounce.300ms="search" placeholder="گەڕان بە ژمارەی نوسراو، لایەن یان بابەت..." icon="magnifying-glass" class="w-full sm:w-80" />
        
        <x-native-select wire:model.live="filter_direction" class="w-full sm:w-56">
            <option value="">هەموو نوسراوەکان</option>
            <option value="incoming">بەشی هاتوو (Incoming)</option>
            <option value="outgoing">بەشی ڕۆشتوو (Outgoing)</option>
        </x-native-select>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                    <tr>
                        <th class="p-4">ژمارەی نوسراو</th>
                        <th class="p-4">جۆری نوسراو</th>
                        <th class="p-4">بەروار</th>
                        <th class="p-4">نەخۆشی پەیوەندیدار</th>
                        <th class="p-4">نێرەر / وەرگر (لایەن)</th>
                        <th class="p-4">هۆکار / بابەت</th>
                        <th class="p-4 text-center">فایلی بارکراو</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($mails as $m)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-4 font-mono font-bold text-slate-900 dark:text-white">
                                {{ $m->mail_number }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded text-xs font-bold {{ $m->direction->value === 'incoming' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $m->direction->label() }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">
                                {{ $m->mail_date->format('Y-m-d') }}
                            </td>
                            <td class="p-4 font-bold text-slate-800 dark:text-white">
                                @if($m->patient)
                                    <a href="{{ route('patients.show', $m->patient_id) }}" class="hover:text-red-600">
                                        {{ $m->patient->full_name }}
                                    </a>
                                @else
                                    <span class="text-slate-400 font-normal">گشتی</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-700 dark:text-slate-300">
                                {{ $m->sender_recipient }}
                            </td>
                            <td class="p-4 text-slate-700 dark:text-slate-300">
                                {{ $m->reason_subject }}
                            </td>
                            <td class="p-4 text-center">
                                @if($m->file_path)
                                    <a href="{{ Storage::url($m->file_path) }}" target="_blank" class="text-xs font-bold text-red-600 hover:underline">
                                        بینینی فایل
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                هیچ نوسراوێک نەدۆزرایەوە.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $mails->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal wire:model="showModal">
        <x-card title="تۆمارکردنی نوسراوی نوێ (هاتوو / ڕۆشتوو)">
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input wire:model="mail_number" label="ژمارەی نوسراو *" placeholder="MAIL-2026-001" />
                    <x-native-select wire:model="direction" label="جۆری نوسراو">
                        <option value="incoming">بەشی هاتوو (Incoming)</option>
                        <option value="outgoing">بەشی ڕۆشتوو (Outgoing)</option>
                    </x-native-select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input type="date" wire:model="mail_date" label="بەرواری نوسراو *" />
                    <x-native-select wire:model="patient_id" label="نەخۆشی پەیوەندیدار (ئەگەر هەیە)">
                        <option value="">نەخۆشی پەیوەندیدار نییە (نوسراوی گشتی)</option>
                        @foreach($allPatients as $p)
                            <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->patient_code }})</option>
                        @endforeach
                    </x-native-select>
                </div>

                <x-input wire:model="sender_recipient" label="لایەنی نێرەر / وەرگر *" placeholder="نەخۆشخانەی هیوا / وەزارەتی تەندروستی..." />
                <x-input wire:model="reason_subject" label="هۆکار / بابەتی نوسراو *" placeholder="داواکاری فاکتەر / نوسراوی پشتگیری..." />
                
                <input type="file" wire:model="file" class="w-full text-xs text-slate-500 border p-2 rounded-lg" />

                <div class="flex justify-end gap-2 pt-2">
                    <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                    <x-button type="submit" primary label="پاشەکەوتکردن" class="font-bold" />
                </div>
            </form>
        </x-card>
    </x-modal>
</div>
