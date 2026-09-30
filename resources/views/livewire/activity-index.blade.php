<div class="space-y-6">
    <x-flash-messages />

    <!-- Header Card & Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">تۆماری کار و چالاکییەکان</h1>
            <p class="text-xs text-slate-500 mt-1">بەڕێوەبردن، تۆمارکردن و بەدواداچوونی سیمینار، وۆرکشۆپ، هەڵمەتی پزیشکی و کۆبوونەوەکان</p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <button wire:click="exportExcel" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-md shadow-emerald-600/20">
                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                <span>داگرتن بە Excel</span>
            </button>

            <button onclick="window.print()" class="px-4 py-2 bg-slate-800 text-white rounded-xl font-bold text-xs hover:bg-slate-900 transition flex items-center gap-2 shadow">
                <x-icon name="printer" class="w-4 h-4" />
                <span>چاپکردنی ڕاپۆرتی چالاکییەکان</span>
            </button>

            @if(!auth()->user()->isViewer())
                <x-button wire:click="openCreateModal" primary label="تۆمارکردنی چالاکی نوێ" icon="plus" class="font-extrabold shadow-md shadow-rose-600/30 px-5" />
            @endif
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 rounded-2xl shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 flex items-center justify-center font-black">
                <x-icon name="sparkles" class="w-6 h-6" />
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">سەرجەم چالاکییەکان</span>
                <strong class="text-xl font-black text-slate-900 dark:text-white">{{ number_format($totalActivities) }}</strong>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 rounded-2xl shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center font-black">
                <x-icon name="user-group" class="w-6 h-6" />
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">کۆیی بەشداربووان</span>
                <strong class="text-xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($totalParticipants) }} کەس</strong>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 rounded-2xl shadow-2xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center justify-center font-black">
                <x-icon name="banknotes" class="w-6 h-6" />
            </div>
            <div>
                <span class="text-xs text-slate-400 font-bold block">تێکڕای تێچوو / بودجە</span>
                <strong class="text-xl font-black text-rose-600 dark:text-rose-400">{{ number_format($totalBudget) }} IQD</strong>
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="w-full sm:w-80">
            <x-input wire:model.live.debounce.300ms="search" placeholder="گەڕان لە چالاکی، شوێن، ڕێکخەر..." icon="magnifying-glass" />
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <x-native-select wire:model.live="typeFilter" class="w-full sm:w-48">
                <option value="">هەموو جۆرەکان</option>
                <option value="seminar">سیمینار</option>
                <option value="workshop">وۆرکشۆپ</option>
                <option value="medical_campaign">هەڵمەتی پزیشکی</option>
                <option value="assistance_distribution">دابەشکردنی هاوکاری</option>
                <option value="meeting">کۆبوونەوە</option>
                <option value="awareness">هۆشیارکردنەوە</option>
            </x-native-select>

            <x-native-select wire:model.live="statusFilter" class="w-full sm:w-44">
                <option value="">هەموو دۆخەکان</option>
                <option value="completed">ئەنجامدراو</option>
                <option value="planned">پلاندانراو</option>
                <option value="cancelled">هەڵوەشێنراوە</option>
            </x-native-select>
        </div>
    </div>

    <!-- Printable Area Table -->
    <div id="printable-activities" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-extrabold text-sm text-slate-900 dark:text-white">لیستی کار و چالاکییە تۆمارکراوەکان</h3>
            <span class="text-xs text-slate-500">سەرجەم: {{ $activities->total() }} چالاکی</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 font-bold border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="p-3.5">ناونیشانی چالاکی</th>
                        <th class="p-3.5">جۆری چالاکی</th>
                        <th class="p-3.5">بەروار</th>
                        <th class="p-3.5">شوێن / ڕێکخەر</th>
                        <th class="p-3.5">بەشداربووان</th>
                        <th class="p-3.5">بودجە (IQD)</th>
                        <th class="p-3.5 text-center">دۆخ</th>
                        <th class="p-3.5 text-center print:hidden">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($activities as $act)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                                {{ $act->title }}
                                @if($act->description)
                                    <p class="text-xs text-slate-400 font-normal truncate max-w-xs">{{ $act->description }}</p>
                                @endif
                            </td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ $act->activity_type_label }}
                                </span>
                            </td>
                            <td class="p-3.5 font-mono text-slate-600 dark:text-slate-300">
                                {{ $act->activity_date->format('Y-m-d') }}
                            </td>
                            <td class="p-3.5 text-xs text-slate-500">
                                <div>{{ $act->location ?? '—' }}</div>
                                @if($act->organizer)
                                    <div class="text-[10px] text-slate-400">بەرپرس: {{ $act->organizer }}</div>
                                @endif
                            </td>
                            <td class="p-3.5 font-bold text-indigo-600 dark:text-indigo-400 font-mono">
                                {{ number_format($act->participants_count) }} کەس
                            </td>
                            <td class="p-3.5 font-black text-emerald-600 dark:text-emerald-400 font-mono">
                                {{ number_format($act->budget) }}
                            </td>
                            <td class="p-3.5 text-center">
                                <x-badge :color="$act->status_color" :label="$act->status_label" />
                            </td>
                            <td class="p-3.5 text-center print:hidden">
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="editActivity({{ $act->id }})" class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 transition" title="دەستکاریکردن">
                                        <x-icon name="pencil-square" class="w-4 h-4" />
                                    </button>

                                    @if(!auth()->user()->isViewer())
                                        <button wire:click="deleteActivity({{ $act->id }})" wire:confirm="ئایا دڵنیایت لە سڕینەوەی ئەم چالاکییە؟" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 transition" title="سڕینەوە">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">هیچ چالاکییەک نەدۆزرایەوە.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $activities->links() }}
        </div>
    </div>

    <!-- Create / Edit Activity Modal -->
    <div x-data="{ open: @entangle('showModal') }" x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl space-y-5 text-right">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="font-black text-slate-900 dark:text-white text-base">
                    {{ $editingActivity ? 'دەستکاریکردنی چالاکی' : 'تۆمارکردنی چالاکی نوێ' }}
                </h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-xl">
                    <x-icon name="x-mark" class="w-6 h-6" />
                </button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <x-input wire:model="title" label="ناونیشانی چالاکی *" placeholder="سیمیناری هۆشیاری..." />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-native-select wire:model="activity_type" label="جۆری چالاکی *">
                        <option value="seminar">سیمینار</option>
                        <option value="workshop">وۆرکشۆپ</option>
                        <option value="medical_campaign">هەڵمەتی پزیشکی</option>
                        <option value="assistance_distribution">دابەشکردنی هاوکاری</option>
                        <option value="meeting">کۆبوونەوە</option>
                        <option value="awareness">هۆشیارکردنەوە</option>
                    </x-native-select>

                    <x-datetime-picker wire:model="activity_date" label="بەرواری ئەنجامدان *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input wire:model="location" label="شوێنی چالاکی / هۆڵ" placeholder="هۆڵی ڕێکخراوەکان..." />
                    <x-input wire:model="organizer" label="ڕێکخەر / بەرپرس" placeholder="ناو یان بەش..." />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <x-input type="number" wire:model="participants_count" label="ژمارەی بەشداربووان *" />
                    <x-input type="number" wire:model="budget" label="تێچوو / بودجە (IQD) *" />
                    <x-native-select wire:model="status" label="دۆخی چالاکی *">
                        <option value="completed">ئەنجامدراو</option>
                        <option value="planned">پلاندانراو</option>
                        <option value="cancelled">هەڵوەشێنراوە</option>
                    </x-native-select>
                </div>

                <x-textarea wire:model="description" label="پوختە و تێبینی بەرپرس" placeholder="ووردەکاری چالاکییەکە..." />

                <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                    <x-button flat label="پاشگەزبوونەوە" @click="open = false" class="font-bold" />
                    <x-button type="submit" primary label="پاشەکەوتکردن" icon="check" class="font-extrabold shadow-md shadow-rose-600/30 px-6" />
                </div>
            </form>
        </div>
    </div>
</div>
