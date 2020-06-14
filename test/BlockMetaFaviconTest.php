<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 28-05-18
 * Time: 11:30
 */

namespace test\labo86\staty;

use labo86\staty\BlockMetaFavicon;
use PHPUnit\Framework\TestCase;

class BlockMetaFaviconTest extends TestCase
{

    public function testHtml()
    {
        $s = new BlockMetaFavicon(BlockMetaFavicon::thisPage());
        $s->setIcon16x16('hola16');
        $s->setIcon48x48('hola48');

        ob_start();
        $s->html();

        $output = ob_get_clean();

        $this->assertEquals('<link rel="shortcut icon" sizes="16x16" href="hola16"><link rel="shortcut icon" sizes="48x48" href="hola48">', $output);
    }
}
