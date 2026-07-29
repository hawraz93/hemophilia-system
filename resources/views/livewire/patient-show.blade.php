<div class="space-y-6">
    <!-- Top Patient Profile Header Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-950/60 text-red-600 flex items-center justify-center font-black text-2xl border border-red-200 dark:border-red-800 shrink-0">
                {{ mb_substr($patient->first_name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ $patient->full_name }}</h1>
                    <x-badge :color="$patient->list_status->color()" :label="$patient->list_status->label()" />
                </div>
                <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500 mt-1">
                    <span>کۆد: <code class="font-mono text-slate-800 dark:text-slate-200 font-bold">{{ $patient->patient_code }}</code></span>
                    <span>•</span>
                    <span>کۆدی هیوا: <strong class="text-slate-800 dark:text-slate-200">{{ $patient->hiwa_code ?? 'دیاری نەکراوە' }}</strong></span>
                    <span>•</span>
                    <span class="flex items-center gap-1.5">
                        <span>مۆبایل: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $patient->phone }}</strong></span>
                        @php
                            $waPhone = preg_replace('/[^0-9]/', '', $patient->phone);
                            if (Str::startsWith($waPhone, '0')) {
                                $waPhone = '964' . substr($waPhone, 1);
                            }
                        @endphp
                        <a href="tel:{{ $patient->phone }}" class="p-1 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/60 dark:text-blue-400 transition ms-1" title="پەیوەندی تەلەفۆنی">
                            <x-icon name="phone" class="w-3.5 h-3.5" />
                        </a>
                        <a href="https://wa.me/{{ $waPhone }}" target="_blank" class="p-1 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-400 transition" title="نامەی واتسئەپ">
                            <x-icon name="chat-bubble-left-right" class="w-3.5 h-3.5" />
                        </a>
                    </span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if(!auth()->user()->isViewer())
                <a href="{{ route('patients.edit', $patient) }}" class="px-3.5 py-2 rounded-xl bg-rose-600 text-white font-bold text-xs hover:bg-rose-700 transition flex items-center gap-1.5 shadow-md shadow-rose-600/25">
                    <x-icon name="pencil-square" class="w-4 h-4" />
                    <span>دەستکاریکردنی زانیارییەکان</span>
                </a>
            @endif

            <a href="{{ route('patients.summary-report', $patient) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition flex items-center gap-1.5 shadow">
                <x-icon name="printer" class="w-4 h-4" />
                <span>پرینتکردنی ڕاپۆرتی گشتی</span>
            </a>

            @if($patient->list_status->value === 'green')
                <a href="{{ route('patients.support-letter', $patient) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition flex items-center gap-1.5 shadow">
                    <x-icon name="document-text" class="w-4 h-4" />
                    <span>دروستکردنی پشتیگیری (نوسراو)</span>
                </a>
            @endif

            <a href="{{ route('patients.id-card', $patient) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 transition flex items-center gap-1.5 shadow">
                <x-icon name="identification" class="w-4 h-4" />
                <span>دروستکردنی کارتی ناسنامە</span>
            </a>
        </div>
    </div>

    <!-- Main Tabs Navigation -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2 p-2 border-b border-slate-200 dark:border-slate-700 overflow-x-auto bg-slate-50 dark:bg-slate-800">
            <button wire:click="$set('activeTab', 'info')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'info' ? 'bg-red-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <x-icon name="user" class="w-4 h-4" />
                <span>زانیاری کەسی و تەندروستی</span>
            </button>

            <button wire:click="$set('activeTab', 'docs')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'docs' ? 'bg-red-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <x-icon name="paper-clip" class="w-4 h-4" />
                <span>بەڵگەنامەکان ({{ $patient->documents->count() }})</span>
            </button>

            <button wire:click="$set('activeTab', 'aid')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'aid' ? 'bg-red-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <x-icon name="gift" class="w-4 h-4" />
                <span>هاوکارییەکان ({{ $patient->assistances->count() }})</span>
            </button>

            <button wire:click="$set('activeTab', 'membership')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'membership' ? 'bg-red-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <x-icon name="identification" class="w-4 h-4" />
                <span>ئەندامێتی و رسومات</span>
            </button>

            <button wire:click="$set('activeTab', 'medical')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'medical' ? 'bg-red-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <x-icon name="clipboard-document-check" class="w-4 h-4" />
                <span>چاودێری پزیشکی ({{ $patient->medicalLogs->count() }})</span>
            </button>

            <button wire:click="$set('activeTab', 'contacts')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'contacts' ? 'bg-red-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <x-icon name="phone" class="w-4 h-4" />
                <span>پەیوەندییەکانی بەدواداچوون ({{ $patient->contacts->count() }})</span>
            </button>

            <button wire:click="$set('activeTab', 'logs')" class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'logs' ? 'bg-red-600 text-white shadow' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <x-icon name="clock" class="w-4 h-4" />
                <span>مێژووی چالاکییەکان ({{ $patient->auditLogs->count() }})</span>
            </button>
        </div>

        <!-- Tab 1: Info -->
        @if($activeTab === 'info')
            <div class="p-6 space-y-6">
                <!-- Personal Info Grid -->
                <div>
                    <h3 class="text-sm font-extrabold text-red-600 border-b pb-2 mb-4">زانیاری کەسی و ناونیشان</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">ناوی ناوخۆیی</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->first_name }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">ناوی باوک</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->father_name }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">ناوی باپیر</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->grandfather_name }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">ڕەگەز</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->gender?->label() }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">بەرواری لەدایکبوون</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->dob?->format('Y-m-d') ?? '—' }} ({{ $patient->age }} ساڵ)</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">دۆخی هاوسەرگیری</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->marital_status?->label() ?? '—' }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">ژمارەی منداڵ</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->children_count }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">ناونیشانی تەواو</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->governorate }} / {{ $patient->district }} - {{ $patient->neighborhood }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Medical Info Grid -->
                <div>
                    <h3 class="text-sm font-extrabold text-red-600 border-b pb-2 mb-4">زانیاری تەندروستی و پزیشکی</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">جۆری هیمۆفیلیا</span>
                            <strong class="text-red-600 dark:text-red-400 font-extrabold">{{ $patient->hemophilia_type?->label() }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">پلەی نەخۆشی</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->severity?->label() }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">گروپی خوێن</span>
                            <strong class="text-emerald-600 font-black">{{ $patient->blood_group?->value ?? 'دیاری نەکراوە' }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Inhibitor Status</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->inhibitor_status?->label() }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Hepatitis B</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->hepatitis_b?->label() }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">Hepatitis C</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->hepatitis_c?->label() }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">HIV</span>
                            <strong class="text-slate-800 dark:text-white">{{ $patient->hiv?->label() }}</strong>
                        </div>
                    </div>
                </div>

                @if($patient->medical_notes)
                    <div>
                        <h4 class="text-xs font-bold text-slate-500 mb-1">تێبینی پزیشکی:</h4>
                        <p class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl text-sm text-slate-700 dark:text-slate-300">
                            {{ $patient->medical_notes }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Tab 2: Documents -->
        @if($activeTab === 'docs')
            <div class="p-6 space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-200/80 dark:border-slate-700/80 pb-4">
                    <div>
                        <h3 class="font-black text-slate-900 dark:text-white text-base">بەڵگەنامە و فایلە بارکراوەکان</h3>
                        <p class="text-xs text-slate-400">کاتی بارکردن و پۆلێنکردنی فایلی نەخۆش بەپێی جۆر</p>
                    </div>

                    @if(!auth()->user()->isViewer())
                        <x-button primary icon="cloud-arrow-up" wire:click="$set('showDocModal', true)" label="بارکردنی بەڵگەنامە" class="font-extrabold shadow-md shadow-rose-600/25" />
                    @endif
                </div>

                <!-- Category Filter Pills -->
                <div x-data="{ docFilter: 'all' }" class="space-y-6">
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar">
                        <button @click="docFilter = 'all'" :class="docFilter === 'all' ? 'bg-slate-900 text-white dark:bg-rose-600 shadow-xs' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-2">
                            <span>هەموو بەڵگەنامەکان</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] bg-white/20">{{ $patient->documents->count() }}</span>
                        </button>
                        <button @click="docFilter = 'identity'" :class="docFilter === 'identity' ? 'bg-slate-900 text-white dark:bg-rose-600 shadow-xs' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-2">
                            <span>🆔 ناسنامە و کارتی نیشتمانی</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] bg-white/20">{{ $patient->documents->filter(fn($d) => in_array($d->document_type->value, ['national_card', 'id_card']))->count() }}</span>
                        </button>
                        <button @click="docFilter = 'medical'" :class="docFilter === 'medical' ? 'bg-slate-900 text-white dark:bg-rose-600 shadow-xs' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-2">
                            <span>📋 ڕاپۆرتی پزیشکی و تاقیکردنەوە</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] bg-white/20">{{ $patient->documents->filter(fn($d) => in_array($d->document_type->value, ['medical_report', 'lab_result']))->count() }}</span>
                        </button>
                        <button @click="docFilter = 'photo'" :class="docFilter === 'photo' ? 'bg-slate-900 text-white dark:bg-rose-600 shadow-xs' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-2">
                            <span>🖼️ وێنەی نەخۆش</span>
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] bg-white/20">{{ $patient->documents->filter(fn($d) => $d->document_type->value === 'patient_photo')->count() }}</span>
                        </button>
                    </div>

                    <!-- Documents Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($patient->documents as $doc)
                            @php
                                $typeVal = $doc->document_type->value;
                                $group = match($typeVal) {
                                    'national_card', 'id_card' => 'identity',
                                    'medical_report', 'lab_result' => 'medical',
                                    'patient_photo' => 'photo',
                                    default => 'other',
                                };
                                $ext = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                                $isImage = Str::startsWith($doc->mime_type ?? '', 'image/') || in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                            @endphp
                            <div x-show="docFilter === 'all' || docFilter === '{{ $group }}'" x-transition class="p-4 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 space-y-3 shadow-2xs hover:shadow-md transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-black px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/80">
                                        {{ $doc->document_type->label() }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono font-bold">{{ round($doc->file_size / 1024) }} KB</span>
                                </div>

                                @if($isImage)
                                    <div class="h-36 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 group relative">
                                        <img src="{{ asset('storage/' . $doc->file_path) }}" alt="{{ $doc->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-200" />
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold gap-1.5">
                                            <x-icon name="eye" class="w-4 h-4" />
                                            <span>پیشاندانی تەواو</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="h-24 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-center gap-3 text-slate-500">
                                        <x-icon name="document-text" class="w-8 h-8 text-rose-500" />
                                        <div>
                                            <p class="text-xs font-mono font-bold text-slate-700 dark:text-slate-300 uppercase">{{ $ext ?: 'FILE' }}</p>
                                            <p class="text-[10px] text-slate-400">فایلی ڕەسمی</p>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <h4 class="font-extrabold text-slate-900 dark:text-white text-sm truncate">{{ $doc->title }}</h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5">بەرواری بارکردن: {{ $doc->created_at->format('Y-m-d') }}</p>
                                </div>

                                <div class="pt-2 border-t border-slate-100 dark:border-slate-700/80 flex items-center justify-between">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-xs font-extrabold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                                        <x-icon name="arrow-down-tray" class="w-4 h-4" />
                                        <span>بینیین / داگرتن</span>
                                    </a>

                                    @if(!auth()->user()->isViewer())
                                        <button wire:click="deleteDocument({{ $doc->id }})" wire:confirm="دڵنیایت لە سڕینەوەی ئەم بەڵگەنامەیە؟" class="text-slate-400 hover:text-rose-600 transition p-1 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40" title="سڕینەوەی بەڵگەنامە">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                                <x-icon name="document-text" class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-2" />
                                <p class="text-xs font-bold text-slate-500">هیچ بەڵگەنامەیەک بارنەکراوە.</p>
                                <p class="text-[10px] text-slate-400 mt-1">تکایە دوگمەی "بارکردنی بەڵگەنامە" بەکاربهێنە بۆ زیادکردنی ناسنامە و ڕاپۆرتی پزیشکی</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        <!-- Tab 3: Assistance -->
        @if($activeTab === 'aid')
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">مێژووی هاوکاری و یارمەتییەکان</h3>
                    @if(!auth()->user()->isViewer())
                        <x-button sm primary icon="plus" wire:click="$set('showAidModal', true)" label="تۆمارکردنی هاوکاری نوێ" />
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-end text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                            <tr>
                                <th class="p-3">ژمارەی هاوکاری</th>
                                <th class="p-3">بەروار</th>
                                <th class="p-3">جۆری هاوکاری</th>
                                <th class="p-3">سەرچاوەی هاوکاری</th>
                                <th class="p-3">بڕی پارە (IQD)</th>
                                <th class="p-3">تێبینی</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($patient->assistances as $aid)
                                <tr>
                                    <td class="p-3 font-mono font-bold text-slate-600 dark:text-slate-300">{{ $aid->assistance_number }}</td>
                                    <td class="p-3 text-slate-600 dark:text-slate-400">{{ $aid->assistance_date->format('Y-m-d') }}</td>
                                    <td class="p-3 font-bold text-slate-800 dark:text-white">{{ $aid->category->label() }}</td>
                                    <td class="p-3 text-slate-600 dark:text-slate-300">{{ $aid->source_funder ?? '—' }}</td>
                                    <td class="p-3 font-black text-emerald-600">{{ number_format($aid->amount) }}</td>
                                    <td class="p-3 text-xs text-slate-500">{{ $aid->notes ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400">هیچ هاوکارییەک تائێستا تۆمار نەکراوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Tab 4: Membership -->
        @if($activeTab === 'membership')
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-extrabold text-slate-900 dark:text-white text-base">دۆخی ئەندامێتی و مێژووی رسومات</h3>
                        <p class="text-xs text-slate-400">ژمارەی ئەندامێتی: <strong class="text-slate-800 dark:text-white font-mono">{{ $patient->membership_number ?? 'نادیار' }}</strong></p>
                    </div>

                    @if(!auth()->user()->isViewer())
                        <x-button sm primary icon="plus" wire:click="$set('showPaymentModal', true)" label="تۆمارکردنی پادانی ئەندامێتی" />
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-end text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                            <tr>
                                <th class="p-3">بەرواری پارەدان</th>
                                <th class="p-3">ژمارەی وەسڵ</th>
                                <th class="p-3">بڕی دراو (IQD)</th>
                                <th class="p-3">تێبینی</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($patient->membershipPayments as $pay)
                                <tr>
                                    <td class="p-3 text-slate-600 font-bold dark:text-slate-300">{{ $pay->payment_date->format('Y-m-d') }}</td>
                                    <td class="p-3 font-mono font-bold text-slate-600 dark:text-slate-300">{{ $pay->receipt_number ?? '—' }}</td>
                                    <td class="p-3 font-black text-emerald-600">{{ number_format($pay->amount_paid) }}</td>
                                    <td class="p-3 text-xs text-slate-500">{{ $pay->notes ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-400">هیچ پارەدانێک تۆمارنەکراوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Tab 5: Medical Logs -->
        @if($activeTab === 'medical')
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">تۆماری چاودێری پزیشکی و سەردانەکان</h3>
                    @if(!auth()->user()->isViewer())
                        <x-button sm primary icon="plus" wire:click="$set('showMedicalModal', true)" label="زیادکردنی تۆماری پزیشکی" />
                    @endif
                </div>

                <div class="space-y-3">
                    @forelse($patient->medicalLogs as $log)
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/40 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-red-600 text-sm">{{ $log->log_type->label() }}</span>
                                <span class="text-xs text-slate-400">{{ $log->log_date->format('Y-m-d') }}</span>
                            </div>
                            @if($log->hospital_name)
                                <p class="text-xs text-slate-600 font-bold">نەخۆشخانە: {{ $log->hospital_name }}</p>
                            @endif
                            @if($log->factor_name_dose)
                                <p class="text-xs text-slate-600 font-bold">Factor: {{ $log->factor_name_dose }}</p>
                            @endif
                            @if($log->details)
                                <p class="text-sm text-slate-700 dark:text-slate-300 pt-1">{{ $log->details }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">هیچ تۆمارێکی پزیشکی نییە.</div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Tab 6: Contacts -->
        @if($activeTab === 'contacts')
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">تۆماری بەدواداچوون و پەیوەندییەکان</h3>
                    @if(!auth()->user()->isViewer())
                        <x-button sm primary icon="plus" wire:click="$set('showContactModal', true)" label="زیادکردنی تێبینی پەیوەندی" />
                    @endif
                </div>

                <div class="space-y-3">
                    @forelse($patient->contacts as $contact)
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/40 flex items-start justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $contact->channel->label() }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded font-bold {{ $contact->is_successful ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $contact->is_successful ? 'سەرکەوتوو' : 'بێوەڵام' }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">{{ $contact->outcome_notes }}</p>
                            </div>
                            <span class="text-xs text-slate-400">{{ $contact->contact_date->format('Y-m-d') }}</span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">هیچ پەیوەندییەک تۆمارنەکراوە.</div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Tab 7: Activity Logs -->
        @if($activeTab === 'logs')
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-extrabold text-slate-900 dark:text-white text-base">مێژووی چالاکی و گۆڕانکارییەکان</h3>
                        <p class="text-xs text-slate-400">تۆماری گشت چالاکییەکانی بەکارهێنەران لەسەر پرۆفایلی ئەم نەخۆشە</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($patient->auditLogs as $log)
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/40 flex items-start justify-between">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-950 dark:text-rose-400 flex items-center justify-center font-bold shrink-0">
                                    <x-icon name="clock" class="w-5 h-5" />
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $log->user?->name ?? 'سیستەم' }}</span>
                                        <span class="text-xs px-2.5 py-0.5 rounded-md font-extrabold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                                            {{ $log->event }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">IP Address: <code class="font-mono text-slate-700 dark:text-slate-300">{{ $log->ip_address ?? '—' }}</code></p>
                                </div>
                            </div>
                            <span class="text-xs text-slate-400 font-mono font-bold">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">هیچ تۆمارێکی چالاکی نییە.</div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>

    <!-- Document Modal -->
    <x-modal-card title="بارکردنی بەڵگەنامە" wire:model="showDocModal" max-width="lg">
        <div class="space-y-4">
            <x-input wire:model="doc_title" label="سەردێڕی بەڵگەنامە *" placeholder="ناوی فایلی بەڵگەنامەکە (بۆ نموونە: ناسنامە / وێنە)" />
            
            <x-native-select wire:model="doc_type" label="جۆری بەڵگەنامە *">
                <option value="national_card">🆔 کارتی نیشتمانی</option>
                <option value="id_card">🎴 ناسنامەی شارستانی</option>
                <option value="medical_report">📋 ڕاپۆرتی پزیشکی</option>
                <option value="lab_result">🧪 ئەنجامی تاقیکردنەوە</option>
                <option value="patient_photo">🖼️ وێنەی نەخۆش</option>
                <option value="other">📁 بەڵگەنامەی تر</option>
            </x-native-select>

            <!-- Custom File Dropzone & Livewire Loading -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">فایلی بەڵگەنامە *</label>
                
                <div class="relative border-2 border-dashed border-rose-300 dark:border-rose-800 hover:border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 rounded-2xl p-6 text-center transition cursor-pointer group">
                    <input type="file" wire:model="doc_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />

                    <!-- Default State -->
                    <div wire:loading.remove wire:target="doc_file" class="space-y-2">
                        @if($doc_file)
                            <div class="flex items-center justify-center gap-3 text-emerald-600 font-bold text-xs">
                                <x-icon name="check-circle" class="w-6 h-6 shrink-0" />
                                <div class="text-right truncate">
                                    <p class="font-bold text-slate-800 dark:text-white truncate">{{ $doc_file->getClientOriginalName() }}</p>
                                    <p class="text-[10px] text-slate-400">{{ round($doc_file->getSize() / 1024) }} KB</p>
                                </div>
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-full bg-rose-100 dark:bg-rose-900/40 text-rose-600 mx-auto flex items-center justify-center group-hover:scale-110 transition">
                                <x-icon name="cloud-arrow-up" class="w-6 h-6" />
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-slate-800 dark:text-slate-200">کلیک بکە یان فایلەکە لێرەدا دابنێ</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">پشتیگیری PNG, JPG, PDF, DOC (بەرزترین قەبارە 10MB)</p>
                            </div>
                        @endif
                    </div>

                    <!-- Livewire Loading State during File Upload -->
                    <div wire:loading wire:target="doc_file" class="space-y-2 py-2">
                        <div class="inline-block animate-spin w-8 h-8 border-3 border-rose-600 border-t-transparent rounded-full"></div>
                        <p class="text-xs font-extrabold text-rose-600 animate-pulse">لە بارکردندایە... تکایە چاوەڕێ بکە</p>
                    </div>
                </div>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="بارکردنی فایل" wire:click="uploadDocument" spinner="uploadDocument" class="font-extrabold shadow-md shadow-rose-600/25 px-5" />
            </div>
        </x-slot:footer>
    </x-modal-card>

    <!-- Aid Modal -->
    <x-modal-card title="تۆمارکردنی هاوکاری نوێ" wire:model="showAidModal" max-width="md">
        <div class="space-y-4">
            <x-datetime-picker wire:model="aid_date" label="بەرواری هاوکاری *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
            <x-native-select wire:model="aid_category" label="جۆری هاوکاری">
                <option value="financial">هاوکاری دارایی</option>
                <option value="medication">دەرمان</option>
                <option value="food">خواردن و بەشەخۆراک</option>
                <option value="medical_supplies">کەرەستەی پزشکی</option>
                <option value="surgery">نەشتەرگەری</option>
                <option value="transport">گواستنەوە</option>
                <option value="other">هاوکاری تر</option>
            </x-native-select>
            <x-currency wire:model="aid_amount" label="بڕی پارە (IQD) *" thousands="," :precision="0" placeholder="0" />
            <x-input wire:model="aid_funder" label="سەرچاوەی هاوکاری" placeholder="خێرخواز / کۆمەڵە" />
            <x-textarea wire:model="aid_notes" label="تێبینی" />
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="پاشەکەوتکردن" wire:click="addAssistance" spinner="addAssistance" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </x-slot:footer>
    </x-modal-card>

    <!-- Contact Modal -->
    <x-modal-card title="تۆماری بەدواداچوونی پەیوەندی" wire:model="showContactModal" max-width="md">
        <div class="space-y-4">
            <x-datetime-picker wire:model="contact_date" label="بەرواری پەیوەندی *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
            <x-native-select wire:model="contact_channel" label="جۆری پەیوەندی">
                <option value="phone">تەلەفۆن</option>
                <option value="whatsapp">واتسئەپ</option>
                <option value="in_person">سەردان</option>
                <option value="other">تر</option>
            </x-native-select>
            <x-checkbox wire:model="contact_successful" label="پەیوەندییەکە سەرکەوتوو بوو؟" />
            <x-textarea wire:model="contact_notes" label="ئەنجام و تێبینی پەیوەندی" />
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="پاشەکەوتکردن" wire:click="addContact" spinner="addContact" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </x-slot:footer>
    </x-modal-card>

    <!-- Medical Modal -->
    <x-modal-card title="تۆماری چاودێری پزیشکی" wire:model="showMedicalModal" max-width="md">
        <div class="space-y-4">
            <x-datetime-picker wire:model="med_date" label="بەروار *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
            <x-native-select wire:model="med_type" label="جۆری ڕووداو / سەردان">
                <option value="hospital_visit">سەردانی نەخۆشخانە</option>
                <option value="bleeding_episode">خوێنڕشتن (Bleeding Episode)</option>
                <option value="admission">داخڵبوونی نەخۆشخانە</option>
                <option value="surgery">نەشتەرگەری</option>
                <option value="lab_test">تاقیکردنەوەی تاقیگە</option>
                <option value="factor_usage">بەکارهێنانی Factor Concentrate</option>
                <option value="doctor_note">تێبینی پزشکی</option>
            </x-native-select>
            <x-input wire:model="med_hospital" label="ناوی نەخۆشخانە" placeholder="نەخۆشخانەی هیوا" />
            <x-input wire:model="med_factor" label="جۆر و بڕی Factor" placeholder="Factor VIII - 1000 IU" />
            <x-textarea wire:model="med_details" label="وردەکاری ڕووداو / سەردان" />
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="پاشەکەوتکردن" wire:click="addMedicalLog" spinner="addMedicalLog" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </x-slot:footer>
    </x-modal-card>

    <!-- Membership Payment Modal -->
    <x-modal-card title="تۆمارکردنی دراو / ئەندامێتی" wire:model="showPaymentModal" max-width="md">
        <div class="space-y-4">
            <x-datetime-picker wire:model="pay_date" label="بەرواری پارەدان *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
            <x-currency wire:model="pay_amount" label="بڕی دراو (IQD) *" thousands="," :precision="0" placeholder="0" />
            <x-input wire:model="pay_receipt" label="ژمارەی وەسڵ / کلاچ" placeholder="REC-1001" />
            <x-textarea wire:model="pay_notes" label="تێبینی" />
        </div>

        <x-slot:footer>
            <div class="flex justify-end gap-2">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button primary label="پاشەکەوتکردن" wire:click="recordMembershipPayment" spinner="recordMembershipPayment" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </x-slot:footer>
    </x-modal-card>
</div>
