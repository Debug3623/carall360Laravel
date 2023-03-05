<?php

/**
 * Created by PhpStorm.
 * User: RadixCode
 * Date: 3/27/2017
 * Time: 7:57 PM
 */

namespace App\Helpers;

use Intervention\Image\Facades\Image;
use App\User;
use Illuminate\Support\Facades\File;

class FileHelper {

    public static function bytesToHuman($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function saveFile($file,$subFolder){

        $result = self::getAndCreateUrl($file->getClientOriginalName(),$subFolder);

        if($result['success']){
            $file->move($result['url'], $result['name']);
            return $result['url'].'/'.$result['name'];
        }

        return null;
    }

    public static function getAndCreateUrl($name,$subFolder){
        $name = date('U').'_'.$name;
        $url = "storage/$subFolder/";
        $url = $url . date('Y') . '/' .date('F');

        if(!File::exists($url)) {
            $fileSaved = File::makeDirectory($url, 0775, true);
        }else{
            $fileSaved = true;
        }

        return ['success' => $fileSaved,'name' =>$name,'url'=>$url];
    }

    public static function saveImageFromBase64($base64,$name,$subFolder){
        $results = self::getAndCreateUrl($name,$subFolder);
        $imageUrl = $results['url'].'/'.$results['name'];
        file_put_contents($imageUrl, base64_decode($base64));
        return $imageUrl;
    }

    public static function deleteFileIfNotDefault($file,$default = null){
        if(File::exists($file)){
            File::delete($file);
        }
    }

    public static function saveImages($image,$subFolder){

        $img = Image::make($image);
        
        $width = 600; // your max width
        $height = 600; // your max height
       
        $img->height() > $img->width() ? $width=null : $height=null;
        
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        
        $result = self::getAndCreateUrl($img->filename, $subFolder);

        $file_type = substr($img->mime,strpos($img->mime,'/')+1);

        $result['name'] = $result['name'].str_random(15).'.'.$file_type;

        $url = self::saveImage($img,'',1,$result);

        return [
            'url' => $url,
            'file_type'=>$file_type,
            'name' => $result['name'],
        ];
    }

    public static function saveImage($img,string $prefix, float $percentage,array $result){

        $img->resize($img->width()*$percentage, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $url = $result['url']."/$prefix".$result['name'];
        $img->save($url, 60);

        return $url;
    }

    public static function deleteImages($media){
        
        self::deleteFileIfNotDefault($media->url);
    }
}