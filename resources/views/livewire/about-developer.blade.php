<div class="space-y-8">
    <!-- Hero Branding Card -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-8 text-white border border-slate-800 shadow-xl">
        <div class="absolute -top-12 -left-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-cyan-500 flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-indigo-600/40 shrink-0 border border-white/10">
                    IC
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">I‑CODE Group</h1>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Modern Software Studio</span>
                    </div>
                    <p class="text-sm text-slate-300 font-medium mt-1">ستۆدیۆی سەردەمیانەی گەشەپێدانی نەرمەکاڵا، سیستەمی پزیشکی، و تەکنەلۆجیا</p>
                </div>
            </div>

            <a href="https://icodegroup.net/" target="_blank" class="px-5 py-2.5 rounded-xl bg-white text-slate-900 font-black text-xs hover:bg-slate-100 transition shadow-md flex items-center gap-2 shrink-0">
                <x-icon name="globe-alt" class="w-4 h-4 text-indigo-600" />
                <span>سەردانی ماڵپەڕی فەرمی</span>
            </a>
        </div>
    </div>

    <!-- About Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Description -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
                <h3 class="font-black text-slate-900 dark:text-white text-lg flex items-center gap-2">
                    <x-icon name="code-bracket" class="w-6 h-6 text-rose-600" />
                    <span>دەربارەی ئایکۆد گروپ (iCode Group)</span>
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    <strong>ئایکۆد گروپ (iCode Group)</strong> دامەزراوەیەکی پزیشکی و تەکنەلۆجیای سەردەمیانەیە بۆ دروستکردن و گەشەپێدانی سیستەمی هۆشمەند، ئەپڵیکەیشنی مۆبایل، و ماڵپەڕی زانستی و تەندروستی. ئەم سیستەمەی بەڕێوەبردنی نەخۆشانی هیمۆفیلیا لەلایەن تیمی ئایکۆد گروپ گەشەی پێدراوە بە بەرزترین ستانداردەکانی ئاسایش، خێرایی، و دیزاینی ئاسانی بەکارهێنان.
                </p>
            </div>

            <!-- Services Offered -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">
                <h3 class="font-black text-slate-900 dark:text-white text-base">خزمەتگوزاری و بەرهەمەکانمان</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200/80 dark:border-slate-700 space-y-1">
                        <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-extrabold text-sm">
                            <x-icon name="device-phone-mobile" class="w-5 h-5" />
                            <span>ئەپڵیکەیشنی مۆبایل</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">دروستکردنی ئەپی iOS & Android بە بەرزترین کوالێتی.</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200/80 dark:border-slate-700 space-y-1">
                        <div class="flex items-center gap-2 text-rose-600 dark:text-rose-400 font-extrabold text-sm">
                            <x-icon name="building-office-2" class="w-5 h-5" />
                            <span>سیستەمی تەندروستی و بنکەی داتا</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">سیستەمی تایبەت بە نەخۆشخانە، ڕێکخراو و بنکەکانی تەندروستی.</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200/80 dark:border-slate-700 space-y-1">
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-extrabold text-sm">
                            <x-icon name="cloud" class="w-5 h-5" />
                            <span>کلاود و سێرڤەری گواستنەوە</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">هۆستینگ، سێرڤەری خێرا، پاراستنی SSL و Backup خودکار.</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200/80 dark:border-slate-700 space-y-1">
                        <div class="flex items-center gap-2 text-cyan-600 dark:text-cyan-400 font-extrabold text-sm">
                            <x-icon name="computer-desktop" class="w-5 h-5" />
                            <span>سیستەمی ژمێریاری و POS</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">سیستەمی بەڕێوەبردنی کۆگا، فرۆشتن و دارایی.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Social Cards Sidebar -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-5">
                <h3 class="font-black text-slate-900 dark:text-white text-base border-b pb-3">پەیوەندی و پشتیوانیی ڕاستەوخۆ</h3>

                <div class="space-y-3">
                    <a href="https://wa.me/9647700941717" target="_blank" class="flex items-center justify-between p-3.5 rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 font-bold text-xs hover:bg-emerald-100 transition border border-emerald-200 dark:border-emerald-900">
                        <div class="flex items-center gap-3">
                            <x-icon name="chat-bubble-left-right" class="w-5 h-5 text-emerald-600" />
                            <span>واتسئەپی پشتیوانی</span>
                        </div>
                        <span dir="ltr" class="font-mono">+964 770 094 1717</span>
                    </a>

                    <a href="https://facebook.com/icodegroup" target="_blank" class="flex items-center justify-between p-3.5 rounded-xl bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 font-bold text-xs hover:bg-blue-100 transition border border-blue-200 dark:border-blue-900">
                        <div class="flex items-center gap-3">
                            <x-icon name="globe-alt" class="w-5 h-5 text-blue-600" />
                            <span>پەیجی فەیسبووک</span>
                        </div>
                        <span dir="ltr" class="font-mono">/icodegroup</span>
                    </a>

                    <a href="https://t.me/icodegroup" target="_blank" class="flex items-center justify-between p-3.5 rounded-xl bg-cyan-50 text-cyan-700 dark:bg-cyan-950/60 dark:text-cyan-300 font-bold text-xs hover:bg-cyan-100 transition border border-cyan-200 dark:border-cyan-900">
                        <div class="flex items-center gap-3">
                            <x-icon name="paper-airplane" class="w-5 h-5 text-cyan-600" />
                            <span>تێلێگرام</span>
                        </div>
                        <span dir="ltr" class="font-mono">@icodegroup</span>
                    </a>

                    <a href="https://icodegroup.net/" target="_blank" class="flex items-center justify-between p-3.5 rounded-xl bg-slate-100 text-slate-800 dark:bg-slate-700/60 dark:text-slate-200 font-bold text-xs hover:bg-slate-200 transition border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <x-icon name="link" class="w-5 h-5 text-slate-600 dark:text-slate-400" />
                            <span>ماڵپەڕی فەرمی</span>
                        </div>
                        <span dir="ltr" class="font-mono">icodegroup.net</span>
                    </a>
                </div>

                <div class="pt-3 border-t border-slate-100 dark:border-slate-700/80 text-xs text-slate-500 space-y-1">
                    <p class="font-bold text-slate-700 dark:text-slate-300">نوسینگەکانی ئایکۆد گروپ:</p>
                    <p>سلێمانی • هەولێر • کەرکووک</p>
                </div>
            </div>
        </div>
    </div>
</div>
