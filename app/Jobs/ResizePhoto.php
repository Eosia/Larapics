<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Storage, Str;
use App\Models\{ Source, Photo };

class ResizePhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Source $source;
    protected Photo $photo;
    protected string $ext;

    /**
     * Create a new job instance.
     */
    public function __construct(Source $source, Photo $photo, string $ext)
    {
        $this->source = $source;
        $this->photo = $photo;
        $this->ext = $ext;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $manager = new ImageManager(new Driver());
            $originalPath = $this->source->path;

            // Check if the original file exists
            if (!Storage::exists($originalPath)) {
                throw new \Exception("Original file does not exist.");
            }

            $image = $manager->read(Storage::get($originalPath));
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            $this->resizeAndStoreImage($image, 350, 233, 'thumbnails'); // Thumbnail

            // Resize and encode additional sizes
            for ($i = 2; $i <= 6; $i++) {
                $this->resizeAndStoreImage($image, round($originalWidth / $i), round($originalHeight / $i));
            }

            $this->photo->active = true;
            $this->photo->save();

        } catch (\Exception $e) {
            // Handle exception (log, notify, etc.)
            // For now, just rethrowing
            throw $e;
        }
    }

    /**
     * Resize and store the image.
     */
    private function resizeAndStoreImage($image, $width, $height, $subfolder = ''): void
    {
        $resizedImage = $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatioCrop();
            $constraint->upsize();
        });

        $encodedImage = $this->encodeImage($resizedImage, $this->ext);
        $newFilename = Str::uuid() . '.' . $this->ext;
        $path = 'photos/' . $this->photo->album->id . '/' . $subfolder . '/' . $newFilename;

        Storage::put($path, (string) $encodedImage);

        if ($subfolder === 'thumbnails') {
            $this->photo->thumbnail_path = $path;
            $this->photo->thumbnail_url = Storage::url($path);
        } else {
            $this->photo->sources()->create([
                'path' => $path,
                'url' => Storage::url($path),
                'size' => Storage::size($path),
                'width' => $width,
                'height' => $height,
            ]);
        }
    }

    /**
     * Encode the given image based on the extension.
     */
    private function encodeImage($image, $ext)
    {
        return match ($ext) {
            'png' => $image->toPng(),
            'gif' => $image->toGif(),
            'bmp' => $image->toBitmap(),
            'webp' => $image->toWebp(),
            'avif' => $image->toAvif(),
            default => $image->toJpeg(),
        };
    }
}
