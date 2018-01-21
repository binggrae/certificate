<?php


namespace app\models;


class TypeMonth extends Type
{

    const TYPE = 'month';

    /**
     * @param Block $block
     * @param Certificate $certificate
     * @return null
     * @throws \yii\base\InvalidConfigException
     */
    public function getValue($block, $certificate)
    {
        return \Yii::$app->formatter->asDate(time(), 'MMMM');
    }


}