<?php

use DecodeLabs\Collections\Tree\NativeMutable;

class TestTree
{
    public function test(): void
    {
        /**
         * @var NativeMutable<string> $tree
         */
        $tree = new NativeMutable();

        $tree->test = 'hello';
    }
}
