<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 25-05-18
 * Time: 23:13
 */

namespace labo86\staty;

/**
 * Class BlockMetaFavicon
 *
 * Function to set the favicons header links.
 * The supported sizes are {@see BlockMetaFavicon::setIcon16x16() 16x16}, {@see BlockMetaFavicon::setIcon24x24() 24x24}, {@see BlockMetaFavicon::setIcon32x32() 32x32},
 * {@see BlockMetaFavicon::setIcon48x48() 48x48} and {@see BlockMetaFavicon::setIcon64x64() 64x64}
 * This class is made to be used inside the head section of a html document
 * ```
 * <head>
 * <?php (new BlockMetaFavicon)->setIcon16x16('some_icon.png')->print() ?>
 * </head>
 * ``
 * @package edwrodrig\static_generator\html
 */
class BlockMetaFavicon extends Block
{
    /**
     * The array that holds the icons
     * @var array
     */
    private array $icons = [];

    /**
     * Set the 16x16 icon
     *
     * @param string $icon
     * @return BlockMetaFavicon
     * @api
     */
    public function setIcon16x16(string $icon) : BlockMetaFavicon
    {
        $this->icons[16] = $icon;
        return $this;
    }

    /**
     * Set the 24x24 icon
     * @param string $icon
     * @return BlockMetaFavicon
     * @api
     */
    public function setIcon24x24(string $icon) : BlockMetaFavicon
    {
        $this->icons[24] = $icon;
        return $this;
    }

    /**
     * Set the 32x32 icon
     *
     * @param string $icon
     * @return BlockMetaFavicon
     * @api
     */
    public function setIcon32x32(string $icon) : BlockMetaFavicon
    {
        $this->icons[32] = $icon;
        return $this;
    }

    /**
     * Set the 48x48 icon
     *
     * @param string $icon
     * @return BlockMetaFavicon
     * @api
     */
    public function setIcon48x48(string $icon) : BlockMetaFavicon
    {
        $this->icons[48] = $icon;
        return $this;
    }

    /**
     * Set the 64x64 icon
     *
     * @param string $icon
     * @return BlockMetaFavicon
     * @api
     */
    public function setIcon64x64(string $icon) : BlockMetaFavicon
    {
        $this->icons[64] = $icon;
        return $this;
    }

    /**
     * Print the favicons links
     * @api
     */
    public function html()
    {
        foreach ($this->icons as $size => $href) {
            echo $this->sprintf('<link rel="shortcut icon" sizes="%dx%d" href="%s">', $size, $size, $href);
        }
    }

}