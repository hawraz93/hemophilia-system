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

        <!-- Section 2: Identity & Code Info Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 sm:p-6 space-y-5 shadow-2xs">
            <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-800 pb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20 flex items-center justify-center font-black text-sm">٢</span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <x-icon name="identification" class="w-5 h-5 text-cyan-500" />
                            <span>زانیاری ناسنامە و کۆدەکان (Identification & Membership)</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">کۆدی فەرمی نەخۆش، کۆدی هیوا و ژمارەی ئەندامێتی</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-5">
                <x-input wire:model="patient_code" label="کۆدی نەخۆش (Patient Code)" readonly class="bg-slate-50 font-mono font-bold text-rose-600" />
                <x-input wire:model="hiwa_code" label="کۆدی نەخۆشخانەی هیوا" placeholder="HW-1234" />
                <x-input wire:model="membership_number" label="ژمارەی ئەندامێتی کۆمەڵە" placeholder="MEM-101" />
                <x-input wire:model="national_id" label="ژمارەی نیشتمانی / ناسنامە" placeholder="1995XXXXXXXX" />
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
                <x-native-select wire:model="hemophilia_type" label="جۆری هیمۆفیلیا *">
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
