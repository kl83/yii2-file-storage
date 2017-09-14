<?php

use kl83\filestorage\Module;

class ModuleCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function tryToTestFindModuleInstance(UnitTester $I)
    {
        $instance = Module::findInstance();
        $I->assertEquals(Module::className(), $instance::className());
    }
}
