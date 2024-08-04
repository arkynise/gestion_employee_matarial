<?php
// src/Service/FileUploader.php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    
    public function upload(UploadedFile $file,string $path,string $fileName): void
    {
        
            $originalFilename = $path . $fileName;
            $targetDirectory = "../public/".$path;
            try {
                $file->move(
                    $targetDirectory,
                    $originalFilename
                );
            } catch (FileException $e) {
            }

        
    }
}
