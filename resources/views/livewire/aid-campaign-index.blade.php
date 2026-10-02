<div class="space-y-6">
    <!-- Header Title & Action Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">هاوکارییە گشتییەکان و کەمپینەکان</h1>
            <p class="text-xs text-slate-500 mt-1">بەرێوەبردنی بڵاوکردنەوەی هاوکاری کۆمەڵ، سەبەتەی خۆراک، دەرزی و فاکتەرەکان بۆ نەخۆشەکان</p>
        </div>

        @if(!auth()->user()->isViewer())
            <x-button primary wire:click="openCreateModal" icon="plus" label="تۆمارکردنی هاوکاری هاتوو" class="font-bold shadow-lg shadow-rose-600/25" />
        @endif
    </div>

    <!-- Flash Notifications -->
    @if (session()->has('message'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 text-xs font-bold flex items-center justify-between">
            <span>{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 rounded-xl bg-rose-50 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 text-xs font-bold flex items-center justify-between">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Store / stock summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-slate-500 block">کۆی هاوکارییە هاتووەکان بۆ کۆمەڵە</span>
            <span class="text-2xl font-black text-slate-900 dark:text-white font-mono">{{ number_format($stockTotals['received']) }}</span>
            <span class="text-xs text-slate-400">دانە</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-slate-500 block">دابەشکراو بەسەر نەخۆشەکاندا</span>
            <span class="text-2xl font-black text-emerald-600 font-mono">{{ number_format($stockTotals['distributed']) }}</span>
            <span class="text-xs text-slate-400">دانە</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm">
            <span class="text-xs font-bold text-slate-500 block">ماوە لە کۆگا (دابەش نەکراو)</span>
            <span class="text-2xl font-black text-amber-600 font-mono">{{ number_format($stockTotals['remaining']) }}</span>
            <span class="text-xs text-slate-400">دانە</span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <x-input wire:model.live.debounce.300ms="search" placeholder="گەڕان بە ناوی کەمپین یان دابینکەر..." icon="magnifying-glass" class="w-full sm:w-80" />
    </div>

    <!-- Campaigns Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($campaigns as $camp)
            @php
                $count = $camp->patients_count;
                $limit = $camp->max_recipients;
                $pct = min(100, round(($count / max(1, $limit)) * 100));
                $isFull = $count >= $limit;
            @endphp
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between space-y-4 hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-extrabold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                            {{ $camp->category?->label() ?? 'هاوکاری' }}
                        </span>

                        @if($isFull)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300 border border-rose-200">
                                🔒 هەمووی دابەشکراوە
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200">
                                🟢 {{ $limit - $count }} دانە ماوە لە کۆگا
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-black text-slate-900 dark:text-white mt-3">{{ $camp->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1">دابینکەر: <strong class="text-slate-700 dark:text-slate-300">{{ $camp->source_funder ?? 'دیاری نەکراوە' }}</strong></p>
                    <div class="mt-2 grid grid-cols-2 gap-2 text-[11px]">
                        <div class="bg-slate-50 dark:bg-slate-900/40 rounded-xl px-2.5 py-1.5">
                            <span class="text-slate-400 block">بەرواری گەیشتن</span>
                            <strong class="font-mono text-slate-700 dark:text-slate-200">{{ $camp->campaign_date->format('Y-m-d') }}</strong>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/40 rounded-xl px-2.5 py-1.5">
                            <span class="text-slate-400 block">بەرواری دابەشکردن</span>
                            <strong class="font-mono {{ $camp->distribution_date ? 'text-slate-700 dark:text-slate-200' : 'text-amber-600' }}">{{ $camp->distribution_date?->format('Y-m-d') ?? 'دیاری نەکراوە' }}</strong>
                        </div>
                    </div>

                    <!-- Capacity Progress Bar -->
                    <div class="mt-4 space-y-1.5">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-600 dark:text-slate-400">دابەشکراو {{ $count }} لە {{ $limit }}</span>
                            <span class="font-mono {{ $isFull ? 'text-rose-600' : 'text-emerald-600' }}">{{ $pct }}%</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full {{ $isFull ? 'bg-rose-500' : 'bg-emerald-500' }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>

                    @if($camp->amount_per_patient > 0)
                        <div class="mt-3 text-xs font-semibold text-slate-500">
                            بڕ بۆ هەر نەخۆشێک: <strong class="text-emerald-600 font-bold">{{ number_format($camp->amount_per_patient) }} IQD</strong>
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700/80 flex items-center justify-between gap-2">
                    <button wire:click="openManageModal({{ $camp->id }})" class="px-4 py-2 rounded-xl bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 font-bold text-xs hover:bg-rose-600 dark:hover:bg-rose-600 dark:hover:text-white transition flex items-center gap-1.5 shadow">
                        <x-icon name="user-group" class="w-4 h-4" />
                        <span>دیاریکردنی وەرگران</span>
                    </button>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('aid-campaigns.print', $camp) }}" target="_blank" class="px-3 py-2 rounded-xl bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 font-bold text-xs hover:bg-indigo-100 transition flex items-center gap-1">
                            <x-icon name="printer" class="w-4 h-4" />
                            <span>ڕاپۆرت</span>
                        </a>
                        @can('edit-records')
                            <button wire:click="editCampaign({{ $camp->id }})" class="p-2 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition" title="دەستکاریکردن">
                                <x-icon name="pencil-square" class="w-4 h-4" />
                            </button>
                        @endcan
                        @can('delete-records')
                            <button wire:click="deleteCampaign({{ $camp->id }})" wire:confirm="ئایا دڵنیایت لە سڕینەوەی ئەم هاوکارییە؟ هەموو تۆمارەکانی دابەشکردنی بەسەر نەخۆشەکانیشدا دەسڕێنەوە." class="p-2 rounded-xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 transition" title="سڕینەوە">
                                <x-icon name="trash" class="w-4 h-4" />
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700">
                <x-icon name="gift" class="w-12 h-12 text-slate-400 mx-auto mb-3" />
                <h3 class="text-base font-bold text-slate-600 dark:text-slate-300">هیچ هاوکارییەک / کەمپینێک نەدۆزرایەوە</h3>
                <p class="text-xs text-slate-400 mt-1">سەرەوە کەمپینێکی نوێ دروست بکە بۆ دابەشکردنی هاوکاری گشتی.</p>
            </div>
        @endforelse
    </div>

    <div class="p-4">
        {{ $campaigns->links() }}
    </div>

    <!-- Create Campaign Modal -->
    <x-modal-card :title="$editingCampaignId ? 'دەستکاریکردنی هاوکاری هاتوو' : 'تۆمارکردنی هاوکاری هاتوو بۆ کۆمەڵە'" wire:model="showCreateModal" max-width="lg">
        <div class="space-y-4">
            <x-input wire:model="title" label="ناوی هاوکاری *" placeholder="نموونە: سەبەتەی خۆراکی کۆمپانیای قەیوان" />

            <x-native-select wire:model="category" label="جۆری هاوکاری *">
                @foreach(\App\Enums\AssistanceCategory::cases() as $cat)
                    <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                @endforeach
            </x-native-select>

            <x-input wire:model="source_funder" label="سەرچاوە / بەخشەر" placeholder="کۆمپانیا، کەسایەتی، ڕێکخراو..." />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-currency wire:model="amount_per_patient" label="بڕی پارە بۆ هەر نەخۆشێک (IQD)" thousands="," :precision="0" placeholder="0" />
                <x-input type="number" wire:model="max_recipients" label="بڕی هاتوو (ژمارەی پاکێج / دانە) *" placeholder="100" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-datetime-picker wire:model="campaign_date" label="بەرواری گەیشتن بۆ کۆمەڵە *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
                <x-datetime-picker wire:model="distribution_date" label="بەرواری دەستپێکی دابەشکردن" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
            </div>
            <x-textarea wire:model="notes" label="تێبینی زیادە" placeholder="وردەکاری زیاتر دەربارەی هاوکاریەکە..." />
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="تۆمارکردن" wire:click="createCampaign" spinner="createCampaign" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </x-slot:footer>
    </x-modal-card>

    <!-- Manage Recipients Modal (Zero-Click Fast Selection UI) -->
    @if($showManageModal && $selectedCampaign)
        <div x-data class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-4xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden text-right flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-rose-400">بەڕێوەبردنی وەرگران</span>
                        <h2 class="text-xl font-black">{{ $selectedCampaign->title }}</h2>
                        <p class="text-xs text-slate-400 mt-0.5">دابینکەر: {{ $selectedCampaign->source_funder ?? '—' }} | گەیشتن: {{ $selectedCampaign->campaign_date->format('Y-m-d') }} | دابەشکردن: {{ $selectedCampaign->distribution_date?->format('Y-m-d') ?? '—' }}</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-center bg-slate-800 px-4 py-2 rounded-2xl border border-slate-700">
                            <span class="text-[10px] text-slate-400 block font-bold">دابەشکراو / هاتوو</span>
                            <span class="text-lg font-mono font-black {{ $selectedCampaign->is_full ? 'text-rose-400' : 'text-emerald-400' }}">
                                {{ $selectedCampaign->patients_count }} / {{ $selectedCampaign->max_recipients }}
                            </span>
                            <span class="text-[10px] text-amber-300 block font-bold">{{ $selectedCampaign->remaining_count }} ماوە لە کۆگا</span>
                        </div>

                        <button wire:click="$set('showManageModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition">
                            <x-icon name="x-mark" class="w-6 h-6" />
                        </button>
                    </div>
                </div>

                <div class="p-6 overflow-y-auto space-y-6 flex-1">
                    <!-- Current Assigned Recipients List -->
                    <div>
                        <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider mb-3">نەخۆشە وەرگرەکانی ئەم کەمپینە ({{ $selectedCampaign->patients->count() }})</h3>

                        @if($selectedCampaign->patients->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($selectedCampaign->patients as $p)
                                    <div class="bg-slate-50 dark:bg-slate-800/80 p-3 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 font-bold flex items-center justify-center text-sm">
                                                <x-icon name="check-circle" class="w-5 h-5" />
                                            </div>
                                            <div>
                                                <span class="font-extrabold text-sm text-slate-900 dark:text-white block">{{ $p->full_name }}</span>
                                                <span class="text-[11px] text-slate-400 font-mono">کۆد: {{ $p->patient_code }} | ژ. ئەندامێتی: {{ $p->membership_number ?? '—' }}</span>
                                                <span class="text-[11px] text-emerald-600 font-mono block">وەرگرتن: {{ $p->pivot->received_at ? \Carbon\Carbon::parse($p->pivot->received_at)->format('Y-m-d') : '—' }}</span>
                                            </div>
                                        </div>

                                        <button wire:click="removePatientFromCampaign({{ $p->id }})" class="p-2 rounded-xl text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 transition" title="لاکردنەوە لە کەمپین">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-6 text-center text-xs font-bold text-slate-400 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-dashed">
                                هێشتا هیچ نەخۆشێک دیاری نەکراوە. لە خوارەوە زۆر بە ئاسانی نەخۆشەکان کلیک بکە و زیاد بکە.
                            </div>
                        @endif
                    </div>

                    <!-- Fast Single-Click Patient Picker (Zero-Click Style) -->
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">زیادکردنی خێرای نەخۆش (Fast Single-Click Allocation)</h3>
                                <p class="text-[11px] text-slate-400">گەڕان بکە و تەنها یەک کلیک بکە بۆ بەخشینی هاوکارییەکە بە نەخۆش</p>
                            </div>

                            @if($selectedCampaign->is_full)
                                <div class="px-3 py-1.5 rounded-xl bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 text-xs font-black border border-rose-300">
                                    🔒 هەموو {{ $selectedCampaign->max_recipients }} دانەکە دابەشکراوە و هیچی لە کۆگا نەماوە.
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                            <div class="sm:col-span-2">
                                <x-input wire:model.live.debounce.250ms="patientSearch" placeholder="گەڕان بە ناو، کۆدی نەخۆش، ژمارەی ئەندامێتی، یان مۆبایل..." icon="magnifying-glass" />
                            </div>
                            <x-datetime-picker wire:model="handout_date" label="بەرواری دابەشکردن بۆ نەخۆش" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
                        </div>

                        @if(!$selectedCampaign->is_full)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @forelse($eligiblePatients as $p)
                                    <button 
                                        wire:click="addPatientToCampaign({{ $p->id }})"
                                        class="p-3 bg-white dark:bg-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-950/60 border border-slate-200 dark:border-slate-700 hover:border-emerald-500 rounded-2xl text-right transition flex items-center justify-between group shadow-2xs"
                                    >
                                        <div>
                                            <span class="font-bold text-xs text-slate-900 dark:text-white block group-hover:text-emerald-600">{{ $p->full_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono block">کۆد: {{ $p->patient_code }}</span>
                                        </div>

                                        <div class="w-7 h-7 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition">
                                            <x-icon name="plus" class="w-4 h-4" />
                                        </div>
                                    </button>
                                @empty
                                    <div class="col-span-full p-4 text-center text-xs text-slate-400">
                                        {{ $patientSearch ? 'هیچ نەخۆشێک بەم ناوەوە نەدۆزرایەوە.' : 'ناوی نەخۆش بنووسە بۆ گەڕان...' }}
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <a href="{{ route('aid-campaigns.print', $selectedCampaign) }}" target="_blank" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-md shadow-indigo-600/20">
                        <x-icon name="printer" class="w-4 h-4" />
                        <span>پرینتی ڕاپۆرتی فەرمی کەمپین (A4 List)</span>
                    </a>

                    <x-button flat label="داخستن" wire:click="$set('showManageModal', false)" class="font-bold" />
                </div>
            </div>
        </div>
    @endif
</div>
