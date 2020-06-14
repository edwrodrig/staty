<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 28-05-18
 * Time: 11:54
 */

namespace test\labo86\staty;

use labo86\staty\AppleWebApplication;
use labo86\staty\BlockMetaAppleWebApplication;
use PHPUnit\Framework\TestCase;

class BlockMetaAppleWebApplicationTest extends TestCase
{

    public function testHtml()
    {
        $s = new BlockMetaAppleWebApplication(BlockMetaAppleWebApplication::thisPage());
        $s->setWebCapable(true);

        ob_start();
        $s->html();

        $output = ob_get_clean();

        $this->assertEquals('<meta name="apple-mobile-web-app-capable" content="yes">', $output);
    }


    public function testHtmlNotWebCapable()
    {
        $s = new BlockMetaAppleWebApplication(BlockMetaAppleWebApplication::thisPage());
        $s->setWebCapable(false);

        ob_start();
        $s->html();

        $output = ob_get_clean();

        $this->assertEmpty($output);
    }

    public function testHtmlDefaultWebCapable()
    {
        $s = new BlockMetaAppleWebApplication(BlockMetaAppleWebApplication::thisPage());

        ob_start();
        $s->html();

        $output = ob_get_clean();

        $this->assertEmpty($output);
    }
}
