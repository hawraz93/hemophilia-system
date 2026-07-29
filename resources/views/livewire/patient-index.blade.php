<div class="space-y-6">
    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">تۆماری گشتی نەخۆشەکان</h1>
            <p class="text-xs text-slate-500 mt-1">تۆمارکردن، گەڕان، فلتەرکردن و لەخۆگرتنی گشت زانیارییەکانی نەخۆش</p>
        </div>

        @if(!auth()->user()->isViewer())
            <x-button primary icon="user-plus" wire:click="openCreateModal" class="font-bold">
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
                                <a href="{{ route('patients.show', $patient) }}" class="hover:text-red-600">
                                    {{ $patient->full_name }}
                                </a>
                                @if($patient->membership_number)
                                    <span class="block text-xs font-normal text-slate-400">ژ. ئەندامێتی: {{ $patient->membership_number }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-700 border border-red-200">
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
                                    <x-button sm outline primary icon="pencil-square" wire:click="editPatient({{ $patient->id }})" />
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

    <!-- Create / Edit Patient Modal -->
    <x-modal wire:model="showCreateModal" max-width="4xl">
        <x-card title="تۆمارکردن / دەستکاری نەخۆش">
            <form wire:submit="save" class="space-y-6 max-h-[78vh] overflow-y-auto px-1">
                <!-- Section 1: Personal Info Card -->
                <div class="bg-slate-50/80 dark:bg-slate-800/70 border border-slate-200/90 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 space-y-4 shadow-sm">
                    <div class="flex items-center gap-2.5 border-b border-slate-200 dark:border-slate-700/80 pb-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 flex items-center justify-center font-black text-xs">١</span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                            <x-icon name="user" class="w-4 h-4 text-indigo-500" />
                            <span>زانیاری کەسی</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-input wire:model="first_name" label="ناوی ناوخۆیی (یەکەم) *" placeholder="ئارام" />
                        <x-input wire:model="father_name" label="ناوی باوک *" placeholder="کامەران" />
                        <x-input wire:model="grandfather_name" label="ناوی باپیر *" placeholder="عەلی" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <x-native-select wire:model="gender" label="ڕەگەز">
                            <option value="male">نێر</option>
                            <option value="female">مێ</option>
                        </x-native-select>

                        <x-input type="date" wire:model="dob" label="بەرواری لەدایکبوون" />
                        <x-input type="number" wire:model="age" label="تەمەن" placeholder="تەمەن" />

                        <x-native-select wire:model="marital_status" label="دۆخی هاوسەرگیری">
                            <option value="">هەڵبژێرە...</option>
                            <option value="single">سەڵت</option>
                            <option value="married">هاوسەردار</option>
                            <option value="divorced">جیابووەوە</option>
                            <option value="widowed">بێوەژین / بێوەپیاو</option>
                        </x-native-select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-input wire:model="phone" label="ژمارەی مۆبایل *" placeholder="0770XXXXXXX" />
                        <x-input wire:model="secondary_phone" label="ژمارەی مۆبایلی تر" placeholder="0750XXXXXXX" />
                        <x-input type="number" wire:model="children_count" label="ژمارەی منداڵ" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <x-input wire:model="governorate" label="پارێزگا" placeholder="سلێمانی" />
                        <x-input wire:model="district" label="قەزا" placeholder="مەڵبەند 1" />
                        <x-input wire:model="neighborhood" label="گەڕەک" placeholder="سەرچنار" />
                        <x-input wire:model="address" label="ناونیشانی تەواو" placeholder="کۆڵانی ..." />
                    </div>
                </div>

                <!-- Section 2: Identity Info Card -->
                <div class="bg-slate-50/80 dark:bg-slate-800/70 border border-slate-200/90 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 space-y-4 shadow-sm">
                    <div class="flex items-center gap-2.5 border-b border-slate-200 dark:border-slate-700/80 pb-3">
                        <span class="w-7 h-7 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20 flex items-center justify-center font-black text-xs">٢</span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                            <x-icon name="identification" class="w-4 h-4 text-cyan-500" />
                            <span>زانیاری ناسنامە و کۆدەکان</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <x-input wire:model="patient_code" label="کۆدی نەخۆش (Patient Code)" readonly />
                        <x-input wire:model="hiwa_code" label="کۆدی نەخۆشخانەی هیوا" placeholder="HW-1234" />
                        <x-input wire:model="membership_number" label="ژمارەی ئەندامێتی کۆمەڵە" placeholder="MEM-101" />
                        <x-input wire:model="national_id" label="ژمارەی نیشتمانی / ناسنامە" placeholder="1995XXXXXXXX" />
                    </div>
                </div>

                <!-- Section 3: Medical Info Card -->
                <div class="bg-slate-50/80 dark:bg-slate-800/70 border border-slate-200/90 dark:border-slate-700/80 rounded-2xl p-4 sm:p-5 space-y-4 shadow-sm">
                    <div class="flex items-center gap-2.5 border-b border-slate-200 dark:border-slate-700/80 pb-3">
                        <span class="w-7 h-7 rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center justify-center font-black text-xs">٣</span>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                            <x-icon name="heart" class="w-4 h-4 text-rose-500" />
                            <span>زانیاری تەندروستی و پزیشکی</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <x-native-select wire:model="hemophilia_type" label="جۆری هیمۆفیلیا">
                            <option value="A">هیمۆفیلیا A</option>
                            <option value="B">هیمۆفیلیا B</option>
                            <option value="other">جۆری تر</option>
                        </x-native-select>

                        <x-native-select wire:model="severity" label="پلەی نەخۆشی">
                            <option value="mild">سوک (Mild)</option>
                            <option value="moderate">ناوەند (Moderate)</option>
                            <option value="severe">سەخت (Severe)</option>
                        </x-native-select>

                        <x-native-select wire:model="blood_group" label="گروپی خوێن">
                            <option value="">دیاری نەکراوە</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </x-native-select>

                        <x-native-select wire:model="inhibitor_status" label="Inhibitor Status">
                            <option value="negative">نێگەتیڤ (-)</option>
                            <option value="positive">پۆزەتیڤ (+)</option>
                            <option value="unknown">نادیار</option>
                        </x-native-select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-native-select wire:model="hepatitis_b" label="Hepatitis B">
                            <option value="negative">نێگەتیڤ (-)</option>
                            <option value="positive">پۆزەتیڤ (+)</option>
                            <option value="unknown">نادیار</option>
                        </x-native-select>

                        <x-native-select wire:model="hepatitis_c" label="Hepatitis C">
                            <option value="negative">نێگەتیڤ (-)</option>
                            <option value="positive">پۆزەتیڤ (+)</option>
                            <option value="unknown">نادیار</option>
                        </x-native-select>

                        <x-native-select wire:model="hiv" label="HIV">
                            <option value="negative">نێگەتیڤ (-)</option>
                            <option value="positive">پۆزەتیڤ (+)</option>
                            <option value="unknown">نادیار</option>
                        </x-native-select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-textarea wire:model="comorbidities" label="نەخۆشییە هاوشێوەکان" placeholder="نەخۆشی تر..." />
                        <x-textarea wire:model="disability_special_needs" label="کێشەی جەستەیی یان پێداویستی تایبەت" placeholder="پێداویستی تایبەت..." />
                        <x-textarea wire:model="medical_notes" label="تێبینی پزشکی" placeholder="تێبینی پزیشکی نەخۆش..." />
                    </div>
                </div>

                <!-- Sticky Action Footer -->
                <div class="sticky bottom-0 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md pt-4 pb-2 border-t border-slate-200 dark:border-slate-700/80 flex items-center justify-end gap-3 z-10">
                    <x-button flat label="پاشگەزبوونەوە" x-on:click="close" class="font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300" />
                    <x-button type="submit" primary label="پاشەکەوتکردنی زانیارییەکان" icon="check" class="font-bold shadow-md shadow-red-600/20" />
                </div>
            </form>
        </x-card>
    </x-modal>
</div>
