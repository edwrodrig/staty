<?php
declare(strict_types=1);
namespace labo86\staty;
use DateTime;


/**
 * Class BlockMetaOpenGraph
 *
 * Class to implement BlockMetaOpenGraph meta tags, focused in the compatibility with facebook
 * @see http://ogp.me/ Opengraph page
 * @see https://developers.facebook.com/docs/sharing/opengraph
 * @see https://developers.facebook.com/docs/sharing/opengraph/object-properties?locale=en_US#standard
 * @see https://developers.facebook.com/tools/debug/ Debug tool
 * @see https://developers.facebook.com/docs/sharing/best-practices/#tags
 * @see https://developers.facebook.com/docs/sharing/best-practices/#images
 */
class BlockMetaOpenGraph extends Block
{



    private string $url;


    private string $title;


    private string $description;


    private string $type;

    private string $image;

    private int $image_width;


    private int $image_height;


    private string $locale;

    private string $determiner;


    /**
     * Must be in ISO8601 format
     *
     * @var DateTime|null
     * @see http://ogp.me/#data_types For format info
     * @see https://stackoverflow.com/questions/26525584/facebook-open-graph-date-format Question about date format
     */
    private DateTime $update_time;


    private string $see_also;


    private bool $rich_attachment = false;

    private int $time_to_live;


    /**
     *
     * The URL of the object, which acts as the canonical URL.
     * Usually the same URL as the page where property tags are placed.
     * It shouldn't include any session variables, user identifying parameters, or counters.
     * Always use the canonical URL for this tag, or likes and shares will be spread across all of the variations of the URL.
     * @param string $url
     * @return BlockMetaOpenGraph
     */
    public function setUrl(string $url): BlockMetaOpenGraph
    {
        $this->url = $url;
        return $this;
    }

    /**
     * The title, headline or name of the object.
     * @param string $title
     * @return BlockMetaOpenGraph
     */
    public function setTitle(string $title): BlockMetaOpenGraph
    {
        $this->title = $title;
        return $this;
    }

    /**
     * A short description or summary of the object.
     * @param string $description
     * @return BlockMetaOpenGraph
     */
    public function setDescription(string $description): BlockMetaOpenGraph
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Find out the type of your object in the Action Type section of App Dashboard.
     * Select the object and find its og:type under Advanced.
     * Once an object is published in a story its type can't be changed.
     * Most commit type is 'website'
     * @param string $type
     * @return BlockMetaOpenGraph
     */
    public function setType(string $type): BlockMetaOpenGraph
    {
        $this->type = $type;
        return $this;
    }

    /**
     * The URL of the image for your object.
     *
     * It should be at least 600x315 pixels, but 1200x630 or larger is preferred (up to 5MB).
     * Stay close to a 1.91:1 aspect ratio to avoid cropping.
     * Game icons should be square and at least 600x600 pixels.
     * You can include multiple og:image tags if you have multiple resolutions available.
     * If you update the image after publishing, use a new URL because images are cached based on the URL and might not update otherwise.
     * @see https://stackoverflow.com/questions/9858577/open-graph-can-resolve-relative-url MUST BE A ABSOLUTE URL
     * @param string $image
     * @return BlockMetaOpenGraph
     */
    public function setImage(string $image): BlockMetaOpenGraph
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Set the image width
     * @param int $image_width
     * @return BlockMetaOpenGraph
     */
    public function setImageWidth(int $image_width): BlockMetaOpenGraph
    {
        $this->image_width = $image_width;
        return $this;
    }

    /**
     * Set image height
     *
     * @param int $image_height
     * @return BlockMetaOpenGraph
     */
    public function setImageHeight(int $image_height): BlockMetaOpenGraph
    {
        $this->image_height = $image_height;
        return $this;
    }

    /**
     * Set locale
     *
     * The language locale that object properties use. The default is en_US.
     * @param string $locale
     * @return BlockMetaOpenGraph
     */
    public function setLocale(string $locale): BlockMetaOpenGraph
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Set de determiner
     *
     * The word that appears before the object in a story (such as "an Omelette").
     * This value should be a string that is a member of the Enum {a, an, the, "", auto}.
     * When 'auto' is selected, Facebook will choose between 'a' or 'an'. Default is blank.
     * Is the spanish articulo definido-indefinido
     * @param string $determiner
     * @return BlockMetaOpenGraph
     */
    public function setDeterminer(string $determiner): BlockMetaOpenGraph
    {
        $this->determiner = $determiner;
        return $this;
    }

    /**
     * Set the date of last actualization
     *
     * When the object was last updated.
     * @param DateTime $update_time
     * @return BlockMetaOpenGraph
     */
    public function setUpdateTime(DateTime $update_time): BlockMetaOpenGraph
    {
        $this->update_time = $update_time;
        return $this;
    }

    /**
     * Set additional link
     *
     * Used to supply an additional link that shows related content to the object.
     * @param string $see_also
     * @return BlockMetaOpenGraph
     */
    public function setSeeAlso(string $see_also): BlockMetaOpenGraph
    {
        $this->see_also = $see_also;
        return $this;
    }

    /**
     * Set if is rich attachment
     *
     * When "true", stories published will render with rich metadata such as the title, description, author, site name, and preview image.
     * @param bool $rich_attachment
     * @return BlockMetaOpenGraph
     */
    public function setRichAttachment(bool $rich_attachment): BlockMetaOpenGraph
    {
        $this->rich_attachment = $rich_attachment;
        return $this;
    }

    /**
     * Set the time to live
     *
     * Seconds until this page should be re-scraped. Use this to rate limit the Facebook content crawlers. The minimum allowed value is 345600 seconds (4 days); if you set a lower value, the minimum will be used. If you do not include this tag, the ttl will be computed from the "Expires" header returned by your web server, otherwise it will default to 7 days.
     * @param int $time_to_live
     * @return BlockMetaOpenGraph
     */
    public function setTimeToLive(int $time_to_live): BlockMetaOpenGraph
    {
        $this->time_to_live = $time_to_live;
        return $this;
    }

    public function html() {
        echo $this->sprintf('<meta property="og:type" content="%s"/>', $this->type ?? null);
        echo $this->sprintf('<meta property="og:url" content="%s"/>', $this->url ?? null);
        echo $this->sprintf('<meta property="og:title" content="%s"/>', $this->title ?? null);
        echo $this->sprintf('<meta property="og:description" content="%s"/>', $this->description ?? null);
        echo $this->sprintf('<meta property="og:image" content="%s"/>', $this->image ?? null);
        echo $this->sprintf('<meta property="og:locale" content="%s"/>', $this->locale ?? null);
        echo $this->sprintf('<meta property="og:determiner" content="%s"/>', $this->determiner ?? null);

        if ( isset($this->update_time))
            echo $this->sprintf('<meta property="og:updated_time" content="%s"/>', $this->update_time->format(DateTime::ISO8601));

        echo $this->sprintf('<meta property="og:see_also" content="%s"/>', $this->see_also ?? null);
        echo $this->sprintf('<meta property="og:ttl" content="%s"/>', $this->time_to_live ?? null);

        if ( isset($this->image) ) {
            echo $this->sprintf('<meta property="og:image:height" content="%d"/>', $this->image_height ?? null);
            echo $this->sprintf('<meta property="og:image:width" content="%d"/>', $this->image_width ?? null);
        }
        if ( $this->rich_attachment )
            echo $this->sprintf('<meta property="og:rich_attachment" content="true"/>');
    }




}