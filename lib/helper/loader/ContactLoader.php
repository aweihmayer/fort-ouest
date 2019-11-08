<?php
namespace helper\loader;

use stdClass;
use helper\loader\ImageLoader;

class ContactLoader {
    public static function load(string $f, string $locale): stdClass {
        $contacts = new stdClass();

        $dom = simplexml_load_file($f);
        foreach ($dom->contact as $c) {
            $id = $c->attributes()->id;
            $contacts->$id = self::makeContact($c, $locale);
        }

        return $contacts;
    }

    private static function makeContact($contact, string $locale): stdClass {
        $c = new stdClass();
        $c->id = (String) $contact->attributes()->id;
        $c->name = (String) $contact->name;
        $c->postalCode = (String) $contact->postalCode;
        $c->address = (String) $contact->address;
        $c->city = (String) $contact->province;
        $c->province = (String) $contact->province;
        $c->country = (String) $contact->country;
        $c->fullAddress = (String) $contact->fullAddress;
        $c->email = (String) $contact->email;
        $c->phone = (String) $contact->phone;
        $c->externalBooking = (String) $contact->bookUrl;
        $c->website = (String) $contact->website;

        $c->socialMedia = new stdClass();
        if ($contact->socialMedia) {
            foreach ($contact->socialMedia->media as $sm) {
                $smSource = (String) $sm->attributes()->source;
                $c->socialMedia->$smSource = (String) $sm;
            }
        }

        $c->hours = new stdClass();
        if ($contact->hours) {
            foreach ($contact->hours->period as $p) {
                $hoursLabel = (String) $p->attributes()->label;
                $c->hours->$hoursLabel = (String) $p;
            }
        }

        $c->logo = ImageLoader::makeImage($contact->logo, '', $locale);

        return $c;
    }
}