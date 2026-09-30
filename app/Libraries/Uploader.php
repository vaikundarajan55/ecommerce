<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;

/** Saves images into public/uploads/<folder>/ and returns the stored file name. */
class Uploader
{
    public static function image(?UploadedFile $file, string $folder, ?string $old = null): ?string
    {
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return $old;
        }
        $dir = FCPATH . 'uploads/' . $folder;
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $name = $file->getRandomName();
        $file->move($dir, $name);
        if ($old && is_file($dir . '/' . $old)) {
            @unlink($dir . '/' . $old);
        }
        return $name;
    }

    public static function delete(?string $file, string $folder): void
    {
        if ($file && is_file(FCPATH . 'uploads/' . $folder . '/' . $file)) {
            @unlink(FCPATH . 'uploads/' . $folder . '/' . $file);
        }
    }
}
