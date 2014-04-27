<?php


namespace merger;


class PhpMergerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function verifySimpleMerge()
    {
        $merger = new PhpMerger();
        $hi = $merger->mergeFiles("<?php class hello {}", "<?php class hello{}");
        $this->assertEquals("class hello\n{\n}", $hi);
    }

    /**
     * @test
     */
    public function verifySimpleUseStatementMerge()
    {
        $merger = new PhpMerger();
        $left = <<<EOF
<?php
namespace hello;

use \DateTime;
class hello
{

}
EOF;

        $right = <<<EOF
<?php
namespace hello;
use \DateTimeZone;
class hello
{

}
EOF;
        $result = <<<EOF
<?php
namespace hello;
use \DateTime;
use \DateTimeZone;
class hello
{

}

EOF;

        $hi = $merger->mergeFiles($left, $right);
        $this->assertEquals($result, $hi);
    }
}
 