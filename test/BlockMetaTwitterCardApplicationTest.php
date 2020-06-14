<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 28-05-18
 * Time: 11:51
 */

namespace test\labo86\staty;

use labo86\staty\Block;
use labo86\staty\BlockMetaTwitterCardApplication;
use PHPUnit\Framework\TestCase;

class BlockMetaTwitterCardApplicationTest extends TestCase
{

    public function testHtml()
    {
        $s = new BlockMetaTwitterCardApplication(Block::thisPage());

        ob_start();
        $s->html();

        $output = ob_get_clean();
        $this->assertEquals('<meta name="twitter:card" content="app"/>', $output);
    }
}
