<?php
declare(strict_types=1);

namespace labo86\staty;

/**
 * Class BlockMetaGeoPoint
 *
 * Use the geopoint complex type when defining objects that specify spatial information (ex: geographic location).
 */
class BlockMetaGeoPoint extends Block
{
    
    private string $longitude;
    
    private string $latitude;

    private string $altitude;

    /**
     * longitude
     *
     * Longitude as represented in decimal degrees format.
     * @param string $longitude
     * @return BlockMetaGeoPoint
     */
    public function setLongitude(string $longitude): BlockMetaGeoPoint
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * latitude
     *
     * Latitude as represented in decimal degrees format.
     * @param string $latitude
     * @return BlockMetaGeoPoint
     */
    public function setLatitude(string $latitude): BlockMetaGeoPoint
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * altitude
     *
     * Altitude of location in feet.
     * @param string $altitude
     * @return BlockMetaGeoPoint
     */
    public function setAltitude(string $altitude): BlockMetaGeoPoint
    {
        $this->altitude = $altitude;
        return $this;
    }

    public function html() {
        echo $this->sprintf('<meta property="place:location:longitude" content="%s">', $this->longitude ?? null);
        echo $this->sprintf('<meta property="place:location:latitude" content="%s">', $this->latitude ?? null);
        echo $this->sprintf('<meta property="place:location:altitude" content="%s">', $this->altitude ?? null);
    }


}