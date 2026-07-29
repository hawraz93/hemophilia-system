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
                    <span>مۆبایل: <strong class="text-slate-800 dark:text-slate-200 font-mono">{{ $patient->phone }}</strong></span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
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
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-900 dark:text-white text-base">بەڵگەنامە و فایلە بارکراوەکان</h3>
                    @if(!auth()->user()->isViewer())
                        <x-button sm primary icon="plus" wire:click="$set('showDocModal', true)" label="بارکردنی بەڵگەنامە" />
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($patient->documents as $doc)
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/40 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold px-2 py-0.5 rounded bg-red-100 text-red-700">
                                    {{ $doc->document_type->label() }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ round($doc->file_size / 1024) }} KB</span>
                            </div>
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm truncate">{{ $doc->title }}</h4>
                            <p class="text-xs text-slate-400">{{ $doc->created_at->format('Y-m-d') }}</p>
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="block text-center text-xs font-bold text-red-600 hover:underline pt-2 border-t">
                                دەستکاری / داگرتنی فایل
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-slate-400">
                            هیچ بەڵگەنامەیەک بارنەکراوە.
                        </div>
                    @endforelse
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
    </div>

    <!-- Document Modal -->
    <x-modal wire:model="showDocModal">
        <x-card title="بارکردنی بەڵگەنامە">
            <form wire:submit="uploadDocument" class="space-y-4">
                <x-input wire:model="doc_title" label="سەردێڕی بەڵگەنامە *" placeholder="ناسنامە / وێنە / ڕاپۆرت" />
                <x-native-select wire:model="doc_type" label="جۆری بەڵگەنامە">
                    <option value="patient_photo">وێنەی نەخۆش</option>
                    <option value="national_card">کارتی نیشتمانی</option>
                    <option value="id_card">ناسنامە</option>
                    <option value="medical_report">ڕاپۆرتی پزشکی</option>
                    <option value="lab_result">ئەنجامی تاقیکردنەوە</option>
                    <option value="other">بەڵگەنامەی تر</option>
                </x-native-select>
                <input type="file" wire:model="doc_file" class="w-full text-xs text-slate-500 border p-2 rounded-lg" />

                <div class="flex justify-end gap-2 pt-2">
                    <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                    <x-button type="submit" primary label="بارکردن" />
                </div>
            </form>
        </x-card>
    </x-modal>

    <!-- Aid Modal -->
    <x-modal wire:model="showAidModal">
        <x-card title="تۆمارکردنی هاوکاری نوێ">
            <form wire:submit="addAssistance" class="space-y-4">
                <x-input type="date" wire:model="aid_date" label="بەرواری هاوکاری *" />
                <x-native-select wire:model="aid_category" label="جۆری هاوکاری">
                    <option value="financial">هاوکاری دارایی</option>
                    <option value="medication">دەرمان</option>
                    <option value="food">خواردن و بەشەخۆراک</option>
                    <option value="medical_supplies">کەرەستەی پزشکی</option>
                    <option value="surgery">نەشتەرگەری</option>
                    <option value="transport">گواستنەوە</option>
                    <option value="other">هاوکاری تر</option>
                </x-native-select>
                <x-input type="number" wire:model="aid_amount" label="بڕی پارە (IQD) *" />
                <x-input wire:model="aid_funder" label="سەرچاوەی هاوکاری" placeholder="خێرخواز / کۆمەڵە" />
                <x-textarea wire:model="aid_notes" label="تێبینی" />

                <div class="flex justify-end gap-2 pt-2">
                    <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                    <x-button type="submit" primary label="پاشەکەوتکردن" />
                </div>
            </form>
        </x-card>
    </x-modal>

    <!-- Contact Modal -->
    <x-modal wire:model="showContactModal">
        <x-card title="تۆماری بەدواداچوونی پەیوەندی">
            <form wire:submit="addContact" class="space-y-4">
                <x-input type="date" wire:model="contact_date" label="بەرواری پەیوەندی *" />
                <x-native-select wire:model="contact_channel" label="جۆری پەیوەندی">
                    <option value="phone">تەلەفۆن</option>
                    <option value="whatsapp">واتسئەپ</option>
                    <option value="in_person">سەردان</option>
                    <option value="other">تر</option>
                </x-native-select>
                <x-checkbox wire:model="contact_successful" label="پەیوەندییەکە سەرکەوتوو بوو؟" />
                <x-textarea wire:model="contact_notes" label="ئەنجام و تێبینی پەیوەندی" />

                <div class="flex justify-end gap-2 pt-2">
                    <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                    <x-button type="submit" primary label="پاشەکەوتکردن" />
                </div>
            </form>
        </x-card>
    </x-modal>

    <!-- Medical Modal -->
    <x-modal wire:model="showMedicalModal">
        <x-card title="تۆماری چاودێری پزیشکی">
            <form wire:submit="addMedicalLog" class="space-y-4">
                <x-input type="date" wire:model="med_date" label="بەروار *" />
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

                <div class="flex justify-end gap-2 pt-2">
                    <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                    <x-button type="submit" primary label="پاشەکەوتکردن" />
                </div>
            </form>
        </x-card>
    </x-modal>

    <!-- Membership Payment Modal -->
    <x-modal wire:model="showPaymentModal">
        <x-card title="تۆمارکردنی دراو / ئەندامێتی">
            <form wire:submit="recordMembershipPayment" class="space-y-4">
                <x-input type="date" wire:model="pay_date" label="بەرواری پارەدان *" />
                <x-input type="number" wire:model="pay_amount" label="بڕی دراو (IQD) *" />
                <x-input wire:model="pay_receipt" label="ژمارەی وەسڵ / کلاچ" placeholder="REC-1001" />
                <x-textarea wire:model="pay_notes" label="تێبینی" />

                <div class="flex justify-end gap-2 pt-2">
                    <x-button flat label="پاشگەزبوونەوە" x-on:click="close" />
                    <x-button type="submit" primary label="پاشەکەوتکردن" />
                </div>
            </form>
        </x-card>
    </x-modal>
</div>
