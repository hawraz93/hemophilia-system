<div class="space-y-6">
    <x-flash-messages />

    <!-- Top Patient Profile Header Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-950/60 text-red-600 flex items-center justify-center font-black text-2xl border border-red-200 dark:border-red-800 shrink-0">
                {{ mb_substr($patient->first_name, 0, 1) }}
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ $patient->full_name }}</h1>
                    <x-badge :color="$patient->list_status->color()" :label="$patient->list_status->label()" />

                    @if($patient->membership_type)
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200">
                            {{ $patient->membership_type->label() }}
                        </span>
                    @endif

                    @if($patient->party_affiliation)
                        <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold border {{ $patient->party_affiliation->badgeClasses() }}">
                            پەیوەندخواز بە: {{ $patient->party_affiliation->label() }}
                        </span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-500 mt-2">
                    <span>کۆد: <code class="font-mono text-slate-800 dark:text-slate-200 font-bold">{{ $patient->patient_code }}</code></span>
                    <span>•</span>
                    <span>ژ. ئەندامێتی: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $patient->membership_number ?? '—' }}</strong></span>
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

            <!-- Support Letter Customization Button -->
            <button wire:click="$set('showSupportModal', true)" class="px-3.5 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition flex items-center gap-1.5 shadow">
                <x-icon name="document-text" class="w-4 h-4" />
                <span>دروستکردنی پشتگیری (نووسراو)</span>
            </button>

            <a href="{{ route('patients.id-card', $patient) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-900 transition flex items-center gap-1.5 shadow">
                <x-icon name="identification" class="w-4 h-4" />
                <span>دروستکردنی کارتی ناسنامە</span>
            </a>

            @can('delete-records')
                <button wire:click="deletePatientPermanently" wire:confirm="ئاگاداری: ئەم نەخۆشە و هەموو تۆمارەکانی (هاوکاری، ئەندامێتی، پزیشکی، بەڵگەنامە و فایلەکان) بە تەواوی و بۆ هەمیشە دەسڕێنەوە و ناگەڕێنەوە. دڵنیایت؟" class="px-3.5 py-2 rounded-xl bg-white text-rose-700 border border-rose-300 font-bold text-xs hover:bg-rose-600 hover:text-white transition flex items-center gap-1.5 shadow-sm">
                    <x-icon name="trash" class="w-4 h-4" />
                    <span>سڕینەوەی تەواوی نەخۆش</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- Completeness Status Alert Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            @if($patient->list_status->value === 'green')
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 flex items-center justify-center font-black">
                    <x-icon name="check-circle" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400">ئەندامی تەواو (دۆخی سەوز)</h3>
                    <p class="text-xs text-slate-500">سەرجەم فۆڕم، بەڵگەنامەکان و رسوماتی ئەندامێتی بە تەواوی تۆمارکراون.</p>
                </div>
            @else
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 border border-amber-500/20 flex items-center justify-center font-black">
                    <x-icon name="exclamation-triangle" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-amber-600 dark:text-amber-400">پڕۆفایلی ناتەواو (دۆخی زەرد / سوور)</h3>
                    <p class="text-xs text-slate-500">پێویستە فۆڕم، بەڵگەنامەکان و رسوماتی ئەندامێتی پڕبکرێنەوە بۆ سەوزبوون.</p>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 text-xs font-bold">
            <span class="flex items-center gap-1 {{ !empty($patient->phone) && !empty($patient->national_id) ? 'text-emerald-600' : 'text-slate-400' }}">
                <x-icon name="{{ !empty($patient->phone) && !empty($patient->national_id) ? 'check' : 'x-mark' }}" class="w-4 h-4" />
                زانیاری فۆڕم
            </span>
            <span>•</span>
            <span class="flex items-center gap-1 {{ $patient->documents->count() > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                <x-icon name="{{ $patient->documents->count() > 0 ? 'check' : 'x-mark' }}" class="w-4 h-4" />
                بەڵگەنامەکان ({{ $patient->documents->count() }})
            </span>
            <span>•</span>
            <span class="flex items-center gap-1 {{ $patient->membershipPayments->count() > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                <x-icon name="{{ $patient->membershipPayments->count() > 0 ? 'check' : 'x-mark' }}" class="w-4 h-4" />
                رسوماتی ئەندامێتی
            </span>
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
                    <h3 class="text-sm font-extrabold text-red-600 border-b pb-2 mb-4">زانیاری کەسی و ناسنامە</h3>
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
                            <span class="text-xs text-slate-400 block font-bold">پلەی ئەندامێتی لە ناو ڕێکخراو</span>
                            <strong class="text-indigo-600 font-extrabold">{{ $patient->membership_type?->label() ?? 'ئەندامی ئاسایی' }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">پەیوەندخوازە بە (حیزبی)</span>
                            @if($patient->party_affiliation)
                                <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold border inline-block mt-0.5 {{ $patient->party_affiliation->badgeClasses() }}">
                                    {{ $patient->party_affiliation->label() }}
                                </span>
                            @else
                                <strong class="text-slate-400">دیاری نەکراوە</strong>
                            @endif
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">ژمارەی کارتی نیشتمانی</span>
                            <strong class="text-slate-800 dark:text-white font-mono">{{ $patient->national_id ?? '—' }}</strong>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-bold">ژمارەی کارتی دەنگدان</span>
                            <strong class="text-slate-800 dark:text-white font-mono">{{ $patient->voting_card_number ?? '—' }}</strong>
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
                            <span class="text-xs text-slate-400 block font-bold">جۆری نەخۆشی</span>
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
            </div>
        @endif

        <!-- Tab 2: Documents with Gallery & Lightbox Viewer -->
        @if($activeTab === 'docs')
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">بەڵگەنامەکانی بارکراو (Documents Gallery)</h3>
                        <p class="text-xs text-slate-500">کارتی نیشتمانی، کارتی دەنگدان، ڕاپۆرتی پزیشکی و فۆتۆکان</p>
                    </div>

                    @if(!auth()->user()->isViewer())
                        <x-button primary wire:click="$set('showDocModal', true)" icon="arrow-up-tray" label="بارکردنی بەڵگەنامەی نوێ" class="font-bold shadow-md shadow-rose-600/20" />
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @forelse($patient->documents as $doc)
                        @php
                            $ext = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp
                        <div x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false" class="bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 flex flex-col justify-between space-y-3 shadow-2xs hover:shadow-md transition">
                            <div class="relative w-full h-40 rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-900 flex items-center justify-center border border-slate-200 dark:border-slate-700 group">
                                @if($isImage)
                                    <img src="{{ route('patient-documents.view', $doc) }}" alt="{{ $doc->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                                @else
                                    <div class="flex flex-col items-center gap-2 text-rose-600">
                                        <x-icon name="document-text" class="w-12 h-12" />
                                        <span class="font-mono text-xs uppercase font-extrabold">{{ $ext }}</span>
                                    </div>
                                @endif

                                <!-- Overlay Hover Buttons -->
                                <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button 
                                        @click="$dispatch('open-lightbox', { title: @js($doc->title), type: @js($doc->document_type?->label()), url: @js(route('patient-documents.view', $doc)), isImage: @js($isImage), printUrl: @js(route('patient-documents.print', $doc)), downloadUrl: @js(route('patient-documents.download', $doc)) })" 
                                        class="p-2.5 rounded-xl bg-white text-slate-900 font-bold hover:bg-rose-600 hover:text-white transition shadow" 
                                        title="بینین و گەورەکردنەوە (Fullscreen Lightbox)"
                                    >
                                        <x-icon name="magnifying-glass-plus" class="w-5 h-5" />
                                    </button>

                                    <a href="{{ route('patient-documents.download', $doc) }}" class="p-2.5 rounded-xl bg-white text-slate-900 font-bold hover:bg-emerald-600 hover:text-white transition shadow" title="داگرتن">
                                        <x-icon name="arrow-down-tray" class="w-5 h-5" />
                                    </a>

                                    <a href="{{ route('patient-documents.print', $doc) }}" target="_blank" class="p-2.5 rounded-xl bg-white text-slate-900 font-bold hover:bg-indigo-600 hover:text-white transition shadow" title="چاپکردن">
                                        <x-icon name="printer" class="w-5 h-5" />
                                    </a>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-slate-900 dark:text-white truncate">{{ $doc->title }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300 font-bold">
                                        {{ $doc->document_type?->label() }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-400 mt-1">
                                    <span>{{ $doc->created_at->format('Y-m-d') }}</span>
                                    <span>{{ round($doc->file_size / 1024, 1) }} KB</span>
                                </div>
                            </div>

                            @can('delete-records')
                                <div class="pt-2 border-t border-slate-200 dark:border-slate-700/80 flex justify-end">
                                    <button wire:click="deleteDocument({{ $doc->id }})" wire:confirm="ئایا دڵنیایت لە سڕینەوەی ئەم بەڵگەنامەیە؟" class="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1">
                                        <x-icon name="trash" class="w-3.5 h-3.5" />
                                        <span>سڕینەوە</span>
                                    </button>
                                </div>
                            @endcan
                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                            <x-icon name="paper-clip" class="w-12 h-12 text-slate-400 mx-auto mb-2" />
                            <p class="text-sm font-bold text-slate-500">هیچ بەڵگەنامەیەک بار نەکراوە.</p>
                            <p class="text-xs text-slate-400 mt-1">بەڵگەنامەکانی کارتی نیشتمانی، کارتی دەنگدان یان فۆتۆ بار بکە.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Tab 3: Assistances Log -->
        @if($activeTab === 'aid')
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">مێژووی هاوکاری و یارمەتییە وەرگیراوەکان</h3>
                    @if(!auth()->user()->isViewer())
                        <x-button primary wire:click="$set('showAidModal', true)" icon="plus" label="تۆمارکردنی هاوکاری نوێ" class="font-bold" />
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-end text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                            <tr>
                                <th class="p-3">ژمارەی هاوکاری</th>
                                <th class="p-3">بەروار</th>
                                <th class="p-3">جۆری هاوکاری</th>
                                <th class="p-3">سەرچاوە / دابینکەر</th>
                                <th class="p-3">سەرچاوەی پارە</th>
                                <th class="p-3">بڕی دراو (IQD)</th>
                                <th class="p-3">تێبینی</th>
                                @can('delete-records')<th class="p-3"></th>@endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($patient->assistances as $aid)
                                <tr>
                                    <td class="p-3 font-mono font-bold">{{ $aid->assistance_number }}</td>
                                    <td class="p-3 text-slate-500">{{ $aid->assistance_date->format('Y-m-d') }}</td>
                                    <td class="p-3 font-bold">{{ $aid->category->label() }}</td>
                                    <td class="p-3">{{ $aid->source_funder ?: '—' }}</td>
                                    <td class="p-3 text-xs font-bold">{{ $aid->funding_source?->shortLabel() ?? '—' }}</td>
                                    <td class="p-3 font-black text-emerald-600">{{ number_format($aid->amount) }}</td>
                                    <td class="p-3 text-xs text-slate-500">{{ $aid->notes ?? '—' }}</td>
                                    @can('delete-records')
                                        <td class="p-3">
                                            <button wire:click="deleteAssistance({{ $aid->id }})" wire:confirm="ئایا دڵنیایت لە سڕینەوەی ئەم هاوکارییە؟ ئەگەر لە کۆگاوە بووبێت دانەکە دەگەڕێتەوە بۆ کۆگا." class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 transition" title="سڕینەوە">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-slate-400">هیچ هاوکارییەک تۆمار نەکراوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Tab 4: Memberships -->
        @if($activeTab === 'membership')
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">تۆماری ئەندامێتی و وەسڵەکانی پارەدان</h3>
                    @if(!auth()->user()->isViewer())
                        <x-button primary wire:click="$set('showPaymentModal', true)" icon="plus" label="تۆمارکردنی وەسلێک" class="font-bold" />
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-end text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                            <tr>
                                <th class="p-3">بەرواری پارەدان</th>
                                <th class="p-3">ژمارەی وەسڵ</th>
                                <th class="p-3">بڕی دانراو (IQD)</th>
                                <th class="p-3">تێبینی</th>
                                @can('delete-records')<th class="p-3"></th>@endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($patient->membershipPayments as $pay)
                                <tr>
                                    <td class="p-3 font-mono">{{ $pay->payment_date->format('Y-m-d') }}</td>
                                    <td class="p-3 font-mono font-bold">{{ $pay->receipt_number ?? '—' }}</td>
                                    <td class="p-3 font-black text-emerald-600">
                                        {{ number_format($pay->amount_paid) }}
                                        @if($pay->is_exempt)
                                            <span class="ms-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950 dark:text-amber-300">لێخۆشبوون</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-xs text-slate-500">{{ $pay->notes ?? '—' }}</td>
                                    @can('delete-records')
                                        <td class="p-3">
                                            <button wire:click="deleteMembershipPayment({{ $pay->id }})" wire:confirm="ئایا دڵنیایت لە سڕینەوەی ئەم وەسڵە؟" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 transition" title="سڕینەوە">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400">هیچ رسوماتێکی ئەندامێتی تۆمار نەکراوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Tab 5: Medical Care -->
        @if($activeTab === 'medical')
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">تۆماری چاودێری پزیشکی</h3>
                    @if(!auth()->user()->isViewer())
                        <x-button primary wire:click="$set('showMedicalModal', true)" icon="plus" label="تۆمارکردنی زانیاری پزیشکی" class="font-bold" />
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-end text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                            <tr>
                                <th class="p-3">بەروار</th>
                                <th class="p-3">جۆری سەردان / چالاکی</th>
                                <th class="p-3">نەخۆشخانە</th>
                                <th class="p-3">فاکتەر / دەرمان</th>
                                <th class="p-3">وردەکاری</th>
                                <th class="p-3">تێبینی پزیشک</th>
                                @can('delete-records')<th class="p-3"></th>@endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($patient->medicalLogs as $med)
                                <tr>
                                    <td class="p-3 font-mono">{{ $med->log_date->format('Y-m-d') }}</td>
                                    <td class="p-3 font-bold">{{ $med->log_type->label() }}</td>
                                    <td class="p-3">{{ $med->hospital_name ?? '—' }}</td>
                                    <td class="p-3 font-semibold text-rose-600">{{ $med->factor_name_dose ?? '—' }}</td>
                                    <td class="p-3 text-xs text-slate-600 dark:text-slate-300">{{ $med->details ?? '—' }}</td>
                                    <td class="p-3 text-xs text-slate-500">{{ $med->doctor_notes ?? '—' }}</td>
                                    @can('delete-records')
                                        <td class="p-3">
                                            <button wire:click="deleteMedicalLog({{ $med->id }})" wire:confirm="ئایا دڵنیایت لە سڕینەوەی ئەم تۆمارە پزیشکییە؟" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 transition" title="سڕینەوە">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400">هیچ تۆمارێکی پزیشکی تۆمار نەکراوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Tab 6: Follow-up Contacts -->
        @if($activeTab === 'contacts')
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">مێژووی پەیوەندییەکانی بەدواداچوون</h3>
                    @if(!auth()->user()->isViewer())
                        <x-button primary wire:click="$set('showContactModal', true)" icon="plus" label="تۆمارکردنی پەیوەندی" class="font-bold" />
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-end text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                            <tr>
                                <th class="p-3">بەروار</th>
                                <th class="p-3">کەناڵی پەیوەندی</th>
                                <th class="p-3">ئەنجامی پەیوەندی</th>
                                <th class="p-3">تێبینی و دەرئەنجام</th>
                                <th class="p-3">تۆمارکەر</th>
                                @can('delete-records')<th class="p-3"></th>@endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($patient->contacts as $con)
                                <tr>
                                    <td class="p-3 font-mono">{{ $con->contact_date->format('Y-m-d') }}</td>
                                    <td class="p-3 font-bold">{{ $con->channel->label() }}</td>
                                    <td class="p-3">
                                        @if($con->is_successful)
                                            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">سەرکەوتوو بوو</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300">وەڵامی نەدایەوە / بەردەست نەبوو</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-xs text-slate-600 dark:text-slate-300">{{ $con->outcome_notes ?? '—' }}</td>
                                    <td class="p-3 text-xs text-slate-500">{{ $con->user?->name ?? '—' }}</td>
                                    @can('delete-records')
                                        <td class="p-3">
                                            <button wire:click="deleteContact({{ $con->id }})" wire:confirm="ئایا دڵنیایت لە سڕینەوەی ئەم تۆمارەی پەیوەندی؟" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 transition" title="سڕینەوە">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400">هیچ پەیوەندییەکی بەدواداچوون تۆمار نەکراوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Tab 7: Activity Logs -->
        @if($activeTab === 'logs')
            <div class="p-6 space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">مێژووی چالاکی و گۆڕانکارییەکان</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-end text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 font-bold border-b">
                            <tr>
                                <th class="p-3">کات و بەروار</th>
                                <th class="p-3">جۆری چالاکی</th>
                                <th class="p-3">بەکارهێنەر</th>
                                <th class="p-3">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($patient->auditLogs as $log)
                                <tr>
                                    <td class="p-3 font-mono text-xs">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="p-3 font-bold text-xs">{{ $log->event }}</td>
                                    <td class="p-3 text-xs">{{ $log->user?->name ?? 'سیستەم' }}</td>
                                    <td class="p-3 font-mono text-xs text-slate-400">{{ $log->ip_address ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-400">هیچ مێژوویەکی چالاکی تۆمار نەکراوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Upload Document Modal with Live Loading Progress -->
    <x-modal-card title="بارکردنی بەڵگەنامەی نوێ" wire:model="showDocModal" max-width="md">
        <form wire:submit="uploadDocument" class="space-y-4">
            <x-input wire:model="doc_title" label="ناوی بەڵگەنامە *" placeholder="کارتی نیشتمانی، ڕاپۆرتی پزیشکی..." />

            <x-native-select wire:model="doc_type" label="جۆری بەڵگەنامە *">
                @foreach(\App\Enums\DocumentType::cases() as $dt)
                    <option value="{{ $dt->value }}">{{ $dt->label() }}</option>
                @endforeach
            </x-native-select>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">فایلی بەڵگەنامە (وێنە یان PDF) *</label>
                <input type="file" wire:model="doc_file" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 border border-slate-300 dark:border-slate-700 rounded-xl p-1" />

                <!-- Live Loading Spinner & Indicator -->
                <div wire:loading wire:target="doc_file" class="flex items-center gap-2 text-xs font-bold text-rose-600 py-1">
                    <x-icon name="arrow-path" class="w-4 h-4 animate-spin" />
                    <span>تکایە چاوەڕێ بکە... لە حاڵەتی بارکردندایە (Uploading File)...</span>
                </div>
            </div>

            <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button type="submit" primary label="بارکردن" spinner="uploadDocument" wire:loading.attr="disabled" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </form>
    </x-modal-card>

    <!-- Interactive Lightbox Viewer Modal for Fullscreen Image/PDF Preview -->
    <div 
        x-data="{ 
            open: false, 
            title: '', 
            type: '', 
            url: '', 
            isImage: true, 
            printUrl: '', 
            downloadUrl: '',
            zoom: 100,
            rotation: 0,
            zoomIn() { if(this.zoom < 250) this.zoom += 25; },
            zoomOut() { if(this.zoom > 50) this.zoom -= 25; },
            rotate() { this.rotation = (this.rotation + 90) % 360; },
            reset() { this.zoom = 100; this.rotation = 0; }
        }" 
        @open-lightbox.window="
            open = true; 
            title = $event.detail.title; 
            type = $event.detail.type; 
            url = $event.detail.url; 
            isImage = $event.detail.isImage; 
            printUrl = $event.detail.printUrl; 
            downloadUrl = $event.detail.downloadUrl;
            reset();
        " 
        x-show="open" 
        x-transition.opacity 
        class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md flex flex-col justify-between p-4 sm:p-6" 
        style="display: none;"
    >
        <!-- Top Control Bar -->
        <div class="flex items-center justify-between bg-slate-900/90 border border-slate-800 rounded-2xl p-3 px-5 text-white shadow-2xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center font-black">
                    <x-icon name="magnifying-glass" class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="font-black text-sm text-white" x-text="title"></h3>
                    <p class="text-[11px] text-slate-400" x-text="'جۆر: ' + type"></p>
                </div>
            </div>

            <!-- Toolbar Controls -->
            <div class="flex items-center gap-2">
                <template x-if="isImage">
                    <div class="flex items-center gap-1 bg-slate-800 p-1 rounded-xl border border-slate-700">
                        <button @click="zoomIn()" class="p-1.5 hover:bg-slate-700 rounded-lg text-slate-300 hover:text-white transition" title="گەورەکردنەوە (+)">
                            <x-icon name="magnifying-glass-plus" class="w-5 h-5" />
                        </button>
                        <button @click="zoomOut()" class="p-1.5 hover:bg-slate-700 rounded-lg text-slate-300 hover:text-white transition" title="بچووککردنەوە (-)">
                            <x-icon name="magnifying-glass-minus" class="w-5 h-5" />
                        </button>
                        <button @click="rotate()" class="p-1.5 hover:bg-slate-700 rounded-lg text-slate-300 hover:text-white transition" title="سوڕاندنەوە (90 deg)">
                            <x-icon name="arrow-path" class="w-5 h-5" />
                        </button>
                        <span class="text-xs font-mono font-bold px-2 text-slate-400" x-text="zoom + '%'"></span>
                    </div>
                </template>

                <a :href="downloadUrl" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-1.5 shadow">
                    <x-icon name="arrow-down-tray" class="w-4 h-4" />
                    <span>داگرتن</span>
                </a>

                <a :href="printUrl" target="_blank" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-1.5 shadow">
                    <x-icon name="printer" class="w-4 h-4" />
                    <span>چاپکردن</span>
                </a>

                <button @click="open = false" class="p-2 bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white rounded-xl transition ms-2">
                    <x-icon name="x-mark" class="w-6 h-6" />
                </button>
            </div>
        </div>

        <!-- Center Document Content Area -->
        <div class="flex-1 flex items-center justify-center overflow-auto p-4 my-2">
            <template x-if="isImage">
                <img 
                    :src="url" 
                    :alt="title" 
                    :style="`transform: scale(${zoom / 100}) rotate(${rotation}deg); transition: transform 0.2s ease-out; max-height: 80vh;`" 
                    class="object-contain rounded-2xl shadow-2xl border border-slate-800"
                />
            </template>

            <template x-if="!isImage">
                <iframe :src="url" class="w-full h-full rounded-2xl border border-slate-800 min-h-[75vh]"></iframe>
            </template>
        </div>
    </div>

    <!-- Custom Support Letter Modal -->
    <div x-data="{ open: @entangle('showSupportModal') }" x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl space-y-5 text-right">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black">
                        <x-icon name="document-text" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 dark:text-white text-base">دروستکردنی نووسراوی پشتگیری فەرمی</h3>
                        <p class="text-[11px] text-slate-400">زانیارییە سەرەکییەکانی سەرپەڕەی نوسراوەکە بنووسە</p>
                    </div>
                </div>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-xl">
                    <x-icon name="x-mark" class="w-6 h-6" />
                </button>
            </div>

            <div class="space-y-4">
                <x-input wire:model="letter_recipient" label="بۆ / لایەنی پەیوەندیدار *" placeholder="سەرجەم لایەنە پەیوەندیدارەکان / بەڕێوەبەرایەتی ..." />
                <x-input wire:model="letter_subject" label="بابەت / بابەتی پشتگیری *" placeholder="نوسراوی پشتگیری / پشتگیری چارەسەر" />
                <x-input wire:model="letter_number" label="ژمارەی نووسراو *" placeholder="مثلاً: 351 یان SUP-2026-0001" />
            </div>

            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                <x-button flat label="پاشگەزبوونەوە" @click="open = false" class="font-bold" />
                <a 
                    x-bind:href="'{{ route('patients.support-letter', $patient) }}?recipient=' + encodeURIComponent($wire.letter_recipient) + '&subject=' + encodeURIComponent($wire.letter_subject) + '&ref_no=' + encodeURIComponent($wire.letter_number)" 
                    target="_blank" 
                    @click="open = false" 
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-extrabold text-xs hover:bg-emerald-700 transition flex items-center gap-2 shadow-md shadow-emerald-600/30"
                >
                    <x-icon name="printer" class="w-4 h-4" />
                    <span>چاپکردنی پشتگیری (A4 Print)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Membership Payment Modal -->
    <x-modal-card title="تۆمارکردنی وەسلێک (رسوماتی ئەندامێتی)" wire:model="showPaymentModal" max-width="md">
        <form wire:submit="recordMembershipPayment" class="space-y-4">
            <x-datetime-picker wire:model="pay_date" label="بەرواری پارەدان *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
            <x-checkbox wire:model.live="pay_exempt" label="لێخۆشبوون (٠ دینار) — تێکڕای خاڵەکانی فۆرمی ئەندامبوون ٨٠ خاڵ و سەرووترە" />
            @if($pay_exempt)
                <div class="p-3 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold dark:bg-amber-950/50 dark:text-amber-300">
                    بڕی پارە سفر دینار تۆمار دەکرێت و ئەم ئەندامە لە پارەی ئەندامێتی ساڵانە بەخشراوە بەپێی بڕیاری کارگێڕی کۆمەڵە.
                </div>
            @else
                <x-currency wire:model="pay_amount" label="بڕی دانراو (IQD) *" thousands="," :precision="0" placeholder="25000" />
            @endif
            <x-input wire:model="pay_receipt" label="ژمارەی وەسڵ" placeholder="مثلاً: 123456" />
            <x-textarea wire:model="pay_notes" label="تێبینی" placeholder="تێبینی زیادە..." />

            <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button type="submit" primary label="تۆمارکردنی پارەدان" spinner="recordMembershipPayment" wire:loading.attr="disabled" class="font-bold shadow-md shadow-emerald-600/20" />
            </div>
        </form>
    </x-modal-card>

    <!-- Assistance / Aid Modal -->
    <x-modal-card title="تۆمارکردنی هاوکاری نوێ" wire:model="showAidModal" max-width="md">
        <form wire:submit="addAssistance" class="space-y-4">
            <x-datetime-picker wire:model="aid_date" label="بەرواری دابەشکردن / هاوکاری *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />

            <div class="grid grid-cols-2 gap-2">
                <button type="button" wire:click="$set('aid_mode', 'campaign')" class="p-3 rounded-xl border text-xs font-extrabold transition {{ $aid_mode === 'campaign' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700' }}">
                    لە هاوکارییە هاتووەکانی کۆگا
                </button>
                <button type="button" wire:click="$set('aid_mode', 'direct')" class="p-3 rounded-xl border text-xs font-extrabold transition {{ $aid_mode === 'direct' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700' }}">
                    هاوکاری ڕاستەوخۆ / دارایی
                </button>
            </div>

            @if($aid_mode === 'campaign')
                <x-native-select wire:model="aid_campaign_id" label="کام هاوکاری پێ بدرێت؟ *">
                    <option value="">— هەڵبژێرە —</option>
                    @foreach($availableCampaigns as $camp)
                        <option value="{{ $camp->id }}">{{ $camp->title }} — {{ $camp->source_funder ?: 'بێ سەرچاوە' }} ({{ $camp->remaining_count }} ماوە لە {{ $camp->max_recipients }})</option>
                    @endforeach
                </x-native-select>
                @if($availableCampaigns->isEmpty())
                    <p class="text-xs font-bold text-amber-600">هیچ هاوکارییەک لە کۆگادا نەماوە کە ئەم نەخۆشە پێشتر وەری نەگرتبێت.</p>
                @endif
            @else
                <x-native-select wire:model="aid_category" label="جۆری هاوکاری *">
                    @foreach(\App\Enums\AssistanceCategory::cases() as $cat)
                        <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                    @endforeach
                </x-native-select>

                <x-native-select wire:model.live="aid_funding_source" label="سەرچاوەی پارە / هاوکاری *">
                    @foreach(\App\Enums\FundingSource::directOptions() as $src)
                        <option value="{{ $src->value }}">{{ $src->label() }}</option>
                    @endforeach
                </x-native-select>

                @if($finance && in_array($aid_funding_source, ['membership_income', 'general_income']))
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-800 border border-blue-200 text-xs font-bold dark:bg-blue-950/50 dark:text-blue-300">
                        باڵانسی داهاتی ئەندامێتی: {{ number_format($finance['membership_balance']) }} IQD |
                        باڵانسی داهاتی گشتی: {{ number_format($finance['general_balance']) }} IQD
                        <span class="block font-semibold mt-1">ئەم بڕە ڕاستەوخۆ لە باڵانسی {{ $aid_funding_source === 'membership_income' ? 'داهاتی ئەندامێتی و داهاتی گشتی' : 'داهاتی گشتی' }} کەم دەکرێتەوە.</span>
                    </div>
                @endif

                <x-currency wire:model="aid_amount" label="بڕی هاوکاری (IQD) *" thousands="," :precision="0" placeholder="0" />
                <x-input wire:model="aid_funder" label="ناوی بەخشەر / لایەن" placeholder="کۆمپانیا، کەسایەتی، شوێن..." />
                <x-textarea wire:model="aid_notes" label="تێبینی" placeholder="تێبینی زیاتر..." />
            @endif

            <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button type="submit" primary label="تۆمارکردنی هاوکاری" spinner="addAssistance" wire:loading.attr="disabled" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </form>
    </x-modal-card>

    <!-- Medical Log Modal -->
    <x-modal-card title="تۆمارکردنی زانیاری پزیشکی نوێ" wire:model="showMedicalModal" max-width="md">
        <form wire:submit="addMedicalLog" class="space-y-4">
            <x-datetime-picker wire:model="med_date" label="بەروار *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
            
            <x-native-select wire:model="med_type" label="جۆری سەردان / چالاکی *">
                @foreach(\App\Enums\MedicalLogType::cases() as $mtype)
                    <option value="{{ $mtype->value }}">{{ $mtype->label() }}</option>
                @endforeach
            </x-native-select>

            <x-input wire:model="med_hospital" label="ناوی نەخۆشخانە / مەڵبەند" placeholder="نەخۆشخانەی هیوا..." />
            <x-input wire:model="med_factor" label="ناوی فاکتەر / دەرمان و دۆز" placeholder="Factor VIII - 1000 IU..." />
            <x-textarea wire:model="med_details" label="وردەکاری" placeholder="تێبینی دەربارەی دۆخی نەخۆش..." />
            <x-textarea wire:model="med_notes" label="تێبینی پزیشک" placeholder="ڕاسپاردەکانی پزیشک..." />

            <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button type="submit" primary label="تۆمارکردن" spinner="addMedicalLog" wire:loading.attr="disabled" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </form>
    </x-modal-card>

    <!-- Contact Log Modal -->
    <x-modal-card title="تۆمارکردنی پەیوەندی بەدواداچوون" wire:model="showContactModal" max-width="md">
        <form wire:submit="addContact" class="space-y-4">
            <x-datetime-picker wire:model="contact_date" label="بەرواری پەیوەندی *" :without-time="true" display-format="YYYY-MM-DD" parse-format="YYYY-MM-DD" />
            
            <x-native-select wire:model="contact_channel" label="کەناڵی پەیوەندی *">
                @foreach(\App\Enums\ContactChannel::cases() as $chan)
                    <option value="{{ $chan->value }}">{{ $chan->label() }}</option>
                @endforeach
            </x-native-select>

            <x-checkbox wire:model="contact_successful" label="پەیوەندییەکە سەرکەوتوو بوو (وەڵام درایەوە)" />

            <x-textarea wire:model="contact_notes" label="تێبینی و دەرئەنجام" placeholder="دەرئەنجامی ئاخاوتن و بەدواداچوون..." />

            <div class="pt-3 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                <x-button type="submit" primary label="تۆمارکردن" spinner="addContact" wire:loading.attr="disabled" class="font-bold shadow-md shadow-rose-600/20" />
            </div>
        </form>
    </x-modal-card>
</div>
