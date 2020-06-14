<?php
declare(strict_types=1);

namespace labo86\staty;


/**
 * Class TwitterCard Summary
 * @package edwrodrig\static_generator\htmlt
 * @see https://developer.twitter.com/en/docs/tweets/optimize-with-cards/guides/troubleshooting-cards
 * @see https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/summary-card-with-large-image
 */
class BlockMetaTwitterCardSummary extends BlockMetaTwitterCard
{

    private string $title;

    private string $image;

    private string $image_alt;

    private string $creator;

    private bool $is_large_image = false;

    /**
     * Set if this summary is large image.
     *
     * @see https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/summary-card-with-large-image
     * @param bool $is_large_image
     * @return BlockMetaTwitterCardSummary
     */
    public function setLargeImage(bool $is_large_image) : BlockMetaTwitterCardSummary {
        $this->is_large_image = $is_large_image;
        return $this;
    }

    /**
     * Set the creator of this summary.
     *
     * The creator is the author of the content instead the owner of the page.
     * In the case of a news, this should be the twitter account of the writer.
     * Only works in when this card is {@see BlockMetaTwitterCardSummary::setLargeImage() large image}
     * @param string $creator
     * @return BlockMetaTwitterCardSummary
     */
    public function setCreator(string $creator) : BlockMetaTwitterCardSummary {
        $this->creator = $creator;
        return $this;
    }

    /**
     * A concise title for the related content.
     *
     * Platform specific behaviors:
     * * iOS, Android: Truncated to two lines in timeline and expanded Tweet
     * * Web: Truncated to one line in timeline and expanded Tweet
     * When not set, Fallbacks to {@see BlockMetaOpenGraph::setTitle()}
     * @param string $title
     * @return $this
     *@api
     */
    public function setTitle(string $title) : BlockMetaTwitterCardSummary {
        $this->title = $title;
        return $this;
    }

    /**
     * A URL to a unique image representing the content of the page.
     *
     * You should not use a generic image such as your website logo, author photo, or other image that spans multiple pages.
     * Images for this Card support an aspect ratio of 1:1 with minimum dimensions of 144x144
     * or maximum of 4096x4096 pixels.
     * Images must be less than 5MB in size.
     * The image will be cropped to a square on all platforms. JPG, PNG, WEBP and GIF formats are supported.
     * Only the first frame of an animated GIF will be used. SVG is not supported.
     * When not set, Fallbacks to {@see BlockMetaOpenGraph::setImage()}
     * MUST BE AN ABSOLUTE URL {@see https://stackoverflow.com/questions/45915720/relative-image-paths-for-twitter-cards-in-blogdown}
     * @param string $image
     * @return $this
     *@api
     */
    public function setImage(string $image) : BlockMetaTwitterCardSummary {
        $this->image = $image;
        return $this;
    }

    /**
     * Set a text description of the image conveying the essential nature of an image to users who are visually impaired.
     *
     * Maximum 420 characters.
     * @api
     * @param string $alt
     * @return $this
     */
    public function setImageAlt(string $alt) : BlockMetaTwitterCardSummary {
        $this->image_alt= $alt;
        return $this;
    }


    /**
     * Print the image to HTML.
     */
    public function html() {
        if ( $this->is_large_image ) {
            echo $this->sprintf('<meta name="twitter:card" content="summary_large_image"/>');
            echo $this->sprintf('<meta name="twitter:creator" content="%s"/>', $this->creator ?? null);
        } else {
            echo $this->sprintf('<meta name="twitter:card" content="summary"/>');
        }

        parent::html();
        echo $this->sprintf('<meta name="twitter:title" content="%s"/>', $this->title  ?? null);
        echo $this->sprintf('<meta name="twitter:image" content="%s"/>', $this->image ?? $this->image_alt ?? null);

        if ( isset($this->image) )
            echo $this->sprintf('<meta name="twitter:image:alt" content="%s"/>', $this->image_alt ?? null);

    }


}