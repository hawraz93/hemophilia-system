<!DOCTYPE html>
<html lang="ckb" dir="rtl" class="h-full bg-slate-50 dark:bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'سیستەمی بەڕێوەبردنی نەخۆشانی هیمۆفیلیا' }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#e11d48">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="هیمۆفیلیا">
        <link rel="apple-touch-icon" href="/icon-192.png">

        <script>
            window.deferredPrompt = null;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                window.deferredPrompt = e;
            });
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            @media print {
                aside, header, #pwa-install-btn, .print\:hidden {
                    display: none !important;
                }
                body, main {
                    background: white !important;
                    padding: 0 !important;
                    margin: 0 !important;
                }
                .shadow-sm, .shadow-md, .shadow-xl, .shadow-2xs {
                    box-shadow: none !important;
                }
                .border, .border-b, .border-t {
                    border-color: #cbd5e1 !important;
                }
            }
        </style>
    </head>
    <body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100 selection:bg-rose-500 selection:text-white">
        <x-notifications position="top-center" />
        <x-dialog />

        <div x-data="{ sidebarOpen: false, showInstallModal: false }" class="min-h-screen flex flex-col lg:flex-row bg-slate-50 dark:bg-slate-950">
            <!-- Sidebar -->
            <aside 
                :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
                class="fixed inset-y-0 right-0 z-50 w-72 bg-slate-900/95 dark:bg-slate-950/95 backdrop-blur-xl text-white flex flex-col transition-transform duration-200 ease-in-out lg:static lg:inset-auto shrink-0 border-l border-slate-800/80 shadow-2xl print:hidden"
            >
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-4 bg-slate-950/90 border-b border-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-rose-600 to-red-500 flex items-center justify-center text-white shadow-md shadow-rose-600/30 shrink-0">
                            <x-icon name="heart" class="w-6 h-6" />
                        </div>
                        <div>
                            <h1 class="font-black text-sm text-white tracking-wide">کۆمەڵەی هیمۆفیلیا</h1>
                            <p class="text-[10px] text-rose-400 font-bold">لقی سلێمانی</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg">
                        <x-icon name="x-mark" class="w-6 h-6" />
                    </button>
                </div>

                <!-- Navigation Links Categorized -->
                <nav class="flex-1 p-3 space-y-4 overflow-y-auto custom-scrollbar">
                    
                    <!-- Group 1: Core Navigation -->
                    <div class="space-y-1">
                        <p class="px-3 text-[10px] font-black text-rose-400 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <x-icon name="squares-2x2" class="w-3.5 h-3.5 text-rose-400" />
                            <span>سەرەکی و نەخۆشەکان</span>
                        </p>

                        <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="chart-bar" class="w-4 h-4 shrink-0" />
                                <span>داشبۆردی سەرەکی</span>
                            </div>
                        </a>

                        <a href="{{ route('patients.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('patients.index') || request()->routeIs('patients.show') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="users" class="w-4 h-4 shrink-0" />
                                <span>تۆماری نەخۆشەکان</span>
                            </div>
                        </a>

                        @if(!auth()->user()?->isViewer())
                            <a href="{{ route('patients.create') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-[11px] font-bold transition-all duration-150 {{ request()->routeIs('patients.create') ? 'bg-rose-950/80 text-rose-300 border border-rose-800/60' : 'text-slate-400 hover:bg-slate-800/40 hover:text-slate-200' }} ms-3">
                                <x-icon name="user-plus" class="w-3.5 h-3.5 text-rose-500 shrink-0" />
                                <span>+ تۆمارکردنی نەخۆشی نوێ</span>
                            </a>
                        @endif
                    </div>

                    <!-- Group 2: Operations & Activities -->
                    <div class="space-y-1 pt-2 border-t border-slate-800/60">
                        <p class="px-3 text-[10px] font-black text-amber-400 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <x-icon name="sparkles" class="w-3.5 h-3.5 text-amber-400" />
                            <span>کار و هاوکارییەکان</span>
                        </p>

                        <a href="{{ route('activities.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('activities.*') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="sparkles" class="w-4 h-4 shrink-0 text-amber-400" />
                                <span>کار و چالاکیەکان</span>
                            </div>
                        </a>

                        <a href="{{ route('assistances.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('assistances.*') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="gift" class="w-4 h-4 shrink-0" />
                                <span>هاوکاری و یارمەتییەکان</span>
                            </div>
                        </a>

                        <a href="{{ route('memberships.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('memberships.*') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="identification" class="w-4 h-4 shrink-0" />
                                <span>ئەندامێتی و رسومات</span>
                            </div>
                        </a>
                    </div>

                    <!-- Group 3: Medical & Official Logs -->
                    <div class="space-y-1 pt-2 border-t border-slate-800/60">
                        <p class="px-3 text-[10px] font-black text-cyan-400 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <x-icon name="clipboard-document-check" class="w-3.5 h-3.5 text-cyan-400" />
                            <span>تەندروستی و بەڵگەنامەکان</span>
                        </p>

                        <a href="{{ route('medical.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('medical.*') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="clipboard-document-check" class="w-4 h-4 shrink-0 text-cyan-400" />
                                <span>چاودێری تەندروستی</span>
                            </div>
                        </a>

                        <a href="{{ route('contacts.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('contacts.*') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="phone" class="w-4 h-4 shrink-0" />
                                <span>تۆماری بەدواداچوون</span>
                            </div>
                        </a>

                        <a href="{{ route('mails.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('mails.*') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="document-text" class="w-4 h-4 shrink-0" />
                                <span>بەڵگەنامەکانی هاتوو/ڕۆشتوو</span>
                            </div>
                        </a>
                    </div>

                    <!-- Group 4: Analytics & App Info -->
                    <div class="space-y-1 pt-2 border-t border-slate-800/60">
                        <p class="px-3 text-[10px] font-black text-indigo-400 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <x-icon name="document-chart-bar" class="w-3.5 h-3.5 text-indigo-400" />
                            <span>ڕاپۆرتەکان و دەربارە</span>
                        </p>

                        <a href="{{ route('reports.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('reports.*') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="document-chart-bar" class="w-4 h-4 shrink-0 text-indigo-400" />
                                <span>ڕاپۆرتەکان و دەرکردنی داتا</span>
                            </div>
                        </a>

                        <a href="{{ route('about.developer') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('about.developer') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <x-icon name="code-bracket" class="w-4 h-4 shrink-0 text-purple-400" />
                                <span>دەربارەی گەشەپێدەر (iCode)</span>
                            </div>
                        </a>
                    </div>

                    <!-- Group 5: Admin Section -->
                    @if(auth()->user()?->isAdmin())
                        <div class="space-y-1 pt-2 border-t border-slate-800/60">
                            <p class="px-3 text-[10px] font-black text-emerald-400 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                <x-icon name="cog-6-tooth" class="w-3.5 h-3.5 text-emerald-400" />
                                <span>بەڕێوەبردن</span>
                            </p>

                            <a href="{{ route('users.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-md shadow-rose-600/25' : 'text-slate-300 hover:bg-slate-800/60 hover:text-white' }}">
                                <div class="flex items-center gap-3">
                                    <x-icon name="users" class="w-4 h-4 shrink-0 text-emerald-400" />
                                    <span>بەکارهێنەران، بەکئەپ و ئۆدیت لۆگ</span>
                                </div>
                            </a>
                        </div>
                    @endif
                </nav>

                <!-- User Footer Profile -->
                @auth
                    <div class="p-3 border-t border-slate-800/80 bg-slate-950 flex items-center justify-between">
                        <a href="{{ route('profile') }}" class="flex items-center gap-3 overflow-hidden hover:opacity-80 transition" title="پرۆفایلی من">
                            <div class="w-9 h-9 rounded-full bg-slate-800 text-rose-400 font-black flex items-center justify-center text-sm shrink-0 border border-slate-700/80 shadow-inner">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="truncate">
                                <p class="text-xs font-extrabold text-white truncate">{{ auth()->user()->name }}</p>
                                <span class="inline-block text-[10px] px-2 py-0.5 rounded-md bg-rose-500/10 text-rose-300 border border-rose-500/20 font-bold mt-0.5">
                                    {{ auth()->user()->role?->label() }}
                                </span>
                            </div>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 transition-colors rounded-lg hover:bg-slate-900" title="دەرچوون">
                                <x-icon name="arrow-left-on-rectangle" class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                @endauth
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                <!-- Top Navbar -->
                <header class="h-16 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-xs print:hidden">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <x-icon name="bars-3" class="w-6 h-6" />
                        </button>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">
                            {{ $header ?? 'سیستەمی بەڕێوەبردنی نەخۆشانی هیمۆفیلیا' }}
                        </h2>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Install App / PWA Button -->
                        <button id="pwa-install-btn" @click="if (handleInstallClick()) { showInstallModal = true; }" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-rose-600 to-red-600 text-white font-black text-xs shadow-md shadow-rose-600/30 hover:from-rose-700 hover:to-red-700 transition flex items-center gap-2 cursor-pointer">
                            <x-icon name="arrow-down-tray" class="w-4 h-4 animate-bounce" />
                            <span>دامەزراندنی ئەپ (شۆرکەت)</span>
                        </button>

                        <span class="hidden sm:inline-flex items-center gap-2 text-xs font-bold px-3.5 py-1.5 rounded-full bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200/80 dark:border-rose-800/60 shadow-2xs">
                            <span class="w-2 h-2 rounded-full bg-rose-600 animate-pulse"></span>
                            کۆمەڵەی هیمۆفیلیای کوردستان - سلێمانی
                        </span>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>

            <!-- Beautiful Custom PWA Instructions Modal -->
            <div x-show="showInstallModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 print:hidden" style="display: none;">
                <div @click.away="showInstallModal = false" class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl space-y-5 text-right">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-black">
                                <x-icon name="arrow-down-tray" class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="font-black text-slate-900 dark:text-white text-base">دامەزراندنی سیستەم وەک ئەپ</h3>
                                <p class="text-[11px] text-slate-400">دەستگەیشتنی خێرا لەسەر مۆبایل و کامپیوتەر</p>
                            </div>
                        </div>
                        <button @click="showInstallModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-xl">
                            <x-icon name="x-mark" class="w-6 h-6" />
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 flex items-start gap-3">
                            <span class="w-7 h-7 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-600 font-extrabold text-xs flex items-center justify-center shrink-0">١</span>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 leading-relaxed pt-1">
                                لە سەرەوەی براوسەرەکەت (Chrome/Edge/Safari) کلیک لە ۳ خاڵەکە یان دوگمەی Menu بکە.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 flex items-start gap-3">
                            <span class="w-7 h-7 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-600 font-extrabold text-xs flex items-center justify-center shrink-0">٢</span>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 leading-relaxed pt-1">
                                هەڵبژاردنی <strong>"Add to Home screen"</strong> (زیادکردن بۆ شاشەی سەرەکی) یان <strong>"Install app"</strong> بژێرە.
                            </p>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <x-button primary label="تێگەیشتم" @click="showInstallModal = false" class="w-full font-black py-2.5 shadow-md shadow-rose-600/25" />
                    </div>
                </div>
            </div>
        </div>

        @livewireScripts
        @wireUiScripts

        <script>
            function handleInstallClick() {
                if (window.deferredPrompt) {
                    window.deferredPrompt.prompt();
                    window.deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('App Installed Successfully!');
                        }
                        window.deferredPrompt = null;
                    });
                    return false;
                }
                return true;
            }

            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW reg error: ', err));
                });
            }
        </script>
    </body>
</html>
