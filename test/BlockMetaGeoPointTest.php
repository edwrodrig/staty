<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 28-05-18
 * Time: 12:39
 */

namespace test\labo86\staty;

use labo86\staty\BlockMetaGeoPoint;
use PHPUnit\Framework\TestCase;

class BlockMetaGeoPointTest extends TestCase
{

    public function testPrint()
    {
        $s = new BlockMetaGeoPoint(BlockMetaGeoPoint::thisPage());
        $s->setLatitude("0.123");

        ob_start();
        $s->html();

        $output = ob_get_clean();

        $this->assertEquals('<meta property="place:location:latitude" content="0.123">', $output);
    }
}
