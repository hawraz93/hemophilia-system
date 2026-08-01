<?php

namespace App\Enums;

enum PartyAffiliation: string
{
    case Green = 'green';
    case Yellow = 'yellow';
    case Orange = 'orange';
    case Brown = 'brown';
    case Light = 'light';
    case White = 'white';
    case Grey = 'grey';

    public function label(): string
    {
        return match ($this) {
            self::Green => 'سەوز',
            self::Yellow => 'زەرد',
            self::Orange => 'پرتەقاڵی',
            self::Brown => 'قاوەیی',
            self::Light => 'ڕووناکی',
            self::White => 'سپی',
            self::Grey => 'خۆلەمێشی',
        };
    }

    public function hexColor(): string
    {
        return match ($this) {
            self::Green => '#22c55e',
            self::Yellow => '#eab308',
            self::Orange => '#f97316',
            self::Brown => '#78350f',
            self::Light => '#06b6d4',
            self::White => '#ffffff',
            self::Grey => '#6b7280',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Green => 'bg-emerald-500 text-white border-emerald-600',
            self::Yellow => 'bg-amber-400 text-slate-900 border-amber-500 font-extrabold',
            self::Orange => 'bg-orange-500 text-white border-orange-600',
            self::Brown => 'bg-amber-900 text-white border-amber-950',
            self::Light => 'bg-cyan-400 text-slate-950 border-cyan-500 font-extrabold',
            self::White => 'bg-white text-slate-900 border-slate-300 shadow-2xs font-extrabold',
            self::Grey => 'bg-slate-500 text-white border-slate-600',
        };
    }
}
