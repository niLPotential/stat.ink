<?php

declare(strict_types=1);

use app\components\widgets\Icon;
use app\models\Battle3;
use yii\base\Model;
use yii\helpers\Html;

$render = function (?Model $model, string $catalog): string {
  if (!$model) {
    return Html::encode('?');
  }

  return Html::encode(Yii::t($catalog, $model->name));
};

return [
  'label' => Yii::t('app', 'Mode'),
  'format' => 'raw',
  'value' => function (Battle3 $model) use ($render): ?string {
    $rule = $model->rule;
    $lobby = $model->lobby;
    if (!$rule && !$lobby) {
      return null;
    }

    return trim(
      vsprintf('%s %s', [
        ($rule && $lobby)
          ? Html::a(
            Icon::search(),
            ['/show-v3/user',
              'screen_name' => $model->user->screen_name,
              'f' => [
                'lobby' => $lobby->key,
                'rule' => $rule->key,
              ],
            ],
          )
          : '',
        vsprintf('%s - %s', [
          $render($rule, 'app-rule3'),
          $render($lobby, 'app-lobby3'),
        ]),
      ]),
    );
  },
];
