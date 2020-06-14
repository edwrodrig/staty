<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 28-05-18
 * Time: 11:35
 */

namespace test\labo86\staty;

use DateTime;
use labo86\staty\BlockMetaOpenGraph;
use PHPUnit\Framework\TestCase;

class BlockMetaOpenGraphTest extends TestCase
{

    public function testHtml()
    {
        $s = new BlockMetaOpenGraph(BlockMetaOpenGraph::thisPage());
        $s->setType('website');
        $s->setDescription('hola');

        ob_start();
        $s->html();

        $output = ob_get_clean();

        $this->assertEquals('<meta property="og:type" content="website"/><meta property="og:description" content="hola"/>', $output);
    }

    public function testDate()
    {
        $s = new BlockMetaOpenGraph(BlockMetaOpenGraph::thisPage());
        $s->setType('website');
        $s->setDescription('hola');
        $s->setUpdateTime(new DateTime('2018-01-01'));

        ob_start();
        $s->html();

        $output = ob_get_clean();

        $this->assertEquals('<meta property="og:type" content="website"/><meta property="og:description" content="hola"/><meta property="og:updated_time" content="2018-01-01T00:00:00+0000"/>', $output);
    }
}
