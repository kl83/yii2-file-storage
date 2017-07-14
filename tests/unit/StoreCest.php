<?php


class StoreCest
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
        $store = Yii::$app->getModule('filestorage')->store;
        $I->assertRegExp("~0/0$~", $store->getUserDir(0));
        $I->assertRegExp("~55/55$~", $store->getUserDir(55));
        $I->assertRegExp("~55/1055$~", $store->getUserDir(1055));
        $I->assertRegExp("~55/1000055$~", $store->getUserDir(1000055));
    }
}
