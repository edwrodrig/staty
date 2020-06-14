<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 28-05-18
 * Time: 11:52
 */

namespace test\labo86\staty;

use labo86\staty\BlockMetaBusinessContactData;
use PHPUnit\Framework\TestCase;

class BlockMetaBusinessContactDataTest extends TestCase
{

    public function testHtml()
    {
        $s = new BlockMetaBusinessContactData(BlockMetaBusinessContactData::thisPage());
        $s->setWebsite('http://edwin.cl');

        ob_start();
        $s->html();

        $output = ob_get_clean();

        $this->assertEquals('<meta property="business:contact_data:website" content="http://edwin.cl" />', $output);
    }

    public function testHtmlEmpty()
    {
        $s = new BlockMetaBusinessContactData(BlockMetaBusinessContactData::thisPage());

        ob_start();
        $s->html();

        $output = ob_get_clean();

        $this->assertEmpty($output);
    }
}
