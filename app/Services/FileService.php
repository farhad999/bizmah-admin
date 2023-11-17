<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService

{

  public function upload($request, $key = 'files')
  {
    return $this->handleUpload($request, 'single', $key);
  }

  public function uploadMulti($request, $key = 'files')
  {
    return $this->handleUpload($request, 'multi', $key);
  }

  private function handleUpload($request, $mode, $key = 'files')
  {

    $uploads = [];

    $files = [];

    if ($request->hasFile($key)) {

      $files = $request->file($key);

      if (gettype($files) !== 'array') {
        $files = [$files];
      }

      foreach ($files as $file) {

        $size = $file->getSize();
        $filename = Str::random(30);
        $mimeType = $file->getClientMimeType();
        $extension = $file->getClientOriginalExtension();

        $disk = 'public';
        $filename = $filename . '.' . $extension;
        $filePath = '/uploads/' . $filename;
        Storage::disk($disk)->put($filename, file_get_contents($file));

        $uploads[] = $filename;

      }

    }

    if (empty($files)) {
      return null;
    }

    if (!empty($mode == 'single')) {
      return $uploads[0];
    }

    return $uploads;
  }

}
