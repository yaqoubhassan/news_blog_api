<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @author Ibrahim Samad <ibrahim@walulel.com>
 */
trait UploadTrait
{
    public function uploadSingle(UploadedFile $uploadedFile, $folder = null)
    {
        $options = [
            'visibility' => 'public'
        ];
        $file = Storage::put($folder, $uploadedFile, $options);
        return $file;
    }

    /**
     * @param string $disk
     * @param string $filename
     *
     * @return void
     */
    public function deleteFile($filename = null)
    {
        Storage::delete($filename);
    }
}
