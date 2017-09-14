<?php

use kl83\filestorage\models\File;

class FileCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function tryToTestUserDirGenerator(UnitTester $I)
    {
        $file = new File;
        $I->assertRegExp("~0/0$~", $file->getUserDir(0));
        $I->assertRegExp("~55/55$~", $file->getUserDir(55));
        $I->assertRegExp("~55/1055$~", $file->getUserDir(1055));
        $I->assertRegExp("~55/1000055$~", $file->getUserDir(1000055));
    }
}
