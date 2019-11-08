<?php
namespace helper\loader;

use stdClass;

class ImageLoader {
    public static function load(string $f, string $locale): stdClass {
        $groups = new stdClass();

        $dom = simplexml_load_file($f);
        foreach($dom->group as $groupEl) {
            $id = (String) $groupEl->attributes()->id;
            $groups->$id = self::makeGroup($groupEl, $locale);
        }

        return $groups;
    }

    private static function makeGroup($groupEl, string $locale): stdClass {
        $group = new stdClass();

        foreach($groupEl->image as $imageEl) {
            $id = $imageEl->attributes()->id;
            $path = (String) $groupEl->attributes()->path;

            $group->$id = self::makeImage($imageEl, $path, $locale);
        }

        return $group;
    }

    public static function makeImage($imageEl, string $path, string $locale): stdClass {
        if($path != '') {
            $path .= '/';
        }

        $img = new stdClass();
        $img->id = (String) $imageEl->attributes()->id;

        $img->file = $path . (String) $imageEl->file[0];
        $img->files = new stdClass();

        foreach($imageEl->file as $fileEl) {
            if(isset($fileEl->attributes()->type)) {
                $file = $path . (String) $fileEl;
                $fileType = (String) $fileEl->attributes()->type;
                $img->files->$fileType = $file;
            }
        }

        foreach($imageEl->alt as $alt) {
            if($alt->attributes()->locale == $locale) {
                $img->alt = (String) $alt;
                break;
            }
        }

        return $img;
    }
}