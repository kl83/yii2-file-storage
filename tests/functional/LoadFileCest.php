<?php


class LoadFileCest
{
    public function _before(FunctionalTester $I)
    {
        $I->cleanDir('tests/uploads');
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTestJpgFileUpload(FunctionalTester $I)
    {
        $I->sendPOST('/', [], [
            'attachment' => [
                'name' => 'alien.jpg',
                'type' => 'image/jpeg',
                'error' => UPLOAD_ERR_OK,
                'size' => filesize(codecept_data_dir('alien.jpg')),
                'tmp_name' => codecept_data_dir('alien.jpg'),
            ],
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([ 'success' => true ]);
        $I->seeResponseMatchesJsonType([
            'success' => 'boolean',
            'path' => 'string',
            'url' => 'string',
        ]);
        $filePath = $I->grabDataFromResponseByJsonPath('path')[0];
        $I->assertFileExists($filePath);
    }

    public function tryToTestPhpFileUpload(FunctionalTester $I)
    {
        $I->sendPOST('/', [], [
            'attachment' => [
                'name' => 'exploit.php',
                'type' => 'text/php',
                'error' => UPLOAD_ERR_OK,
                'size' => filesize(codecept_data_dir('exploit.php')),
                'tmp_name' => codecept_data_dir('exploit.php'),
            ],
        ]);
        $I->seeResponseIsJson();
        $I->dontSeeResponseContainsJson([ 'success' => true ]);
    }
}
