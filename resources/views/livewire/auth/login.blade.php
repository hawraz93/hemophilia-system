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

    <form wire:submit="login" method="POST" class="space-y-6">
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



    <!-- iCode Group Developer Branding Footer -->
    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/60 text-center space-y-2">
        <div class="flex items-center justify-center gap-2">
            <span class="w-6 h-6 rounded-lg bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-black text-[10px] flex items-center justify-center shadow-xs">IC</span>
            <p class="text-xs font-black text-slate-700 dark:text-slate-200">
                پاڵپشتی و گەشەپێدان لەلایەن <a href="https://icodegroup.net/" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">ئایکۆد گروپ (iCode Group)</a>
            </p>
        </div>

        <div class="flex items-center justify-center gap-3 pt-1 text-slate-400 dark:text-slate-500">
            <a href="https://icodegroup.net/" target="_blank" class="hover:text-indigo-600 transition" title="ماڵپەڕی فەرمی icodegroup.net">
                <x-icon name="globe-alt" class="w-4 h-4" />
            </a>
            <a href="https://wa.me/9647700941717" target="_blank" class="hover:text-emerald-500 transition" title="واتسئەپ: +9647700941717">
                <x-icon name="chat-bubble-left-right" class="w-4 h-4" />
            </a>
            <a href="https://facebook.com/icodegroup" target="_blank" class="hover:text-blue-600 transition" title="فەیسبووک: /icodegroup">
                <x-icon name="link" class="w-4 h-4" />
            </a>
            <a href="https://t.me/icodegroup" target="_blank" class="hover:text-cyan-500 transition" title="تێلێگرام: @icodegroup">
                <x-icon name="paper-airplane" class="w-4 h-4" />
            </a>
        </div>
    </div>
</div>
