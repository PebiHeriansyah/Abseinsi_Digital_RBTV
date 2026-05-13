<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

/**
 * StorageUrlHelper menyediakan helper global untuk mendapatkan URL file
 * yang bekerja baik di lokal (disk public) maupun production (disk supabase).
 */
class StorageUrlHelper
{
    /**
     * Mendapatkan public URL dari path file yang tersimpan.
     * Otomatis menggunakan disk yang dikonfigurasi via FILESYSTEM_DISK.
     *
     * @param string|null $path
     * @return string|null
     */
    public static function url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $disk = config('filesystems.default', 'public');
        return Storage::disk($disk)->url($path);
    }
}
