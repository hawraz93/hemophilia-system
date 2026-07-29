<div class="space-y-8">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-red-700 via-red-600 to-rose-600 rounded-2xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-right">
            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider">
                کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی
            </span>
            <h1 class="text-2xl sm:text-3xl font-black">سیستەمی بەڕێوەبردنی نەخۆشانی هیمۆفیلیا</h1>
            <p class="text-sm text-red-100 max-w-xl">
                بەخێربێن بۆ داشبۆردی ناوەندی. لێرەوە دەتوانیت ئاماری گشتی نەخۆشەکان، هاوکارییەکان، و پۆلێنکردنی لیستەکانی سەوز، زەرد و سوور ببینیت.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('patients.index') }}" class="px-5 py-3 rounded-xl bg-white text-red-700 font-bold hover:bg-red-50 transition shadow-lg flex items-center gap-2">
                <x-icon name="user-plus" class="w-5 h-5" />
                <span>تۆمارکردنی نەخۆش</span>
            </a>
        </div>
    </div>

    <!-- Main Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Patients -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400">کۆی گشتی نەخۆشەکان</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($totalPatients) }}</h3>
                <span class="text-xs text-slate-500 mt-1 inline-block">تۆمارکراو لە سیستەمدا</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center">
                <x-icon name="users" class="w-8 h-8" />
            </div>
        </div>

        <!-- Hemophilia A & B -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400">جۆری هیمۆفیلیا</p>
                <div class="flex items-center gap-4 mt-1">
                    <div>
                        <span class="text-xs text-slate-500 font-bold">جۆری A:</span>
                        <span class="text-xl font-black text-red-600 block">{{ number_format($hemophiliaACount) }}</span>
                    </div>
                    <div class="border-r border-slate-200 dark:border-slate-700 pe-4">
                        <span class="text-xs text-slate-500 font-bold">جۆری B:</span>
                        <span class="text-xl font-black text-purple-600 block">{{ number_format($hemophiliaBCount) }}</span>
                    </div>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/60 text-red-600 flex items-center justify-center">
                <x-icon name="heart" class="w-8 h-8" />
            </div>
        </div>

        <!-- Age Classification -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400">پۆلێنی تەمەن</p>
                <div class="flex items-center gap-4 mt-1">
                    <div>
                        <span class="text-xs text-slate-500 font-bold">منداڵ (<18):</span>
                        <span class="text-xl font-black text-emerald-600 block">{{ number_format($childrenCount) }}</span>
                    </div>
                    <div class="border-r border-slate-200 dark:border-slate-700 pe-4">
                        <span class="text-xs text-slate-500 font-bold">گەورەسال:</span>
                        <span class="text-xl font-black text-indigo-600 block">{{ number_format($adultsCount) }}</span>
                    </div>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center">
                <x-icon name="user-group" class="w-8 h-8" />
            </div>
        </div>

        <!-- Financial Aid Summary -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400">کۆی هاوکارییەکان (IQD)</p>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($totalAidAmount) }}</h3>
                <span class="text-xs text-slate-500 mt-1 inline-block">{{ $totalAidCount }} جاری هاوکاری</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center">
                <x-icon name="banknotes" class="w-8 h-8" />
            </div>
        </div>
    </div>

    <!-- Patient Categorization (Green / Yellow / Red Lists) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                <x-icon name="funnel" class="w-5 h-5 text-slate-500" />
                <span>لیستەکانی بەدواداچوونی خۆکارانەی نەخۆشەکان</span>
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Green List -->
            <a href="{{ route('patients.index', ['status' => 'green']) }}" class="p-5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase">لیستی سەوز</span>
                    <h4 class="text-2xl font-black text-emerald-900 dark:text-emerald-100 mt-1">{{ $greenListCount }} نەخۆش</h4>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">داتای تەواو و پەیوەندی بەردەوام</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-lg">
                    ✓
                </div>
            </a>

            <!-- Yellow List -->
            <a href="{{ route('patients.index', ['status' => 'yellow']) }}" class="p-5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <span class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase">لیستی زەرد</span>
                    <h4 class="text-2xl font-black text-amber-900 dark:text-amber-100 mt-1">{{ $yellowListCount }} نەخۆش</h4>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">زانیاری و داتای ناتەواو</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold text-lg">
                    !
                </div>
            </a>

            <!-- Red List -->
            <a href="{{ route('patients.index', ['status' => 'red']) }}" class="p-5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <span class="text-xs font-bold text-rose-700 dark:text-rose-400 uppercase">لیستی سوور</span>
                    <h4 class="text-2xl font-black text-rose-900 dark:text-rose-100 mt-1">{{ $redListCount }} نەخۆش</h4>
                    <p class="text-xs text-rose-600 dark:text-rose-400 mt-0.5">بێوەڵام / پەیوەندی پچڕاو (>90 ڕۆژ)</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold text-lg">
                    ✕
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Patients -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <x-icon name="user-group" class="w-5 h-5 text-red-600" />
                    <span>دواین نەخۆشە تۆمارکراوەکان</span>
                </h3>
                <a href="{{ route('patients.index') }}" class="text-xs font-bold text-red-600 hover:underline">بینینی هەمووی</a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($recentPatients as $patient)
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 font-bold text-slate-600 dark:text-slate-300 flex items-center justify-center text-sm">
                                {{ mb_substr($patient->first_name, 0, 1) }}
                            </div>
                            <div>
                                <a href="{{ route('patients.show', $patient) }}" class="font-bold text-slate-900 dark:text-white hover:text-red-600 text-sm">
                                    {{ $patient->full_name }}
                                </a>
                                <p class="text-xs text-slate-500">{{ $patient->patient_code }} | {{ $patient->phone }}</p>
                            </div>
                        </div>

                        <x-badge :color="$patient->list_status->color()" :label="$patient->list_status->label()" />
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-4 text-center">هیچ نەخۆشێک تۆمار نەکراوە.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Assistances -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <x-icon name="gift" class="w-5 h-5 text-emerald-600" />
                    <span>دواین هاوکارییە دابەشکراوەکان</span>
                </h3>
                <a href="{{ route('assistances.index') }}" class="text-xs font-bold text-red-600 hover:underline">بینینی هەمووی</a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($recentAssistances as $aid)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">
                                {{ $aid->patient?->full_name }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ $aid->category->label() }} — {{ $aid->assistance_date->format('Y-m-d') }}
                            </p>
                        </div>
                        <span class="font-black text-emerald-600 text-sm">
                            {{ number_format($aid->amount) }} IQD
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-4 text-center">هیچ هاوکارییەک تۆمار نەکراوە.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
