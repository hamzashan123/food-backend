<?php

namespace App\Services;

use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\File;

class ImageService
{
    use ImageUploadTrait;

    public function storeProductImages($images, $product, $i = 1)
    {
        foreach ($images as $image) {
            $product->media()->create([
                'file_name' => $this->uploadImages($product->name, $image, $i, 'products', 500, NULL),
                'file_size' => $image->getSize(),
                'file_type' => $image->getMimeType(),
                'file_status' => true,
                'file_sort' => $i
            ]);

            $i++;
        }
    }

    public function storeDishImages($images, $dish, $i = 1)
    {
        foreach ($images as $image) {
            $dish->media()->create([
                'file_name' => $this->uploadImages($dish->name, $image, $i, 'dishes', 500, NULL),
                'file_size' => $image->getSize(),
                'file_type' => $image->getMimeType(),
                'file_status' => true,
                'file_sort' => $i
            ]);

            $i++;
        }
    }

    public function storeMealImages($images, $meal, $i = 1)
    {
        foreach ($images as $image) {
            $meal->media()->create([
                'file_name' => $this->uploadImages($meal->name, $image, $i, 'meals', 500, NULL),
                'file_size' => $image->getSize(),
                'file_type' => $image->getMimeType(),
                'file_status' => true,
                'file_sort' => $i
            ]);

            $i++;
        }
    }

    public function storeMealPlanImages($images, $mealplan, $i = 1)
    {
        foreach ($images as $image) {
            $mealplan->media()->create([
                'file_name' => $this->uploadImages($mealplan->name, $image, $i, 'mealplans', 500, NULL),
                'file_size' => $image->getSize(),
                'file_type' => $image->getMimeType(),
                'file_status' => true,
                'file_sort' => $i
            ]);

            $i++;
        }
    }

    public function storeUserImages($fileName, $image): string
    {
        return $this->uploadImage(
            $fileName,
            $image,
            'users',
            300,
            NULL
        );
    }

    public function unlinkImage($image, $folderName)
    {
        if (File::exists('storage/images/'. $folderName .'/' . $image)) {
            unlink('storage/images/'. $folderName .'/' . $image);
        }
    }
}
