<!DOCTYPE html>
<html lang="ckb" dir="rtl" class="h-full bg-slate-950 text-slate-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#e11d48">
        <link rel="apple-touch-icon" href="/icon-192.png">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-slate-950 font-sans antialiased text-slate-100 selection:bg-rose-600 selection:text-white flex flex-col justify-between">
        <!-- Background Lighting FX -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-[30rem] h-[30rem] bg-rose-600/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -left-40 w-[30rem] h-[30rem] bg-indigo-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10">
            <!-- Top Navbar -->
            <header class="h-20 border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-xl sticky top-0 z-50">
                <div class="max-w-7xl mx-auto h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-rose-600 to-red-500 flex items-center justify-center text-white shadow-lg shadow-rose-600/30">
                            <x-icon name="heart" class="w-6 h-6" />
                        </div>
                        <div>
                            <h1 class="font-black text-base text-white">کۆمەڵەی هیمۆفیلیا</h1>
                            <p class="text-[11px] text-rose-400 font-bold">لقی سلێمانی</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-red-600 text-white font-black text-xs shadow-lg shadow-rose-600/30 hover:from-rose-700 hover:to-red-700 transition flex items-center gap-2">
                                <x-icon name="chart-bar" class="w-4 h-4" />
                                <span>داشبۆردی سیستەم</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-red-600 text-white font-black text-xs shadow-lg shadow-rose-600/30 hover:from-rose-700 hover:to-red-700 transition flex items-center gap-2">
                                <x-icon name="arrow-left-on-rectangle" class="w-4 h-4" />
                                <span>چوونه‌ژوورەوەی ئەندامان (Login)</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Hero Banner -->
            <section class="relative py-16 sm:py-24 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-500/10 text-rose-300 border border-rose-500/20 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        ڕێکخراوێکی مرۆیی و تەندروستی سەربەخۆ
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white leading-tight max-w-4xl mx-auto">
                        خزمەتکردن و چاودێری تەندروستی گشتگیری نەخۆشانی <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-red-400">هیمۆفیلیا</span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-300 font-medium max-w-2xl mx-auto leading-relaxed">
                        کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی کار دەکات بۆ دابینکردنی فاکتەر، پشتگیری پزیشکی، هاوکاری دارایی و کۆمەڵایەتی بۆ سەرجەم تووشبووانی نەخۆشی هیمۆفیلیا لە سلێمانی و ناوچەکانی دەوروبەری.
                    </p>

                    <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-rose-600 to-red-600 text-white font-black text-sm shadow-xl shadow-rose-600/30 hover:from-rose-700 transition">
                                چوون بۆ داشبۆردی بەڕێوەبردن
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-rose-600 to-red-600 text-white font-black text-sm shadow-xl shadow-rose-600/30 hover:from-rose-700 transition">
                                چوونه‌ژوورەوە بۆ سیستەمی بەڕێوەبردن
                            </a>
                        @endauth
                        <a href="https://wa.me/9647700941717" target="_blank" class="px-7 py-3.5 rounded-2xl bg-slate-900 text-slate-200 border border-slate-800 font-extrabold text-sm hover:bg-slate-800 transition flex items-center gap-2">
                            <x-icon name="chat-bubble-left-right" class="w-5 h-5 text-emerald-400" />
                            <span>پەیوەندیی فریاگوزاری</span>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Impact Statistics Grid -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-6 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-md text-center space-y-1">
                        <p class="text-3xl font-black text-rose-500">1,200+</p>
                        <p class="text-xs font-bold text-slate-400">نەخۆشی تۆمارکراو</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-md text-center space-y-1">
                        <p class="text-3xl font-black text-indigo-400">450+</p>
                        <p class="text-xs font-bold text-slate-400">هاوکاری دابەشکراو</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-md text-center space-y-1">
                        <p class="text-3xl font-black text-emerald-400">24/7</p>
                        <p class="text-xs font-bold text-slate-400">پشتیوانی و چاودێری</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-slate-900/60 border border-slate-800 backdrop-blur-md text-center space-y-1">
                        <p class="text-3xl font-black text-amber-400">100%</p>
                        <p class="text-xs font-bold text-slate-400">خزمەتگوزاری خۆڕایی</p>
                    </div>
                </div>
            </section>

            <!-- Services Offered -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">
                <div class="text-center space-y-3">
                    <h2 class="text-2xl sm:text-3xl font-black text-white">خزمەتگوزاری و ئامانجەکانی کۆمەڵە</h2>
                    <p class="text-xs sm:text-sm text-slate-400 font-medium">باشترکردنی کوالێتی ژیانی تووشبووانی هیمۆفیلیا</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 space-y-4 hover:border-slate-700 transition">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center font-black">
                            <x-icon name="heart" class="w-6 h-6" />
                        </div>
                        <h3 class="font-extrabold text-white text-base">دابینکردنی فاکتەر و دەرمان</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">بەدواداچوون بۆ دابینکردنی فاکتەر 8 و فاکتەر 9 بە هەماهەنگی لەگەڵ نەخۆشخانەی هیوا و وەزارەتی تەندروستی.</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 space-y-4 hover:border-slate-700 transition">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center font-black">
                            <x-icon name="gift" class="w-6 h-6" />
                        </div>
                        <h3 class="font-extrabold text-white text-base">هاوکاری دارایی و خێرخوازی</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">پێشکەشکردنی هاوکاری دارایی بۆ خێزانە کەمدەرامەتەکان و دابینکردنی پێداویستی تەندروستی.</p>
                    </div>

                    <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 space-y-4 hover:border-slate-700 transition">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center font-black">
                            <x-icon name="document-text" class="w-6 h-6" />
                        </div>
                        <h3 class="font-extrabold text-white text-base">نوسراوی پشتگیری ڕەسمی</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">دەرکردنی نوسراوی ڕەسمی پشتگیری بۆ نەخۆشخانەکان، فەرمانگە حکومییەکان و دروستکردنی کارتی ئەندامێتی.</p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Developer Footer -->
        <footer class="relative z-10 border-t border-slate-900 bg-slate-950/90 py-8 text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="font-bold text-slate-400">© {{ date('Y') }} کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی. هەموو مافەکانی پارێزراوە.</p>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-black text-[9px] flex items-center justify-center">IC</span>
                    <span>پاڵپشتی و گەشەپێدان لەلایەن <a href="https://icodegroup.net/" target="_blank" class="text-indigo-400 font-extrabold hover:underline">ئایکۆد گروپ (iCode Group)</a></span>
                </div>
            </div>
        </footer>
    </body>
</html>
