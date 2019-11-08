<?php
namespace helper\loader;

use stdClass;
use PeazyPhp\Request;

class NavLoader {
    public static function load(string $f, Request $request): stdClass {
        $nav = new stdClass();
        $nav->primary = [];
        $nav->secondary = [];

        $dom = simplexml_load_file($f);
        foreach ($dom->link as $link1) {
            $route1 = (string) $link1->attributes()->route;
            $label1 = (string) $link1->attributes()->label;
            $module1 = (string) $link1->attributes()->module;

            if($link1->link) {
                $nav->primary[$route1] = [];

                foreach($link1->link as $link2) {
                    $route2 = (string) $link2->attributes()->route;
                    $label2 = (string) $link2->attributes()->label;
                    $controller2 = (string) $link2->attributes()->controller;

                    $nav->primary[$route1][$route2] = $label2;

                    if($module1 == $request->module
                    && $controller2 == $request->controller
                    && $link2->link) {
                        foreach($link2->link as $link3) {
                            $route3 = (string) $link3->attributes()->route;
                            $label3 = (string) $link3->attributes()->label;

                            $nav->secondary[$route3] = $label3;
                        }
                    }
                }
            } else {
                $nav->primary[$route1] = $label1;
            }
        }

        return $nav;
    }
}