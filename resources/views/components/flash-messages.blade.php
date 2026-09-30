@if (session()->has('message'))
    <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 text-xs font-bold border border-emerald-200 dark:border-emerald-900">
        {{ session('message') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="p-3.5 rounded-xl bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 text-xs font-bold border border-rose-200 dark:border-rose-900">
        {{ session('error') }}
    </div>
@endif
