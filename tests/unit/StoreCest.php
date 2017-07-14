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
        $I->assertRegExp("~0/0$~", Yii::$app->getModule('filestorage')->store->getUserDir(0));
        $I->assertRegExp("~55/55$~", Yii::$app->getModule('filestorage')->store->getUserDir(55));
        $I->assertRegExp("~55/1055$~", Yii::$app->getModule('filestorage')->store->getUserDir(1055));
        $I->assertRegExp("~55/1000055$~", Yii::$app->getModule('filestorage')->store->getUserDir(1000055));
    }
}
