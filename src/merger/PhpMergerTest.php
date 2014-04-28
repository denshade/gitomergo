<?php


namespace merger;


class PhpMergerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function verifySimpleMerge()
    {
        $merger = new \merger\PhpMerger();
        $hi = $merger->mergeFiles("<?php class hello {}", "<?php class hello{}");
        $this->assertEquals("class hello\n{\n}", $hi);
    }

    /**
     * @test
     */
    public function verifySimpleUseStatementMerge()
    {
        $merger = new PhpMerger();
        $left = '
<?php
namespace hello;

use \DateTime;
class hello
{

}
';

        $right = '
<?php
namespace hello;
use \DateTimeZone;
class hello
{

}
';
        $result = '
<?php
namespace hello;
use \DateTime;
use \DateTimeZone;
class hello
{

}
';

        $hi = $merger->mergeFiles($left, $right);
        $this->assertEquals($result, $hi);
    }
}
 