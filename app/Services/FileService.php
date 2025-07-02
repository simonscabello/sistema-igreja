<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Illuminate\Support\Collection;

class FileService
{
    public function uploadFile(UploadedFile $file, Model $related, string $collection = 'default'): File
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

    public function deleteFile(File $file): void
    {
        Storage::disk($file->disk)->delete($file->path);
        $file->delete();
    }

    public function listFilesFor(Model $related, string $collection = 'default'): Collection
    {
        return $related->files($collection)->get();
    }

    public function generateUrl(File $file): string
    {
        return $this->generateUrlRaw($file->disk, $file->path);
    }

    private function generateUrlRaw($disk, $path): string
    {
        if ($disk === 'public') {
            return asset('storage/' . $path);
        }
        if ($disk === 's3') {
            return Storage::disk('s3')->url($path);
        }
        return '';
    }

    private function processImage(UploadedFile $file, $disk): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        // Redimensionar para no máximo 500x500px para fotos de perfil
        if ($image->width() > 500 || $image->height() > 500) {
            $image->resize(500, 500);
        }

        $filename = uniqid('img_') . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/' . $filename;

        // Usar encoder específico baseado na extensão
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $imageData = $image->toJpeg(80)->toString();
        } else {
            $imageData = $image->toPng()->toString();
        }

        // Usar Storage facade para garantir que o diretório seja criado
        Storage::disk($disk)->put($path, $imageData);

        return $path;
    }
}
