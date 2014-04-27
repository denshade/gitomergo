<?php

use merger\ConservativeUseStatementMerger;
use merger\AddedNode;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
class ConservativeUseStatementMergerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function verifyUseStatements()
    {
        $useMerger = new ConservativeUseStatementMerger();
        $useMerger->merge([
            new AddedNode(new UseUse(new FullyQualified("\\DateTime")))
        ],[
            new AddedNode(new UseUse(new FullyQualified("\\DateTimeZone")))
        ]);

    }
}
 