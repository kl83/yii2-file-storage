<?php


class PicSetWidgetCest
{
    const WAIT = 1;

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTestAddPhotoToNewPicsetAndCheckPhotoExists(AcceptanceTester $I)
    {
        $I->amOnPage('/site/new-pic-set');

        $I->dontSeeElement(['css' => "div.item"]);
        $I->attachFile(['css' => '#w1-file'], 'alien.jpg');
        $I->wait(self::WAIT);
        $I->seeElement(['css' => "div.item"]);

        $style = $I->grabAttributeFrom(['css' => 'div.item .image'], 'style');
        preg_match('~(/uploads.*jpg)~', $style, $m);
        $I->assertNotEmpty($m[1]);
        $I->amOnPage($m[1]);
        $I->dontSee('404');
    }

    public function tryToTestAddPhotoToNewPicsetAndCheckPicsetExists(AcceptanceTester $I)
    {
        $I->amOnPage('/site/new-pic-set');

        $I->dontSeeElement(['css' => "div.item"]);
        $I->attachFile(['css' => '#w1-file'], 'alien.jpg');
        $I->wait(self::WAIT);
        $I->seeElement(['css' => "div.item"]);
        $fileId1 = $I->grabAttributeFrom(['css' => 'div.item'], 'data-id');

        $fieldSetId = $I->grabValueFrom(['css' => '#fileset-id']);
        $I->amOnPage("/site/pic-set?id=".$fieldSetId);
        $fileId2 = $I->grabAttributeFrom(['css' => 'div.item'], 'data-id');

        $I->assertEquals($fileId1, $fileId2);
    }
}
