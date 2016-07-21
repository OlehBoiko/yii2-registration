<?php
namespace app\helpers;

use Yii;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\helpers\FileHelper;

class Image
{
    public static function upload(
        UploadedFile $fileInstance,
        $dir = '',
        $resizeWidth = null,
        $resizeHeight = null,
        $resizeCrop = false
    ) {
        $fileName = Upload::getUploadPath($dir) . DIRECTORY_SEPARATOR . Upload::getFileName($fileInstance);

        $uploaded = $resizeWidth
            ? self::copyResizedImage($fileInstance->tempName, $fileName, $resizeWidth, $resizeHeight, $resizeCrop)
            : $fileInstance->saveAs($fileName);

        if (!$uploaded) {
            throw new HttpException(500, 'Cannot upload file "' . $fileName . '". Please check write permissions.');
        }

        return Upload::getLink($fileName);
    }

    static function thumb($filename, $width = null, $height = null, $crop = true)
    {
        if ($filename && file_exists(($filename = Yii::getAlias('@webroot') . $filename))) {
            $info = pathinfo($filename);
            $thumbName = $info['filename'] . '-' . md5(filemtime($filename) . (int)$width . (int)$height . (int)$crop) . '.' . $info['extension'];
            $thumbFile = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Upload::$UPLOADS_DIR . DIRECTORY_SEPARATOR . 'thumbs' . DIRECTORY_SEPARATOR . $thumbName;
            $thumbWebFile = '/' . Upload::$UPLOADS_DIR . '/thumbs/' . $thumbName;
            if (file_exists($thumbFile)) {
                return $thumbWebFile;
            } elseif (FileHelper::createDirectory(dirname($thumbFile), 0777) && self::copyResizedImage($filename,
                    $thumbFile, $width, $height, $crop)
            ) {
                return $thumbWebFile;
            }
        }
        return '';
    }

    static function copyResizedImage($inputFile, $outputFile, $width, $height = null, $crop = true)
    {
        if (extension_loaded('gd')) {
            $image = new GD($inputFile);

            if ($height) {
                if ($width && $crop) {
                    $image->cropThumbnail($width, $height);
                } else {
                    $image->resize($width, $height);
                }
            } else {
                $image->resize($width);
            }
            return $image->save($outputFile);
        } elseif (extension_loaded('imagick')) {
            $image = new \Imagick($inputFile);

            if ($height && !$crop) {
                $image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, true);
            } else {
                $image->resizeImage($width, null, \Imagick::FILTER_LANCZOS, 1);
            }

            if ($height && $crop) {
                $image->cropThumbnailImage($width, $height);
            }

            return $image->writeImage($outputFile);
        } else {
            throw new HttpException(500, 'Please install GD or Imagick extension');
        }
    }

    static function cropImageSection($source_image_path, $thumbnail_image_path, $params, $degrees = 0)
    {
        if (file_exists($source_image_path) && is_file($source_image_path)) {
            $source_gd_image = false;


            $image_size = getimagesize($source_image_path);
//            $source_image_width  = isset($image_size[0]) ? $image_size[0] : null;
//            $source_image_height  = isset($image_size[1]) ? $image_size[1] : null;
            $source_image_type  = isset($image_size[2]) ? $image_size[2] : null;

            switch ($source_image_type) {
                case IMAGETYPE_GIF:
                    $source_gd_image = imagecreatefromgif($source_image_path);
                    break;
                case IMAGETYPE_JPEG:
                    $source_gd_image = imagecreatefromjpeg($source_image_path);
                    break;
                case IMAGETYPE_PNG:
                    $source_gd_image = imagecreatefrompng($source_image_path);
                    break;
            }
            if ($source_gd_image === false) {
                return false;
            }

            if (array_key_exists('degrees', $params)) {
                $degrees = isset($params['degrees']) && $params['degrees'] == 270 ? 90 : ($params['degrees'] == 90 ? 270 : $params['degrees']);
            }

//            if(isset($degrees ))
//                $rotate = imagerotate($source_gd_image, $degrees, 0);

            $thumbnail_gd_image = imagecreatetruecolor($params['width'], $params['height']);

            //     imagecopyresampled($thumbnail_gd_image, $rotate, -$params['x'], -$params['y'], 0, 0, $params['origin_w'], $params['origin_h'], $source_image_width, $source_image_height);
            $rotate = null;
            if (isset($degrees)) {
                $rotate = imagerotate($source_gd_image, $degrees * -1, 0);
            }

            $x = round($params['x'] / $params['scale']) + $params['scale'];
            $y = round($params['y'] / $params['scale']) - $params['scale'];
            $height = ($params['height'] / $params['scale']) + ceil($params['scale']);
            $width = $params['width'] / $params['scale'] - ceil($params['scale']);

            imagecopyresampled($thumbnail_gd_image, $rotate, 0, 0, $x, $y, $params['width'], $params['height'], $width,
                $height);

            imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 100);
            imagedestroy($source_gd_image);
            imagedestroy($thumbnail_gd_image);
            return true;
        }
        return false;
    }
}