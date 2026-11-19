<?php

namespace Modules\Core\DTO;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

abstract class BaseDTO
{
    public function toArray(): array
    {
        $array = get_object_vars($this);
        unset($array['id']);

        return $array;
    }

    public function filterNull(array $skip = []): static
    {
        foreach (get_object_vars($this) as $attribute => $value) {
            if ($value === null && ! in_array($attribute, $skip, true)) {
                unset($this->{$attribute});
            }
        }

        return $this;
    }

    abstract public static function fromRequest(FormRequest $request): static;

    public static function handleFileStoring(
        UploadedFile|string|null $file,
        string $path,
        ?string $name = null,
        string $disk = 'public',
    ): ?string {
        if ($file === null) {
            return null;
        }

        if (is_string($file)) {
            return $file;
        }

        if ($name !== null) {
            $stored = $file->storeAs($path, $name, $disk);
        } else {
            $stored = $file->store($path, $disk);
        }

        return $stored === false ? null : $stored;
    }

    public static function prepareRequestArray(?array $data): ?array
    {
        if (! is_array($data)) {
            return null;
        }

        if ($data === []) {
            return [];
        }

        if (in_array(-1, $data, true)) {
            return [];
        }

        return $data;
    }
}