<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">بەڵگەنامەکانی هاتوو و ڕۆشتوو</h1>
            <p class="text-xs text-slate-500 mt-1">ئەرشیف و تۆماری گشتی نوسراوە فەرمییەکانی کۆمەڵە بەپێی بەشی هاتوو و ڕۆشتوو</p>
        </div>

        @if(!auth()->user()->isViewer())
            <x-button primary icon="plus" wire:click="$set('showModal', true)" class="font-bold shadow-lg shadow-rose-600/25">
                تۆمارکردنی نوسراوی نوێ
            </x-button>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 text-xs font-bold">
            {{ session('message') }}
        </div>
    @endif

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
                        <th class="p-4 text-center">جۆری مۆر</th>
                        <th class="p-4 text-center">فایل و چاپکردن</th>
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
                                @if($m->stamp_type === 'online')
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        🟢 مۆری دیجیتاڵی
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        ✍️ مۆری دەستی
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center flex items-center justify-center gap-2">
                                @if($m->file_path)
                                    <a href="{{ Storage::url($m->file_path) }}" target="_blank" class="p-1.5 rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200 hover:bg-slate-200 transition" title="بینینی فایلی بارکراو">
                                        <x-icon name="paper-clip" class="w-4 h-4" />
                                    </a>
                                @endif

                                <a href="{{ route('mails.print', $m) }}" target="_blank" class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950 dark:text-indigo-400 transition" title="چاپکردنی نوسراو (Print A4)">
                                    <x-icon name="printer" class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
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
    <x-modal-card title="تۆمارکردن / دروستکردنی نوسراوی نوێ" wire:model="showModal" max-width="lg">
        <div class="space-y-4">
            <!-- Template Picker -->
            <div class="bg-indigo-50/70 dark:bg-indigo-950/40 p-3.5 rounded-2xl border border-indigo-200 dark:border-indigo-800 space-y-2">
                <label class="block text-xs font-bold text-indigo-900 dark:text-indigo-200">هەڵبژاردنی تێمپلەیتی ئامادەکراو (ئارەزوومەندانە):</label>
                <x-native-select wire:model.live="selected_template">
                    <option value="">-- تێمپلەیتی دەستی / بەتاڵ --</option>
                    <option value="medication_request">دابیىکردنی دەرمانی نەخۆشانی هیمۆفیلیا (دابیىکردنی فاکتەر)</option>
                    <option value="patient_support">نوسراوی پشتگیری فەرمی بۆ نەخۆش</option>
                    <option value="official_thanks">سوپاس و پێزانینی فەرمی</option>
                    <option value="inquiry_letter">داواکاری هاوکاری و هەماهەنگی</option>
                </x-native-select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-input wire:model="mail_number" label="ژمارەی نوسراو *" placeholder="MAIL-2026-001" />
                <x-native-select wire:model="direction" label="جۆری نوسراو">
                    <option value="outgoing">بەشی ڕۆشتوو (Outgoing)</option>
                    <option value="incoming">بەشی هاتوو (Incoming)</option>
                </x-native-select>
            </div>

            <!-- Stamp Choice Toggle -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">جۆری مۆر لەکاتی چاپکردندا *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800">
                        <input type="radio" wire:model="stamp_type" value="online" class="text-rose-600 focus:ring-rose-500" />
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">مۆری ئۆنلاین و واژۆ (Digital)</span>
                    </label>

                    <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800">
                        <input type="radio" wire:model="stamp_type" value="manual" class="text-rose-600 focus:ring-rose-500" />
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">مۆری دەستی (دوای پرنت)</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-datetime-picker wire:model="mail_date" label="بەرواری نوسراو *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
                <x-native-select wire:model="patient_id" label="نەخۆشی پەیوەندیدار (ئەگەر هەیە)">
                    <option value="">نەخۆشی پەیوەندیدار نییە (نوسراوی گشتی)</option>
                    @foreach($allPatients as $p)
                        <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->patient_code }})</option>
                    @endforeach
                </x-native-select>
            </div>

            <x-input wire:model="sender_recipient" label="لایەنی نێرەر / وەرگر *" placeholder="نەخۆشخانەی هیوا / وەزارەتی تەندروستی..." />
            <x-input wire:model="reason_subject" label="هۆکار / بابەتی نوسراو *" placeholder="داواکاری فاکتەر / نوسراوی پشتگیری..." />
            
            <x-textarea wire:model="letter_body" label="ناوەڕۆکی نوسراو (Letter Content)" placeholder="ناوەڕۆک و دەقی نوسراوەکە بنووسە..." rows="4" />

            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">فایلی سکانکراو (ئەگەر هەیە)</label>
                <input type="file" wire:model="file" class="w-full text-xs text-slate-500 border border-slate-300 dark:border-slate-700 p-2 rounded-xl" />
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
