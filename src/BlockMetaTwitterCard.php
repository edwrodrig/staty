<?php
declare(strict_types=1);

namespace labo86\staty;


/**
 * Class TwitterCard
 * @package edwrodrig\static_generator\htmlt
 * @see https://developer.twitter.com/en/docs/tweets/optimize-with-cards/guides/troubleshooting-cards
 * @see https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/abouts-cards
 * @see https://cards-dev.twitter.com/validator Validator
 */
abstract class BlockMetaTwitterCard extends Block
{

    /**
     * @var string
     */
    private string $site;

    /**
     * @var null|string
     */
    private string $description;


    /**
     * Set the twitter username of the card should be attributed to
     *
     * Should add the leading @
     * Website Attribution:
     * Indicates the Twitter account for the website or platform on which the content was published.
     * Note that a service may set separate Twitter accounts for different pages/sections of their website,
     * and the most appropriate Twitter account should be used to provide the best context for the user.
     * For example, nytimes.com may set the the website attribution to “@nytimes” for front page articles, and “@NYTArts”
     * for articles in the Arts & Entertainment section.
     * @api
     * @param string $user
     * @return $this
     */
    public function setSite(string $user) : self {
        $this->site = $user;
        return $this;

    }


    /**
     * A description that concisely summarizes the content as appropriate for presentation within a Tweet.
     *
     * You should not re-use the title as the description or use this field to describe the general services provided by the website.
     * Platform specific behaviors:
     * * iOS, Android: Not displayed
     * * Web: Truncated to three lines in timeline and expanded Tweet
     * When not set, fallbacks to {@see BlockMetaOpenGraph::setDescription()}
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description) : self {
        $this->description = $description;
        return $this;
    }

    /**
     * Print the image to HTML.
     */
    public function html() {

        echo $this->sprintf('<meta name="twitter:site" content="%s"/>', $this->site ?? null);
        echo $this->sprintf('<meta name="twitter:description" content="%s"/>', $this->description ?? null);
    }


}