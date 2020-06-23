<?php
declare(strict_types=1);

namespace labo86\staty;

use edwrodrig\image\exception\ConvertingSvgException;
use edwrodrig\image\exception\InvalidImageException;
use edwrodrig\image\exception\InvalidSizeException;
use edwrodrig\image\exception\WrongFormatException;
use edwrodrig\image\Image;
use edwrodrig\image\Size;
use Imagick;
use ImagickException;
use labo86\staty_core\PageFile;
use labo86\staty_core\SourceFileInterface;

/**
 * Class ImageItem
 *
 * Use this function to cache images
 * This class works with a {@see CacheManager cache} in the following way.
 * ```
 * $file = new ImageItem('/images', 'image.jpg');
 * $cache_manager->update($file);
 * ```
 *
 * Maybe you want to override {@see ImageItem::process()} to creating images with other behaviours.
 * This class works with {@see Image SVG, PNG and JPG} file formats.
 * @api
 * @package edwrodrig\static_generator\cache
 */
class PageImage extends PageFile
{
    /**
     * The width of the image
     *
     * This is used in the context or {@see ImageItem::resizeContain() resizes}
     */
    protected int $width;

    /**
     * The height of the image.
     *
     * This is used in the context or {@see ImageItem::resizeContain() resizes}
     */
    protected int $height;

    /**
     * Resize mode.
     *
     * What resize mode will be executed in {@see ImageItem::generate() generation}.
     * @var int
     */
    protected int $resize_mode = self::RESIZE_MODE_COPY;

    /**
     * The image should not be resized.
     */
    const RESIZE_MODE_COPY = 0;

    /**
     * The image should be resized to be {@see Image::contain() contained}.
     */
    const RESIZE_MODE_CONTAIN = 1;

    /**
     * The image should be resized to be {@see Image::cover() cover}.
     */
    const RESIZE_MODE_COVER = 2;


    private string $version;

    /**
     * ImageItem constructor.
     *
     * @param SourceFileInterface $source
     * @param string $relative_filename
     * @api
     */
    public function __construct(SourceFileInterface $source, string $relative_filename)
    {
        parent::__construct($source, $relative_filename);
    }

    /**
     * Get the image width.
     *
     * Is safe to call this after {@see ImageItem::generate()}
     * @return int
     * @throws ImagickException
     */
    public function getWidth() : int {
        if ( !isset($this->width) ) {
            $image = new Imagick($this->source->getFilename());

            $dimension = $image->getImageGeometry();

            $this->width = $dimension['width'];
            $this->height = $dimension['height'];
        }
        return $this->width;
    }

    /**
     * Get the image height.
     *
     * Is safe to call this after {@see ImageItem::generate()}
     * @return int
     * @throws ImagickException
     */
    public function getHeight() : int {
        $this->getWidth();
        return $this->height;
    }

    /**
     * Command to resize contain the image
     *
     * Uses the behavior of (@see Image::contain()}.
     * @api
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function resizeContain(int $width, int $height) : PageImage {
        $this->width = $width;
        $this->height = $height;
        $this->version = $width . 'x' . $height . '_contain';
        $this->resize_mode = self::RESIZE_MODE_CONTAIN;

        return $this;
    }

    /**
     * Command to resize cover the image
     *
     * Uses the behavior of (@see Image::cover()}.
     * @api
     * @param int $width
     * @param int $height
     * @return $this
     */
    public function resizeCover(int $width, int $height) : PageImage {
        $this->width = $width;
        $this->height = $height;
        $this->version = $width . 'x' . $height . '_cover';
        $this->resize_mode = self::RESIZE_MODE_COVER;

        return $this;
    }

    public function getRelativeFilename() : string {
         $file_info =  pathinfo ( $this->relative_filename);
         $name_elements = [];
         $name_elements[] =  $file_info['filename'];
         if ( isset($this->version) ) $name_elements[] = '_' . $this->version;
         if ( isset($file_info['extension'])) $name_elements[] = '.' . $file_info['extension'];
         $basename = implode('', $name_elements);

         if ( $file_info['dirname'] != '.')
            return $file_info['dirname'] . '/' . $basename;
         else
             return $basename;
    }

    public function getExtension() : string {
        return pathinfo ( $this->relative_filename , PATHINFO_EXTENSION);
    }

    /**
     * Generate the image.
     *
     * If the target extension is {@see ImageItem::setTargetExtension() forced to jpg}
     * then the generated image is {@see Image::optimizePhoto() optimized as photo}/
     * @param string $filename
     * @throws ImagickException
     * @throws ConvertingSvgException
     * @throws InvalidImageException
     * @throws InvalidSizeException
     * @throws WrongFormatException
     * @api
     * @uses ImageItem::process()
     */
    public function generate(string $filename) {

        $img = Image::createFromFile($this->source->getFilename(), $this->width ?? 1000);

        if ( $this->resize_mode == self::RESIZE_MODE_CONTAIN ) {
            $img->contain(new Size($this->width, $this->height));

        } else if ( $this->resize_mode == self::RESIZE_MODE_COVER ) {
            $img->cover(new Size($this->width, $this->height));
        }

        $target_extension = $this->getExtension();
        if ( $target_extension == 'jpg') {
            $img->optimizePhoto();
        } else if ( $target_extension == 'png' ) {
            $img->optimizeLossless();
        } else {
            $img->optimize();
        }

        $this->width = $img->getImagickImage()->getImageWidth();
        $this->height = $img->getImagickImage()->getImageHeight();


        $img->writeImage($filename);
    }
}