<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 28-05-18
 * Time: 11:49
 */

namespace test\labo86\staty;

use labo86\staty\BlockMetaTwitterCardSummary;
use PHPUnit\Framework\TestCase;

class BlockMetaTwitterCardSummaryTest extends TestCase
{

    public function testHtmlSummary()
    {
        $s = new BlockMetaTwitterCardSummary(BlockMetaTwitterCardSummary::thisPage());

        ob_start();
        $s->html();

        $output = ob_get_clean();
        $this->assertEquals('<meta name="twitter:card" content="summary"/>', $output);
    }

    public function testHtmlSummaryLarge()
    {
        $s = new BlockMetaTwitterCardSummary(BlockMetaTwitterCardSummary::thisPage());
        $s->setLargeImage(true);

        ob_start();
        $s->html();

        $output = ob_get_clean();
        $this->assertEquals('<meta name="twitter:card" content="summary_large_image"/>', $output);
    }
}
