<?php

namespace Modules\Core\Utilities;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileManager
{
    public static function storePublicly(
        UploadedFile $file,
        string $path,
        string $disk = 'public',
    ): string|false {
        return $file->storePublicly($path, $disk);
    }

    public static function storeAs(
        UploadedFile $file,
        string $path,
        string $name,
        string $disk = 'public',
    ): string|false {
        return $file->storeAs($path, $name, $disk);
    }

    public static function deleteIfExists(string $path, string $disk = 'public'): bool
    {
        if (! Storage::disk($disk)->exists($path)) {
            return false;
        }

        return Storage::disk($disk)->delete($path);
    }
}
