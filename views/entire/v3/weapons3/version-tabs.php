<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 */

?>
<aside class="mb-3">
  <nav><?= Html::tag(
    'ul',
    implode('', [
      Html::tag(
        'li',
        Html::tag(
          'a',
          Html::encode(Yii::t('app', 'Splatoon 3')),
        ),
        ['class' => 'active'],
      ),
      Html::tag(
        'li',
        Html::a(
          Html::encode(Yii::t('app', 'Splatoon 2')),
          ['entire/weapons2'],
        ),
      ),
      Html::tag(
        'li',
        Html::a(
          Html::encode(Yii::t('app', 'Splatoon')),
          ['entire/weapons'],
        ),
      ),
    ]),
    ['class' => 'nav nav-tabs'],
  ) ?></nav>
</aside>
