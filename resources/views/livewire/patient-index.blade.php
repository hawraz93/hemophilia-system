<div class="space-y-6">
    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">تۆماری گشتی نەخۆشەکان</h1>
            <p class="text-xs text-slate-500 mt-1">تۆمارکردن، گەڕان، فلتەرکردن و لەخۆگرتنی گشت زانیارییەکانی نەخۆش</p>
        </div>

        @if(!auth()->user()->isViewer())
            <x-button primary icon="user-plus" href="{{ route('patients.create') }}" class="font-bold shadow-md shadow-rose-600/20">
                تۆمارکردنی نەخۆشی نوێ
            </x-button>
        @endif
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
                ['label' => 'هیمۆفیلیا A', 'value' => 'A'],
                ['label' => 'هیمۆفیلیا B', 'value' => 'B'],
                ['label' => 'جۆری تر', 'value' => 'other'],
            ]"
            option-label="label"
            option-value="value"
        />

        <x-native-select
            wire:model.live="filter_status"
            :options="[
                ['label' => 'هەموو لیستەکان', 'value' => ''],
                ['label' => 'لیستی سەوز (تەواو)', 'value' => 'green'],
                ['label' => 'لیستی زەرد (داتای ناتەواو)', 'value' => 'yellow'],
                ['label' => 'لیستی سوور (بێوەڵام)', 'value' => 'red'],
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

    <!-- Patients Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-end text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="p-4">کۆدی نەخۆش</th>
                        <th class="p-4">ناوی تەواو</th>
                        <th class="p-4">جۆری هیمۆفیلیا</th>
                        <th class="p-4">گروپی خوێن</th>
                        <th class="p-4">ژمارەی مۆبایل</th>
                        <th class="p-4">پارێزگا / شار</th>
                        <th class="p-4 text-center">لیستی سەوز/زەرد/سوور</th>
                        <th class="p-4 text-center">کردارەکان</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-4 font-mono font-bold text-slate-600 dark:text-slate-300">
                                {{ $patient->patient_code }}
                            </td>
                            <td class="p-4 font-bold text-slate-900 dark:text-white">
                                <a href="{{ route('patients.show', $patient) }}" class="hover:text-rose-600 transition">
                                    {{ $patient->full_name }}
                                </a>
                                @if($patient->membership_number)
                                    <span class="block text-xs font-normal text-slate-400">ژ. ئەندامێتی: {{ $patient->membership_number }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    {{ $patient->hemophilia_type?->label() }} — {{ $patient->severity?->label() }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-slate-700 dark:text-slate-300">
                                {{ $patient->blood_group?->value ?? '—' }}
                            </td>
                            <td class="p-4 font-mono text-slate-700 dark:text-slate-300">
                                {{ $patient->phone }}
                            </td>
                            <td class="p-4 text-slate-600 dark:text-slate-400">
                                {{ $patient->governorate }} {{ $patient->district ? " / {$patient->district}" : '' }}
                            </td>
                            <td class="p-4 text-center">
                                <x-badge :color="$patient->list_status->color()" :label="$patient->list_status->label()" />
                            </td>
                            <td class="p-4 text-center space-x-1 space-x-reverse">
                                <x-button sm outline secondary icon="eye" href="{{ route('patients.show', $patient) }}" />
                                @if(!auth()->user()->isViewer())
                                    <x-button sm outline primary icon="pencil-square" href="{{ route('patients.edit', $patient) }}" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-500">
                                هیچ نەخۆشێک بەم تایبەتمەندییانە نەدۆزرایەوە.
                            </td>
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
</div>
