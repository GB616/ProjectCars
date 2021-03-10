<?php

namespace App;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageOptimizer
{
    private const MAX_WIDTH = 200;
    private const MAX_HEIGTH = 150;

    private $image;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function resize(string $filename1, int $width, int $height) 
    {
        //$width = self::MAX_WIDTH;
        //$height = self::MAX_HEIGTH;

        $filename = $filename1;
        //dd($filename);
        //list($iwidth, $iheight) = 
        //dd(getimagesize($filename)[0]);
        //$new = getimagesize($filename);
        //dd(getimagesize('C:\Users\Kompik1\Desktop\S\sfprojectcars\projectcars\public\upload\photos\\' . '' . $filename)[1]);
        $iheight = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/upload/photos/" . $filename)[1];//$filename)[1]; //= imagesy($filename);
        $iwidth  = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/upload/photos/" . $filename)[0]; //getimagesize($filename)[0]; //= imagesx($filename);
        //$iheight = 110;
        //$iwidth = 300;

        $ratio = $iwidth / $iheight;

        

        if($width / $height > $ratio ){
            $width = $height / $ratio;
        }
        else{
            $height = $width / $ratio;
        }
        //dd($filename);
        $photo  = $this->imagine->open($_SERVER['DOCUMENT_ROOT'] . "/upload/photos/" . $filename);
        $photo->resize(new Box($width, $height))->save($_SERVER['DOCUMENT_ROOT'] . "/upload/photos/" . $filename);
         
    }

}