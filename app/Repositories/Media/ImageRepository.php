<?php
/**
 * Created by PhpStorm.
 * User: Samrat
 * Date: 12/4/2017
 * Time: 2:00 AM
 */

namespace App\Repositories\Media;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Image;
use Auth;


class ImageRepository
{
    /**
     * @param $form_data
     * @return mixed
     */
    public function upload($form_data)
    {
        $result = $this->storeFile($form_data);

        if ($result['error']) {
            return Response::json([
                'error' => true,
                'message' => $result['message'],
                'code' => $result['code']
            ], $result['code']);
        }

        return Response::json([
            'error' => false,
            'code' => 200,
            'image_id' => $result['image']->id
        ], 200);
    }

    /**
     * @param $form_data
     * @return array
     */
    public function storeFile($form_data)
    {
        $photo = $form_data['file'];
        $extension = strtolower($photo->getClientOriginalExtension());

        $validXtra = $extension === 'glb' || $extension === 'gitf';
        $validator = Validator::make($form_data, Image::$rules, Image::$messages);

        if (!$validXtra && $validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->messages()->first(),
                'code' => 400
            ];
        }

        $originalName = $photo->getClientOriginalName();
        $originalNameWithoutExt = substr($originalName, 0, strlen($originalName) - strlen($extension) - 1);

        $filename = $this->sanitize($originalNameWithoutExt);
        $allowed_filename = $this->createUniqueFilename($filename, $extension);

        $uploadSuccess1 = $this->original($photo, $allowed_filename);
        $shouldGenerateIcon = !in_array($extension, ['glb', 'gitf', 'pdf'], true);
        $uploadSuccess2 = $shouldGenerateIcon ? $this->icon($photo, $allowed_filename) : true;

        if (!$uploadSuccess1 || !$uploadSuccess2) {
            return [
                'error' => true,
                'message' => 'Server error while uploading',
                'code' => 500
            ];
        }

        $date = date('Y-m');
        $folder_name = str_replace(':', '', $date);

        $sessionImage = new Image;
        $sessionImage->filename = $allowed_filename;
        $sessionImage->original_name = $originalName;
        $sessionImage->file_type = $photo->getMimeType();
        $sessionImage->file_size = $photo->getSize();
        $sessionImage->file_extension = $photo->getClientOriginalExtension();
        $sessionImage->full_size_directory = 'storage/uploads/fullsize/' . $folder_name . '/' . $allowed_filename;
        $sessionImage->icon_size_directory = $extension === 'pdf'
            ? 'frontend/img/pdf-page.png'
            : 'storage/uploads/iconsize/' . $folder_name . '/' . $allowed_filename;
        $sessionImage->status = 1;
        $sessionImage->user_id = Auth::user()->id;
        $sessionImage->save();

        return [
            'error' => false,
            'image' => $sessionImage
        ];
    }

    /**
     * @param $filename
     * @param $extension
     * @return string
     */
    public function createUniqueFilename($filename, $extension)
    {
        $full_size_dir = $this->upload_path(true);
        $full_size_dir = rtrim($full_size_dir, '/');

        $candidate = $filename . '.' . $extension;
        $full_image_path = $full_size_dir . '/' . $candidate;

        while (File::exists($full_image_path)) {
            $imageToken = substr(sha1(mt_rand()), 0, 5);
            $candidate = $filename . '-' . $imageToken . '.' . $extension;
            $full_image_path = $full_size_dir . '/' . $candidate;
        }

        return $candidate;
    }

    /**
     * Optimize Original Image
     * @param $photo
     * @param $filename
     * @return \Intervention\Image\Image
     */
    public function original($photo, $filename)
    {
        /*$manager = new ImageManager();
        $image = $manager->make($photo)->save($this->upload_path(TRUE) . '/' . $filename);*/
        
        $imag   = $this->upload_path(true).'/'.$filename;
        
        $image = copy($photo,$imag) ? $imag : null;

        return $image;
    }

    /**
     * Create Icon From Original
     * @param $photo
     * @param $filename
     * @return \Intervention\Image\Image
     */
    public function icon($photo, $filename)
    {

        $manager = new ImageManager();
        $image = $manager->make($photo)->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($this->upload_path(FALSE) . '/' . $filename);

        return $image;
    }

    /**
     * Delete Image From Session folder, based on original filename
     * @param $originalFilename
     * @return
     */
    public function delete($originalFilename)
    {

        $full_size_dir = Config::get('images.full_size');
        $icon_size_dir = Config::get('images.icon_size');

        $sessionImage = Image::where('id', $originalFilename)->first();


        if (empty($sessionImage)) {
            return [
                'error' => true,
                'code' => 400
            ];

        }

        $full_path1 = $full_size_dir . $sessionImage->filename;
        $full_path2 = $icon_size_dir . $sessionImage->filename;

        if (File::exists($full_path1)) {
            File::delete($full_path1);
        }

        if (File::exists($full_path2)) {
            File::delete($full_path2);
        }

        if (!empty($sessionImage)) {
            $sessionImage->delete();
        }

        return [
            'error' => false,
            'code' => 200
        ];
    }

    /**
     * @param $string
     * @param bool $force_lowercase
     * @param bool $anal
     * @return mixed|string
     */
    function sanitize($string, $force_lowercase = true, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;

        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
    }


    /**
     * @param bool $fullsize
     * @return string
     */
    function upload_path($fullsize = true)
    {
        $date = date('Y-m');
        $folder_name = str_replace(':', '', $date);

        $cute = ($fullsize == TRUE) ? 'fullsize' : 'iconsize';

        if (!is_dir('storage/uploads/' . $cute . '/' . $folder_name)) {
            mkdir('./storage/uploads/' . $cute . '/' . $folder_name, 0777, TRUE);
        }
        //Move Uploaded File
        $upload_path = './storage/uploads/' . $cute . '/' . $folder_name;

        return $upload_path;
    }

}