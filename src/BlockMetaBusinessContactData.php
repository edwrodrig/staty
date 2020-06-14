<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 27-05-18
 * Time: 10:04
 */

namespace labo86\staty;

/**
 * Class BlockMetaBusinessContactData
 * @package edwrodrig\static_generator\html
 * @see https://developers.facebook.com/tools/debug/ Debug tool
 * @see https://developers.facebook.com/docs/reference/opengraph/object-type/business.business/
 * @see https://developers.facebook.com/docs/sharing/opengraph/object-properties#standard
 */
class BlockMetaBusinessContactData extends Block
{
    /**
     * @var string
     */
    private string $street_address;

    /**
     * @var string
     */
    private string $locality;

    /**
     * @var string
     */
    private string $postal_code;

    /**
     * @var string
     */
    private string $region;

    /**
     * @var string
     */
    private string $country_name;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $phone_number;

    /**
     * @var string
     */
    private string $website;

    /**
     * @var string
     */
    private string $fax_number;

    /**
     * street_address (string, required)
     *
     * The number and street of the postal address for this business.
     * @param string $street_address
     * @return BlockMetaBusinessContactData
     */
    public function setStreetAddress(string $street_address) : BlockMetaBusinessContactData
    {
        $this->street_address = $street_address;
        return $this;
    }

    /**
     * locality (string, required)
     *
     *
     * The city (or locality) line of the postal address for this business.
     * @param string $locality
     * @return BlockMetaBusinessContactData
     */
    public function setLocality(string $locality) : BlockMetaBusinessContactData
    {
        $this->locality = $locality;
        return $this;
    }

    /**
     * postal_code (string, required)
     *
     *
     * The postcode (or ZIP code) of the postal address for this business.
     * The postcode of concepcion is 4030000
     * @param string $postal_code
     * @return BlockMetaBusinessContactData
     */
    public function setPostalCode(string $postal_code) : BlockMetaBusinessContactData
    {
        $this->postal_code = $postal_code;
        return $this;
    }

    /**
     * region (string)
     *
     * The state (or region) line of the postal address for this business.
     * @param string $region
     * @return BlockMetaBusinessContactData
     */
    public function setRegion(string $region) : BlockMetaBusinessContactData
    {
        $this->region = $region;
        return $this;
    }

    /**
     * country_name (string, required)
     *
     * The country of the postal address for this business.
     * @param string $country_name
     * @return BlockMetaBusinessContactData
     */
    public function setCountryName(string $country_name) : BlockMetaBusinessContactData
    {
        $this->country_name = $country_name;
        return $this;
    }

    /**
     * email (string)
     *
     * An email address to contact this business.
     * @param string $email
     * @return BlockMetaBusinessContactData
     */
    public function setEmail(string $email) : BlockMetaBusinessContactData
    {
        $this->email = $email;
        return $this;
    }

    /**
     * phone_number (string)
     *
     * A telephone number to contact this business.
     * @param string $phone_number
     * @return BlockMetaBusinessContactData
     */
    public function setPhoneNumber(string $phone_number) : BlockMetaBusinessContactData
    {
        $this->phone_number = $phone_number;
        return $this;
    }

    /**
     * fax_number (string)
     *
     * A fax number to contact this business.
     * @param string $fax_number
     * @return BlockMetaBusinessContactData
     */
    public function setFaxNumber(string $fax_number) : BlockMetaBusinessContactData {
        $this->fax_number = $fax_number;
        return $this;
    }

    /**
     * A website for this business.
     * @param string $website
     * @return BlockMetaBusinessContactData
     */
    public function setWebsite(string $website) : BlockMetaBusinessContactData {
        $this->website = $website;
        return $this;
    }

    public function html() {
        echo $this->sprintf('<meta property="business:contact_data:street_address" content="%s" />', $this->street_address ?? null);
        echo $this->sprintf('<meta property="business:contact_data:locality" content="%s" />', $this->locality ?? null);
        echo $this->sprintf('<meta property="business:contact_data:postal_code" content="%s" />', $this->postal_code ?? null);
        echo $this->sprintf('<meta property="business:contact_data:region" content="%s" />', $this->region ?? null);
        echo $this->sprintf('<meta property="business:contact_data:country_name" content="%s" />', $this->country_name ?? null);
        echo $this->sprintf('<meta property="business:contact_data:email" content="%s" />', $this->email ?? null);
        echo $this->sprintf('<meta property="business:contact_data:phone_number" content="%s" />', $this->phone_number ?? null);
        echo $this->sprintf('<meta property="business:contact_data:fax_number" content="%s" />', $this->fax_number ?? null);
        echo $this->sprintf('<meta property="business:contact_data:website" content="%s" />', $this->website ?? null);

    }
}