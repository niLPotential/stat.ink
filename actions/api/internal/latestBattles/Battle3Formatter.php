<?php

/**
 * @copyright Copyright (C) 2015-2022 AIZAWA Hina
 * @license https://github.com/fetus-hina/stat.ink/blob/master/LICENSE MIT
 * @author AIZAWA Hina <hina@fetus.jp>
 */

declare(strict_types=1);

namespace app\actions\api\internal\latestBattles;

use Yii;
use app\assets\GameModeIconsAsset;
use app\assets\Spl3StageAsset;
use app\models\Battle3;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\AssetManager;

use function rawurlencode;
use function sprintf;
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

        if (
            $lobby->key === 'regular' ||
            $rule->key === 'nawabari'
        ) {
            return Yii::t('app-rule3', $rule->name);
        }

        // 長すぎるので断腸の思いでロビーだけに変更
        return Yii::t('app-lobby3', $lobby->name);
        // return \vsprintf('%s %s', [
        //     Yii::t('app-lobby3', $lobby->name),
        //     Yii::t('app-rule3', $rule->name),
        // ]);
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
