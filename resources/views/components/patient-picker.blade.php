@props([
    'label' => 'نەخۆش',
    'selected' => null,
    'results' => collect(),
    'term' => '',
])

{{-- Search-as-you-type patient picker; pair with the SearchesPatients Livewire trait --}}
<div class="space-y-1.5">
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ $label }}</label>

    @if($selected)
        <div class="flex items-center justify-between gap-2 p-2.5 rounded-xl border border-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 dark:border-emerald-800">
            <div>
                <span class="text-sm font-extrabold text-slate-900 dark:text-white block">{{ $selected->full_name }}</span>
                <span class="text-[11px] font-mono text-slate-500">{{ $selected->patient_code }} | {{ $selected->phone }}</span>
            </div>
            <button type="button" wire:click="clearPatient" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-white dark:hover:bg-slate-800 transition" title="گۆڕین">
                <x-icon name="x-mark" class="w-4 h-4" />
            </button>
        </div>
    @else
        <x-input wire:model.live.debounce.500ms="patientLookup" placeholder="ناو، کۆد، ژمارەی ئەندامێتی یان مۆبایل بنووسە..." icon="magnifying-glass" />

        @if($results->isNotEmpty())
            <div class="max-h-56 overflow-y-auto rounded-xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($results as $p)
                    <button type="button" wire:click="selectPatient({{ $p->id }})" class="w-full text-right px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition">
                        <span class="text-sm font-bold text-slate-900 dark:text-white block">{{ $p->full_name }}</span>
                        <span class="text-[11px] font-mono text-slate-500">{{ $p->patient_code }} | {{ $p->phone }}</span>
                    </button>
                @endforeach
            </div>
        @elseif(mb_strlen(trim($term)) >= 2)
            <p class="text-xs text-slate-400">هیچ نەخۆشێک نەدۆزرایەوە.</p>
        @endif
    @endif

    @error('patient_id') <p class="text-xs text-rose-600">{{ $message }}</p> @enderror
</div>
