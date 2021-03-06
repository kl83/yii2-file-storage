<?php

namespace kl83\filestorage\console;

use kl83\filestorage\models\File;
use kl83\filestorage\Module;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * File storage
 * @property Module $module
 */
class ThumbController extends Controller
{
    /**
     * Regenerate thumbnails
     * @param string $id Thumbnails config id
     * @param bool $ifExists If true, new thumbnails will not be created
     */
    public function actionRegenerate(string $id, bool $ifExists = true)
    {
        $factory = $this->module->getThumbFactory($id);
        $count = File::find()->count();
        Console::startProgress(0, $count);
        foreach (File::find()->each() as $idx => $file) {
            $thumb = $factory->createThumb($file, false);
            if (!$ifExists || $thumb->isExists()) {
                $thumb->updateThumbnail();
            }
            if ($idx % 99 == 0) {
                Console::updateProgress($idx, $count);
            }
        }
        Console::updateProgress($count, $count);
        Console::endProgress();
    }

    /**
     * Regenerate all thumbnails
     * @param bool $ifExists If true, new thumbnails will not be created
     */
    public function actionRegenerateAll(bool $ifExists = true)
    {
        $factories = $this->module->getAllThumbFactories();
        $count = File::find()->count();
        Console::startProgress(0, $count);
        foreach (File::find()->each() as $idx => $file) {
            foreach ($factories as $factory) {
                $thumb = $factory->createThumb($file, false);
                if (!$ifExists || $thumb->isExists()) {
                    $thumb->updateThumbnail();
                }
            }
            if ($idx % 99 == 0) {
                Console::updateProgress($idx, $count);
            }
        }
        Console::updateProgress($count, $count);
        Console::endProgress();
    }
}
