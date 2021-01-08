<?php

namespace app\modules\atm;

use Yii;

/**
 * atm module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\atm\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\atm\commands';
        }
        \Yii::configure($this, require __DIR__ . '/config/main.php');
    }
}
