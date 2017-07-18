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
        $I->sendPOST('/filestorage/default/upload', [], [
            'attachment' => [
                'name' => 'alien.jpg',
                'type' => 'image/jpeg',
                'error' => UPLOAD_ERR_OK,
                'size' => filesize(codecept_data_dir('alien.jpg')),
                'tmp_name' => codecept_data_dir('alien.jpg'),
            ],
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'attachment' => [
                'id' => 'integer',
                'url' => 'string',
            ],
        ]);
        $filePath = $I->grabDataFromResponseByJsonPath('attachment.url')[0];
        $I->assertFileExists(__DIR__.DIRECTORY_SEPARATOR."..$filePath");
    }

    public function tryToTestPhpFileUpload(FunctionalTester $I)
    {
        $I->sendPOST('/filestorage/default/upload', [], [
            'attachment' => [
                'name' => 'exploit.php',
                'type' => 'text/php',
                'error' => UPLOAD_ERR_OK,
                'size' => filesize(codecept_data_dir('exploit.php')),
                'tmp_name' => codecept_data_dir('exploit.php'),
            ],
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'attachment' => [
                'id' => 'boolean',
            ],
        ]);
        $fileId = $I->grabDataFromResponseByJsonPath('attachment.id')[0];
        $I->assertEquals(false, $fileId);
    }
}
