<div class="max-w-6xl mx-auto space-y-6 pb-12">
    <!-- Page Top Navigation & Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('patients.index') }}" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <x-icon name="arrow-right" class="w-5 h-5" />
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                    {{ $isEditing ? 'دەستکاریکردنی زانیاری نەخۆش: ' . $patient->full_name : 'تۆمارکردنی نەخۆشی نوێ' }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">تکایە سەرجەم زانیارییەکانی نەخۆش بە ووردیی پڕبکەرەوە بۆ پێدانی کۆدی فەرمی</p>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <x-button flat label="پاشگەزبوونەوە" href="{{ route('patients.index') }}" class="font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300" />
            <x-button type="button" wire:click="save" primary label="پاشەکەوتکردن" icon="check" class="font-extrabold shadow-md shadow-rose-600/25 px-6" />
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Section 1: Personal Info Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xs">
            <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-800 pb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 flex items-center justify-center font-black text-sm">١</span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <x-icon name="user" class="w-5 h-5 text-indigo-500" />
                            <span>زانیاری کەسی (Personal Information)</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">ناوی تەواو، بەرواری لەدایکبوون، پەیوەندی و ناونیشان</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <x-input wire:model="first_name" label="ناوی ناوخۆیی (یەکەم) *" placeholder="ئارام" />
                <x-input wire:model="father_name" label="ناوی باوک *" placeholder="کامەران" />
                <x-input wire:model="grandfather_name" label="ناوی باپیر *" placeholder="عەلی" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
                <x-native-select wire:model="gender" label="ڕەگەز">
                    <option value="male">نێر (Male)</option>
                    <option value="female">مێ (Female)</option>
                </x-native-select>

                <x-datetime-picker wire:model.live="dob" label="بەرواری لەدایکبوون" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
                <x-input type="number" wire:model="age" label="تەمەن" placeholder="تەمەن" />

                <x-native-select wire:model="marital_status" label="دۆخی هاوسەرگیری">
                    <option value="">هەڵبژێرە...</option>
                    <option value="single">سەڵت</option>
                    <option value="married">هاوسەردار</option>
                    <option value="divorced">جیابووەوە</option>
                    <option value="widowed">بێوەژین / بێوەپیاو</option>
                </x-native-select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <x-phone wire:model="phone" label="ژمارەی مۆبایل (پەیوەندی سەرەکی) *" placeholder="0770-123-4567" mask="####-###-####" />
                <x-phone wire:model="secondary_phone" label="ژمارەی مۆبایلی تر (یارمەتیدەر)" placeholder="0750-123-4567" mask="####-###-####" />
                <x-input type="number" wire:model="children_count" label="ژمارەی منداڵ" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
                <x-input wire:model="governorate" label="پارێزگا" placeholder="سلێمانی" />
                <x-input wire:model="district" label="قەزا" placeholder="مەڵبەند 1" />
                <x-input wire:model="neighborhood" label="گەڕەک" placeholder="سەرچنار" />
                <x-input wire:model="address" label="ناونیشانی تەواو" placeholder="کۆڵانی ..." />
            </div>
        </div>

        <!-- Section 2: Identity, Membership & Party Affiliation Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xs">
            <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-800 pb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20 flex items-center justify-center font-black text-sm">٢</span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <x-icon name="identification" class="w-5 h-5 text-cyan-500" />
                            <span>ناسنامە، ئەندامێتی و پەیوەندیداری (Membership & Affiliation)</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">ژمارەی ئەندامێتی، پلەی ئەندام لە ناو ڕێکخراو، کارتی دەنگدان و پەیوەندخوازە بە</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <x-input wire:model="patient_code" label="کۆدی نەخۆش (Patient Code)" readonly class="bg-slate-50 font-mono font-bold text-rose-600" />
                <x-input wire:model="hiwa_code" label="کۆدی نەخۆشخانەی هیوا" placeholder="HW-1234" />
                <x-input wire:model="membership_number" label="ژمارەی ئەندامێتی کۆمەڵە" placeholder="MEM-101" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
                <!-- Membership Type Dropdown -->
                <x-native-select wire:model="membership_type" label="پلەی ئەندامێتی لە ناو ڕێکخراوەکە (جۆری ئەندامبوون) *">
                    @foreach(\App\Enums\MembershipType::cases() as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </x-native-select>

                <!-- Custom Alpine Visual Colored Select for Party Affiliation -->
                <div x-data="{ open: false, selected: @entangle('party_affiliation') }" class="relative">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1">
                        پەیوەندخوازە بە (پابەندی حیزبی) *
                    </label>

                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-right shadow-2xs font-extrabold text-xs min-h-[42px]">
                        <template x-if="selected == 'green'">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-emerald-500 inline-block shadow-xs border border-emerald-600"></span><span class="text-emerald-700 dark:text-emerald-400 font-black">سەوز</span></span>
                        </template>
                        <template x-if="selected == 'yellow'">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-amber-400 inline-block shadow-xs border border-amber-500"></span><span class="text-amber-700 dark:text-amber-300 font-black">زەرد</span></span>
                        </template>
                        <template x-if="selected == 'orange'">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-orange-500 inline-block shadow-xs border border-orange-600"></span><span class="text-orange-700 dark:text-orange-400 font-black">پرتەقاڵی</span></span>
                        </template>
                        <template x-if="selected == 'brown'">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-amber-900 inline-block shadow-xs border border-amber-950"></span><span class="text-amber-900 dark:text-amber-300 font-black">قاوەیی</span></span>
                        </template>
                        <template x-if="selected == 'light'">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-cyan-400 inline-block shadow-xs border border-cyan-500"></span><span class="text-cyan-700 dark:text-cyan-300 font-black">ڕووناکی</span></span>
                        </template>
                        <template x-if="selected == 'white'">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-white border border-slate-400 inline-block shadow-xs"></span><span class="text-slate-800 dark:text-slate-200 font-black">سپی</span></span>
                        </template>
                        <template x-if="selected == 'grey'">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-slate-500 inline-block shadow-xs border border-slate-600"></span><span class="text-slate-600 dark:text-slate-400 font-black">خۆلەمێشی</span></span>
                        </template>
                        <template x-if="!selected">
                            <span class="text-slate-400 font-medium">دیاری نەکراوە</span>
                        </template>
                        <x-icon name="chevron-down" class="w-4 h-4 text-slate-400 shrink-0" />
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="absolute top-full right-0 mt-1 w-full z-40 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-xl p-2 space-y-1" style="display: none;">
                        <button type="button" @click="selected = ''; open = false" class="w-full text-right p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 text-xs font-bold flex items-center justify-between">
                            <span class="text-slate-400">دیاری نەکراوە</span>
                        </button>
                        <button type="button" @click="selected = 'green'; open = false" class="w-full text-right p-2.5 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-950/50 text-xs font-black flex items-center justify-between text-emerald-700 dark:text-emerald-300">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-emerald-500 shadow-xs border border-emerald-600"></span> سەوز</span>
                        </button>
                        <button type="button" @click="selected = 'yellow'; open = false" class="w-full text-right p-2.5 rounded-xl hover:bg-amber-50 dark:hover:bg-amber-950/50 text-xs font-black flex items-center justify-between text-amber-700 dark:text-amber-300">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-amber-400 shadow-xs border border-amber-500"></span> زەرد</span>
                        </button>
                        <button type="button" @click="selected = 'orange'; open = false" class="w-full text-right p-2.5 rounded-xl hover:bg-orange-50 dark:hover:bg-orange-950/50 text-xs font-black flex items-center justify-between text-orange-700 dark:text-orange-300">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-orange-500 shadow-xs border border-orange-600"></span> پرتەقاڵی</span>
                        </button>
                        <button type="button" @click="selected = 'brown'; open = false" class="w-full text-right p-2.5 rounded-xl hover:bg-amber-900/10 text-xs font-black flex items-center justify-between text-amber-900 dark:text-amber-300">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-amber-900 shadow-xs border border-amber-950"></span> قاوەیی</span>
                        </button>
                        <button type="button" @click="selected = 'light'; open = false" class="w-full text-right p-2.5 rounded-xl hover:bg-cyan-50 dark:hover:bg-cyan-950/50 text-xs font-black flex items-center justify-between text-cyan-700 dark:text-cyan-300">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-cyan-400 shadow-xs border border-cyan-500"></span> ڕووناکی</span>
                        </button>
                        <button type="button" @click="selected = 'white'; open = false" class="w-full text-right p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 text-xs font-black flex items-center justify-between text-slate-800 dark:text-slate-100">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-white shadow-xs border border-slate-400"></span> سپی</span>
                        </button>
                        <button type="button" @click="selected = 'grey'; open = false" class="w-full text-right p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 text-xs font-black flex items-center justify-between text-slate-600 dark:text-slate-300">
                            <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-slate-500 shadow-xs border border-slate-600"></span> خۆلەمێشی</span>
                        </button>
                    </div>
                </div>

                <x-input wire:model="national_id" label="ژمارەی کارتی نیشتمانی / ناسنامە" placeholder="1995XXXXXXXX" />
                <x-input wire:model="voting_card_number" label="ژمارەی کارتی دەنگدان" placeholder="VOTE-987654" />
            </div>
        </div>

        <!-- Section 3: Medical & Health Info Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xs">
            <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-800 pb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 flex items-center justify-center font-black text-sm">٣</span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <x-icon name="heart" class="w-5 h-5 text-rose-500" />
                            <span>زانیاری تەندروستی و پزیشکی (Medical Profile)</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">جۆری هیمۆفیلیا، پلە، گروپی خوێن و نەخۆشییە گواستراوەکان</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
                <x-native-select wire:model="hemophilia_type" label="جۆری نەخۆشی (هیمۆفیلیا) *">
                    @foreach(\App\Enums\HemophiliaType::cases() as $ht)
                        <option value="{{ $ht->value }}">{{ $ht->label() }}</option>
                    @endforeach
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

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
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

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <x-textarea wire:model="comorbidities" label="نەخۆشییە هاوشێوەکان (Comorbidities)" placeholder="نەخۆشی تر..." />
                <x-textarea wire:model="disability_special_needs" label="کێشەی جەستەیی یان پێداویستی تایبەت" placeholder="پێداویستی تایبەت..." />
                <x-textarea wire:model="medical_notes" label="تێبینی پزشکی" placeholder="تێبینی پزیشکی نەخۆش..." />
            </div>
        </div>

        <!-- Floating Bottom Sticky Bar -->
        <div class="sticky bottom-4 z-20 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl p-4 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xl flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <x-icon name="information-circle" class="w-5 h-5 text-rose-500 shrink-0" />
                <span>دڵنیا ببەوە لە ڕاستیی سەرجەم زانیارییەکان پێش فۆرمکردنی تەمەن و کۆد</span>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <x-button flat label="پاشگەزبوونەوە" href="{{ route('patients.index') }}" class="font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300" />
                <x-button type="submit" primary label="پاشەکەوتکردنی زانیارییەکان" icon="check" class="font-extrabold shadow-md shadow-rose-600/30 px-6 py-2.5" />
            </div>
        </div>
    </form>
</div>
