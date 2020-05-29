#if (${NAME} == "")
#set($NAME = "new_method")
#end

public function test_${NAME}_basic() {
    $path = $this->root->url();
    $filename = $path . "/file";
    file_put_contents($filename, "hello world");

    $this->assertFileExists($filename);
    $contents = file_get_contents($filename);

    $this->assertEquals("hello world", $contents);
}