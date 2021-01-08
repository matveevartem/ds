<?php

namespace app\modules\wallet;

use Yii;

/**
 * wallet module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\wallet\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\wallet\commands';
        }
        \Yii::configure($this, require __DIR__ . '/config/main.php');
    }
}
