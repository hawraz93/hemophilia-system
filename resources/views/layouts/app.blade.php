<!DOCTYPE html>
<html lang="ckb" dir="rtl" class="h-full bg-slate-100 dark:bg-slate-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'سیستەمی بەڕێوەبردنی نەخۆشانی هیمۆفیلیا' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="h-full font-sans antialiased text-slate-800 dark:text-slate-100">
        <x-notifications position="top-center" />
        <x-dialog />

        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col lg:flex-row bg-slate-100 dark:bg-slate-900">
            <!-- Sidebar -->
            <aside 
                :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
                class="fixed inset-y-0 right-0 z-50 w-64 bg-slate-900 text-white flex flex-col transition-transform duration-200 ease-in-out lg:static lg:inset-auto shrink-0 shadow-2xl"
            >
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-4 bg-slate-950 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-red-600 flex items-center justify-center text-white shadow">
                            <x-icon name="heart" class="w-6 h-6" />
                        </div>
                        <div>
                            <h1 class="font-extrabold text-sm text-white">کۆمەڵەی هیمۆفیلیا</h1>
                            <p class="text-[10px] text-slate-400">لقی سلێمانی</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                        <x-icon name="x-mark" class="w-6 h-6" />
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-red-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-icon name="chart-bar" class="w-5 h-5" />
                        <span>داشبۆردی سەرەکی</span>
                    </a>

                    <a href="{{ route('patients.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('patients.*') ? 'bg-red-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-icon name="users" class="w-5 h-5" />
                        <span>تۆماری نەخۆشەکان</span>
                    </a>

                    <a href="{{ route('assistances.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('assistances.*') ? 'bg-red-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-icon name="gift" class="w-5 h-5" />
                        <span>هاوکاری و یارمەتییەکان</span>
                    </a>

                    <a href="{{ route('memberships.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('memberships.*') ? 'bg-red-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-icon name="identification" class="w-5 h-5" />
                        <span>ئەندامێتی و رسومات</span>
                    </a>

                    <a href="{{ route('contacts.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('contacts.*') ? 'bg-red-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-icon name="phone" class="w-5 h-5" />
                        <span>تۆماری بەدواداچوون</span>
                    </a>

                    <a href="{{ route('medical.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('medical.*') ? 'bg-red-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-icon name="clipboard-document-check" class="w-5 h-5" />
                        <span>چاودێری تەندروستی</span>
                    </a>

                    <a href="{{ route('mails.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('mails.*') ? 'bg-red-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-icon name="document-text" class="w-5 h-5" />
                        <span>بەڵگەنامەکانی هاتوو/ڕۆشتوو</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.*') ? 'bg-red-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-icon name="document-chart-bar" class="w-5 h-5" />
                        <span>ڕاپۆرتەکان</span>
                    </a>

                    @if(auth()->user()?->isAdmin())
                        <div class="pt-4 mt-4 border-t border-slate-800">
                            <p class="px-3 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">بەڕێوەبردن</p>
                            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('users.*') ? 'bg-red-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <x-icon name="cog-6-tooth" class="w-5 h-5" />
                                <span>بەکارهێنەران و لۆگ</span>
                            </a>
                        </div>
                    @endif
                </nav>

                <!-- User Footer Profile -->
                @auth
                    <div class="p-3 border-t border-slate-800 bg-slate-950 flex items-center justify-between">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-9 h-9 rounded-full bg-slate-800 text-slate-300 font-bold flex items-center justify-center text-sm shrink-0 border border-slate-700">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="truncate">
                                <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                                <span class="inline-block text-[10px] px-1.5 py-0.5 rounded bg-slate-800 text-slate-400 font-medium">
                                    {{ auth()->user()->role->label() }}
                                </span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-400 transition" title="دەرچوون">
                                <x-icon name="arrow-left-on-rectangle" class="w-5 h-5" />
                            </button>
                        </form>
                    </div>
                @endauth
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                <!-- Top Navbar -->
                <header class="h-16 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-sm">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                            <x-icon name="bars-3" class="w-6 h-6" />
                        </button>
                        <h2 class="text-lg font-extrabold text-slate-800 dark:text-white">
                            {{ $header ?? 'سیستەمی بەڕێوەبردنی نەخۆشانی هیمۆفیلیا' }}
                        </h2>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full bg-red-50 text-red-700 dark:bg-red-950/60 dark:text-red-300 border border-red-200 dark:border-red-800">
                            <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                            کۆمەڵەی هیمۆفیلیای کوردستان - سلێمانی
                        </span>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
        @wireUiScripts
    </body>
</html>
