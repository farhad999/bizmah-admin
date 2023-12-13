<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService

{

  public function upload($request, $key = 'files')
  {
    return $this->processUpload($request, 'single', $key);
  }

  //it will upload file directly
  //no need to retrieve it from request object
  //file will be passed
  public function fileUpload($file)
  {
    if (empty($file)) {
      return null;
    }
    return $this->handleUpload($file);
  }

  public function uploadMulti($request, $key = 'files')
  {
    return $this->processUpload($request, 'multi', $key);
  }

  private function processUpload($request, $mode, $key = 'files')
  {
    $uploads = [];

    $files = [];

    if ($request->hasFile($key)) {

      $files = $request->file($key);

      if (gettype($files) !== 'array') {
        $files = [$files];
      }

      foreach ($files as $file) {
        $uploads[] = $this->handleUpload($file);
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

  private function handleUpload($file)
  {

    $size = $file->getSize();
    $filename = Str::random(30);
    $mimeType = $file->getClientMimeType();
    $extension = $file->getClientOriginalExtension();

    $disk = 'public';
    $filename = $filename . '.' . $extension;
    Storage::disk($disk)->put($filename, file_get_contents($file));

    return $filename;

  }

  function delete($path)
  {
    if (!empty($path)) {
      Storage::delete($path);
    }
  }


}
