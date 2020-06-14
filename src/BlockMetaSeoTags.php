<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 26-05-18
 * Time: 9:51
 */

namespace labo86\staty;

/**
 * Class BlockMetaSeoTags
 *
 * Search Engine Optimization tags
 * @api
 * @package edwrodrig\static_generator\html
 * @see https://support.google.com/webmasters/answer/35624
 */
class BlockMetaSeoTags extends Block
{

    private string $description;

    /**
     * Set the description meta tag
     *
     * Use less than 150 character or google will trim it.
     * This is what should show in google search result as a description.
     * The meta description doesn't just have to be in sentence format;
     * it's also a great place to include structured data about the page.
     * For example, news or blog postings can list the author, date of publication, or byline information. This can give potential visitors very relevant information that might not be displayed in the snippet otherwise. Similarly, product pages might have the key bits of information—price, age, manufacturer—scattered throughout a page. A good meta description can bring all this data together. For example, the following meta description provides detailed information about a book.
     * ```
     * <meta name="Description" content="Author: A.N. Author,
     * Illustrator: P. Picture, Category: Books, Price: $17.99,
     * Length: 784 pages">
     * ```
     * In this example, information is clearly tagged and separated.
     * @param string $description
     * @return BlockMetaSeoTags
     * @api
     */
    public function setDescription(string $description) : BlockMetaSeoTags
    {
        $this->description = $description;
        return $this;
    }

    public function html() {
        echo $this->sprintf('<meta name="description" content="%s">', $this->description ?? null);
    }

}