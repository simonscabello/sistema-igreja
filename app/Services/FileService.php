<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class FileService
{
    public function uploadFile(UploadedFile $file, Model $related, string $collection = 'default')
    {
        $disk = config('filesystems.default');
        $isImage = str_starts_with($file->getMimeType(), 'image/');
        $path = $isImage ? $this->processImage($file, $disk) : $file->store('uploads', $disk);

        $fileModel = File::create([
            'original_name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'path' => $path,
            'disk' => $disk,
            'url' => $this->generateUrlRaw($disk, $path),
        ]);

        $related->files($collection)->attach($fileModel->id, ['collection' => $collection]);
        return $fileModel;
    }

    public function deleteFile(File $file)
    {
        Storage::disk($file->disk)->delete($file->path);
        $file->delete();
    }

    public function listFilesFor(Model $related, string $collection = 'default')
    {
        return $related->files($collection)->get();
    }

    public function generateUrl(File $file)
    {
        return $this->generateUrlRaw($file->disk, $file->path);
    }

    private function generateUrlRaw($disk, $path)
    {
        if ($disk === 'public') {
            return asset('storage/' . $path);
        }
        if ($disk === 's3') {
            return Storage::disk('s3')->url($path);
        }
        return '';
    }

    private function processImage(UploadedFile $file, $disk)
    {
        $image = Image::make($file->getRealPath());
        if ($image->width() > 1920) {
            $image->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $image->encode($file->getClientOriginalExtension(), 80);
        $filename = uniqid('img_') . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/' . $filename;
        Storage::disk($disk)->put($path, (string) $image);
        return $path;
    }
} 