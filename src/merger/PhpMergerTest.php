<?php


namespace merger;


class PhpMergerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function verifySimpleMerge()
    {
        $merger = new \merger\PhpMerger();
        $hi = $merger->mergeFiles("<?php class hello {}", "<?php class hello{}");
        $this->assertEquals("class hello\n{\n}", $hi);
    }

}
 