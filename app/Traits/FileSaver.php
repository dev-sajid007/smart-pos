<?php


namespace App\Traits;
use Image;

trait FileSaver
{
    // <!-- upload file -->
    public function upload_file($file, $model, $fieldName, $basePath)
    {

        // <!-- upload file -->
        if ($file) {

            // <!-- delete file if exist -->
            if (file_exists($model->$fieldName)) {
                unlink($model->$fieldName);
            }

            // <!-- create unique file name -->
            $newFileName   = time() . '.' . $file->getClientOriginalExtension();
            $subdomain = "./";

            // <!-- create upload directory -->
            $directory   = $subdomain . '_uploads/' . $basePath . '/' . date('Y') . '/';

            // <!-- create store file to directory -->
            $file->move($directory, $newFileName);

            // <!-- update file name to database -->
            $model->$fieldName = $directory . $newFileName;
            $model->save();
        }
    }


    public function uploadFileWithResize($file, $model, $database_field_name, $basePath)
    {
        if ($file) {

            try {
                $basePath = 'uploads/' . $basePath;

                $image_name     = time() . '.' . $file->getClientOriginalExtension();

                if (file_exists($basePath . '/' . $model->image) && $model->image != '') {
                    unlink($basePath . '/' . $model->image);
                }

                if (!is_dir($basePath)) {
                    \File::makeDirectory($basePath, 493, true);
                }

                $resize_image = Image::make($file->getRealPath());
                $resize_image->resize(250, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($basePath . '/' . $image_name);

                $model->update([$database_field_name => ($basePath . '/' . $image_name)]);
            } catch (\Exception $ex) {}
        }
    }

    public function uploadMultipleFileWithResize($files, $model, $database_field_name, $basePath)
    {
        $allFiles = [];
        $basePath = 'uploads/' . $basePath;
        if ($files) {
            try {
                foreach($files as $key => $file)
                {
                    $image_name     = time() . $key . '.' . $file->getClientOriginalExtension();

                    if (file_exists($basePath . '/' . $model->images) && $model->images != '') {
                        unlink($basePath . '/' . $model->images);
                    }

                    if (!is_dir($basePath)) {
                        \File::makeDirectory($basePath, 493, true);
                    }

                    $resize_image = Image::make($file->getRealPath());
                    $resize_image->resize(250, 300, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($basePath . '/' . $image_name);

                    array_push($allFiles, ($basePath . '/' . $image_name));
                }
                
                $model->update([$database_field_name => json_encode($allFiles)]);
            } catch (\Exception $ex) {}
        }
    }
}
