<?php

/**
 * @copyright Copyright (C) 2015-2023 AIZAWA Hina
 * @license https://github.com/fetus-hina/stat.ink/blob/master/LICENSE MIT
 * @author AIZAWA Hina <hina@fetus.jp>
 */

declare(strict_types=1);

namespace app\actions\api\internal\latestBattles;

use Yii;
use app\models\Battle3;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use function rawurlencode;
use function strtotime;
use function vsprintf;

trait Battle3Formatter
{
    use UserFormatter;

    protected function formatBattle3(Battle3 $battle): array
    {
        return [
            'id' => $battle->id,
            'image' => self::image3($battle),
            'isWin' => self::isWin3($battle),
            'mode' => self::mode3($battle),
            'stage' => self::stage3($battle),
            'summary' => self::summary3a($battle),
            'summary2' => self::summary3b($battle),
            'time' => strtotime($battle->end_at ?: $battle->created_at),
            'rule' => self::rule3($battle),
            'url' => self::url3($battle),
            'user' => self::formatUser($battle->user),
            'variant' => 'splatoon3',
        ];
    }

    private static function image3(Battle3 $model): ?string
    {
        if ($model->battleImageResult3) {
            return Url::to(
                Yii::getAlias('@imageurl') . '/' . $model->battleImageResult3->filename,
                true,
            );
        }

        if (ArrayHelper::getValue(Yii::$app->params, 'useS3ImgGen')) {
            $rule = $model->rule;
            if ($rule && $rule->key !== 'tricolor') {
                return vsprintf('https://s3-img-gen.stats.ink/results/en-US/%s.jpg', [
                    rawurlencode($model->uuid),
                ]);
            }
        }

        return null;
    }

    private static function isWin3(Battle3 $model): ?bool
    {
        return ($r = $model->result)
            ? $r->is_win
            : null;
    }

    private static function mode3(Battle3 $model): ?array
    {
        if (!$lobby = $model->lobby) {
            return null;
        }

        return [
            'icon' => null,
            'key' => $lobby->key,
            'name' => Yii::t('app-lobby3', $lobby->name),
        ];
    }

    private static function stage3(Battle3 $model): ?array
    {
        return null;
    }

    private static function summary3a(Battle3 $model): ?string
    {
        $map = $model->map;
        $result = $model->result;
        if (!$map && !$result) {
            return null;
        }

        $mapText = $map ? Yii::t('app-map3', $map->name) : '?';
        $resultText = $result ? Yii::t('app', $result->name) : '?';
        return vsprintf('%s @%s', [
            $resultText,
            $mapText,
        ]);
    }

    private static function summary3b(Battle3 $model): ?string
    {
        $lobby = $model->lobby;
        $rule = $model->rule;
        if (!$lobby || !$rule) {
            return null;
        }

        return match (true) {
            $lobby->key === 'event' && $model->event !== null => Yii::t('db/event3', (string)$model->event?->name),
            $lobby->key === 'regular', $rule->key === 'nawabari' => Yii::t('app-rule3', (string)$rule->name),
            default => Yii::t('app-lobby3', $lobby->name),
        };
    }

    private static function rule3(Battle3 $model): ?array
    {
        if (!$rule = $model->rule) {
            return null;
        }

        return [
            'icon' => null,
            'key' => $rule->key,
            'name' => Yii::t('app-rule3', $rule->name),
        ];
    }

    private static function url3(Battle3 $model): string
    {
        return Url::to(
            ['show-v3/battle',
                'battle' => $model->uuid,
                'screen_name' => $model->user->screen_name,
            ],
            true,
        );
    }
}
