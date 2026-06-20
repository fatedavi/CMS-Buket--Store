<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageHelper
{
    public static function compress(string $path, int $maxSizeKB = 4096, int $maxDimension = 1920, string $disk = 'public'): void
    {
        $fullPath = Storage::disk($disk)->path($path);

        if (! file_exists($fullPath)) {
            return;
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        if (in_array($ext, ['gif', 'svg'])) {
            return;
        }

        $manager = new ImageManager(new Driver);
        $image = $manager->decodePath($fullPath);

        if ($image->width() > $maxDimension || $image->height() > $maxDimension) {
            $image->scale(width: $maxDimension, height: $maxDimension);
        }

        foreach ([85, 70, 55, 40, 25] as $quality) {
            $encoded = $image->encodeUsingFileExtension($ext, quality: $quality);
            if (strlen((string) $encoded) <= $maxSizeKB * 1024) {
                $encoded->save($fullPath);

                return;
            }
        }

        $encoded = $image->encodeUsingFileExtension($ext, quality: 25);
        $encoded->save($fullPath);
    }
}
