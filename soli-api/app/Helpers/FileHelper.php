<?php

namespace App\Helpers;

class FileHelper
{
    public function saveMedia($file, string $uid = '__', string $folderType = 'images')
    {
        $folder = 'public/' . $uid . '/media/' . $folderType;
        $filename = $file->hashName();
        $url = '/storage/' . $uid . '/media/' . $folderType . '/' . $filename;
        $file->store($folder);
        return $url;
    }
}
