<?php

/**
 * SiteParseInfo short summary.
 *
 * SiteParseInfo description.
 *
 * @version 1.0
 * @author Aleksei goliguzov
 */

final class SiteParseInfo
{
    static public $LINK        = 'Link';
    static public $UNDERGROUND = 'Underground';
    static public $PRICE       = 'Prise';
    static public $ROOMS       = 'Rooms';
    static public $FLOOR       = 'Floor';
    static public $SQUARE      = 'Square';
    static public $ADRESS      = 'Adress';

    public $link,
           $underground,
           $price,
           $rooms,
           $floor,
           $square,
           $adress;

    function SiteParseInfo($l = '', $u = '', $p = '', $r = '', $f = '', $s = '', $a = '')
    {
        $this->link        = $l;
        $this->underground = $u;
        $this->price       = $p;
        $this->rooms       = $r;
        $this->floor       = $f;
        $this->square      = $s;
        $this->adress      = $a;
    }
}