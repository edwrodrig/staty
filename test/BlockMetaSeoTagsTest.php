<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 26-05-18
 * Time: 23:06
 */

namespace test\labo86\staty;

use labo86\staty\BlockMetaSeoTags;
use PHPUnit\Framework\TestCase;

class BlockMetaSeoTagsTest extends TestCase
{

    public function testHappy() {
        $s = new BlockMetaSeoTags(BlockMetaSeoTags::thisPage());
        $s->setDescription('hola');

        ob_start();
        $s->html();

        $output = ob_get_clean();
        $this->assertStringContainsString('<meta', $output);
        $this->assertStringContainsString('hola', $output);
    }

    public function testNull() {
        $s = new BlockMetaSeoTags(BlockMetaSeoTags::thisPage());

        ob_start();
        $s->html();

        $output = ob_get_clean();
        $this->assertEmpty($output);
    }
}
