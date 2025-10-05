<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageService
{
    public function __construct(
        private readonly ImageManager $imageManager
    )
    {
    }

    public function upload(UploadedFile $imageFile, string $folderName): string
    {
        $fileName = uniqid(rand() . '_');
        $extension = $imageFile->extension();
        $fileNameToStore = $fileName . '.' . $extension;
        $resizedImage = $this->imageManager->read($imageFile->getRealPath())->resize(1920, 1080)->encode();
        Storage::put("public/{$folderName}/{$fileNameToStore}", $resizedImage);

        return $fileNameToStore;
    }
}
