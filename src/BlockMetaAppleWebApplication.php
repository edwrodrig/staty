<?php
declare(strict_types=1);

namespace labo86\staty;

/**
 * Class AppleWebApplication
 *
 * A class to implementing {@see https://developer.apple.com/library/content/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html web applications tags} recommended by apple.
 * This class is made to be used inside the head section of a html document
 * ```
 * <head>
 * <?php (new AppleWebApplication)->setIcon16x16('some_icon.png')->print() ?>
 * </head>
 * ```
 * @package edwrodrig\static_generator\html
 */
class BlockMetaAppleWebApplication extends Block
{

    /**
     * @var string[]
     */
    private array $icons = [];


    private string $startup_image;

    private string $status_bar_style;

    private bool $web_capable = false;


    private string $title;


    /**
     * Set a icon of 72x72.
     *
     * @param string $icon
     * @return BlockMetaAppleWebApplication
     */
    public function setIcon72x72(string $icon) : BlockMetaAppleWebApplication {
        $this->icons[72] = $icon;
        return $this;
    }

    /**
     * Set a icon of 152x152
     *
     * @param string $icon
     * @return BlockMetaAppleWebApplication
     * @api
     */
    public function setIcon152x125(string $icon) : BlockMetaAppleWebApplication
    {
        $this->icons[152] = $icon;
        return $this;
    }

    /**
     * Set a icon of 167x167.
     *
     * @param string $icon
     * @return BlockMetaAppleWebApplication
     */
    public function setIcon167x167(string $icon) : BlockMetaAppleWebApplication
    {
        $this->icons[167] = $icon;
        return $this;
    }

    /**
     * Set a icon of 180x180.
     *
     * User for retina displays
     * @param string $icon
     * @return BlockMetaAppleWebApplication
     */
    public function setIcon180x180(string $icon) : BlockMetaAppleWebApplication
    {
        $this->icons[180] = $icon;
        return $this;
    }

    /**
     * Set the startup image.
     *
     * Is seems that is some splash image that is show while the page is loading.
     * When this is not defined it seems that apple devices show a last screenshow of the app. But I do not have a apple device to test it.
     *
     * @param string $image
     * @return BlockMetaAppleWebApplication
     */
    public function setStartupImage(string $image) : BlockMetaAppleWebApplication
    {
        $this->startup_image = $image;
        return $this;
    }

    /**
     * Set if the application if web capable
     *
     * @param bool $web_capable
     * @return BlockMetaAppleWebApplication
     */
    public function setWebCapable(bool $web_capable) : BlockMetaAppleWebApplication
    {
        $this->web_capable = $web_capable;
        return $this;
    }

    /**
     * Set the status bar style
     *
     * The style generally is a color, for example 'black'
     * @see https://developer.apple.com/documentation/uikit/uinavigationbar
     * @param string $style
     * @return BlockMetaAppleWebApplication
     */
    public function setStatusBarStyle(string $style) : BlockMetaAppleWebApplication
    {
        $this->status_bar_style = $style;
        return $this;
    }

    /**
     * Set the title of the app.
     *
     * Generally match with the page name.
     * @param string $title
     * @return BlockMetaAppleWebApplication
     */
    public function setTitle(string $title) : BlockMetaAppleWebApplication
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Print the web application tags.
     *
     * Ignore which are not set
     */
    public function html() {
        if ( $this->web_capable ) {
            echo $this->sprintf('<meta name="apple-mobile-web-app-capable" content="yes">');
        }

        echo $this->sprintf('<meta name="apple-mobile-web-app-title" content="%s">', $this->title ?? null);
        echo $this->sprintf('<meta name="apple-mobile-web-app-status-bar-style" content="%s">', $this->status_bar_style ?? null);
        echo $this->sprintf('<link rel="apple-touch-startup-image" href="%s">', $this->startup_image ?? null);


        foreach ( $this->icons as $size => $href )  {
            echo $this->sprintf('<link rel="apple-touch-icon" sizes="%dx%d" href="%s">', $size ?? null, $size ?? null, $href ?? null);
        }

    }

}