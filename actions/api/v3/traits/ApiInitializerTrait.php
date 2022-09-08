<?php

/**
 * @copyright Copyright (C) 2015-2022 AIZAWA Hina
 * @license https://github.com/fetus-hina/stat.ink/blob/master/LICENSE MIT
 * @author AIZAWA Hina <hina@fetus.jp>
 */

declare(strict_types=1);

namespace app\actions\api\v3\traits;

use Yii;
use yii\base\Application;
use yii\web\Application as WebApp;
use yii\web\Response;

trait ApiInitializerTrait
{
    protected function apiInit(): void
    {
        $app = Yii::$app;
        if ($app instanceof Application) {
            Yii::$app->language = 'en-US';
            Yii::$app->timeZone = 'Etc/UTC';
        }

        if ($app instanceof WebApp) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }
    }
}
