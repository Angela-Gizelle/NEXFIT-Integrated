<?php

namespace App\Support;

class Initials
{
    /**
     * Derive up to 2 uppercase initials from a full name string.
     * "Juan Dela Cruz" -> "JD", "Maria" -> "M".
     */
    public static function of(?string $fullName): string
    {
        $fullName = trim((string) $fullName);

        if ($fullName === '') {
            return '?';
        }

        return collect(explode(' ', $fullName))
            ->filter()
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->join('');
    }
}
