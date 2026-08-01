<div class="space-y-6">
    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">تۆماری گشتی نەخۆشەکان</h1>
            <p class="text-xs text-slate-500 mt-1">تۆمارکردن، گەڕان، فلتەرکردن و لەخۆگرتنی گشت زانیارییەکانی نەخۆش</p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="exportExcel" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-md shadow-emerald-600/20">
                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                <span>داگرتن بە Excel</span>
            </button>

            @if(!auth()->user()->isViewer())
                <x-button primary icon="user-plus" href="{{ route('patients.create') }}" class="font-bold shadow-md shadow-rose-600/20">
                    تۆمارکردنی نەخۆشی نوێ
                </x-button>
            @endif
        </div>
    </div>

    <!-- Search & Filters Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-input
            wire:model.live.debounce.300ms="search"
            placeholder="گەڕان بە ناو، کۆدی نەخۆش، ژمارەی ئەندامێتی، کۆدی هیوا، ژمارەی مۆبایل..."
            icon="magnifying-glass"
        />

        <x-native-select
            wire:model.live="filter_type"
            :options="[
                ['label' => 'هەموو جۆرەکانی هیمۆفیلیا', 'value' => ''],
                ['label' => 'هیمۆفیلیای A', 'value' => 'A'],
                ['label' => 'هیمۆفیلیای B', 'value' => 'B'],
                ['label' => 'ڤۆن ویلی براند', 'value' => 'von_willebrand'],
                ['label' => 'خوێنبەربوونی تر', 'value' => 'other_bleeding'],
                ['label' => 'جۆری تر', 'value' => 'other'],
            ]"
            option-label="label"
            option-value="value"
        />

        <x-native-select
            wire:model.live="filter_status"
            :options="[
                ['label' => 'هەموو دۆخەکان', 'value' => ''],
                ['label' => 'سەوز (تەواو)', 'value' => 'green'],
                ['label' => 'زەرد (ناتەواو)', 'value' => 'yellow'],
                ['label' => 'سوور (پەیوەندی نەپچڕاو)', 'value' => 'red'],
            ]"
            option-label="label"
            option-value="value"
        />

        <x-native-select
            wire:model.live="filter_blood"
            :options="[
                ['label' => 'هەموو گروپەکانی خوێن', 'value' => ''],
                ['label' => 'A+', 'value' => 'A+'],
                ['label' => 'A-', 'value' => 'A-'],
                ['label' => 'B+', 'value' => 'B+'],
                ['label' => 'B-', 'value' => 'B-'],
                ['label' => 'AB+', 'value' => 'AB+'],
                ['label' => 'AB-', 'value' => 'AB-'],
                ['label' => 'O+', 'value' => 'O+'],
                ['label' => 'O-', 'value' => 'O-'],
            ]"
            option-label="label"
            option-value="value"
        />
    </div>

    <!-- Patients Table Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="p-3.5">کۆد</th>
                        <th class="p-3.5">ناوی تەواو</th>
                        <th class="p-3.5">ژ. ئەندامێتی</th>
                        <th class="p-3.5">پەیوەندخوازە بە</th>
                        <th class="p-3.5">جۆری هیمۆفیلیا</th>
                        <th class="p-3.5">گروپی خوێن</th>
                        <th class="p-3.5">ژمارەی مۆبایل</th>
                        <th class="p-3.5 text-center">دۆخی لیست</th>
                        <th class="p-3.5 text-center">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition">
                            <td class="p-3.5 font-mono font-bold text-slate-600 dark:text-slate-300">
                                {{ $patient->patient_code }}
                            </td>
                            <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                                <a href="{{ route('patients.show', $patient) }}" class="hover:text-rose-600 transition">
                                    {{ $patient->full_name }}
                                </a>
                                @if($patient->hiwa_code)
                                    <span class="block text-[10px] text-slate-400 font-normal font-mono">هیوا: {{ $patient->hiwa_code }}</span>
                                @endif
                            </td>
                            <td class="p-3.5 font-mono text-slate-600 dark:text-slate-300">
                                {{ $patient->membership_number ?? '—' }}
                                @if($patient->membership_type)
                                    <span class="block text-[10px] text-indigo-600 font-bold">{{ $patient->membership_type->label() }}</span>
                                @endif
                            </td>
                            <td class="p-3.5">
                                @if($patient->party_affiliation)
                                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-extrabold border inline-block {{ $patient->party_affiliation->badgeClasses() }}">
                                        {{ $patient->party_affiliation->label() }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="p-3.5 font-bold text-rose-600 dark:text-rose-400">
                                {{ $patient->hemophilia_type?->label() }}
                                <span class="block text-[10px] text-slate-400 font-normal">پلەی {{ $patient->severity?->label() }}</span>
                            </td>
                            <td class="p-3.5 font-bold text-slate-700 dark:text-slate-200">
                                {{ $patient->blood_group?->value ?? '—' }}
                            </td>
                            <td class="p-3.5 font-mono text-slate-600 dark:text-slate-300">
                                {{ $patient->phone }}
                            </td>
                            <td class="p-3.5 text-center">
                                <x-badge :color="$patient->list_status->color()" :label="$patient->list_status->label()" />
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('patients.show', $patient) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-white transition" title="بینینی زانیاری">
                                        <x-icon name="eye" class="w-4 h-4" />
                                    </a>

                                    @if(!auth()->user()->isViewer())
                                        <a href="{{ route('patients.edit', $patient) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 transition" title="دەستکاریکردن">
                                            <x-icon name="pencil-square" class="w-4 h-4" />
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400">هیچ نەخۆشێک نەدۆزرایەوە.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            {{ $patients->links() }}
        </div>
    </div>
</div>
