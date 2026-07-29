<div class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-8 border border-slate-200 dark:border-slate-700">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-950/60 text-red-600 mb-4">
            <x-icon name="heart" class="w-10 h-10" />
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">
            کۆمەڵەی هیمۆفیلیای کوردستان
        </h2>
        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mt-1">
            لقی سلێمانی — سیستەمی بەڕێوەبردنی نەخۆشان
        </p>
    </div>

    <form wire:submit="login" class="space-y-6">
        <x-input
            wire:model="email_or_username"
            label="ئیمەیڵ یاخود ناوی بەکارهێنەر"
            placeholder="ئیمەیڵ یان username بنووسە"
            icon="user"
        />

        <x-password
            wire:model="password"
            label="وشەی نهێنی (Password)"
            placeholder="••••••••"
        />

        <div class="flex items-center justify-between">
            <x-checkbox wire:model="remember" label="لەبیرم مەکە" />
        </div>

        <div>
            <x-button type="submit" primary class="w-full justify-center py-2.5 text-base font-bold">
                چوونەژوورەوە
            </x-button>
        </div>
    </form>

    <div class="mt-8 border-t border-slate-200 dark:border-slate-700 pt-4 text-xs text-center text-slate-500">
        <p class="font-bold mb-1">هەژمارەکانی تاقیکردنەوە:</p>
        <p>ئەدمین: <code class="bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">admin</code> | وشەی نهێنی: <code class="bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">password</code></p>
        <p class="mt-0.5">کارمەند: <code class="bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">staff</code> | وشەی نهێنی: <code class="bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">password</code></p>
    </div>
</div>
