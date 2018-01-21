<?php


namespace app\models;


class TypeDate extends Type
{

    const TYPE = 'date';

    /**
     * @param Block $block
     * @param Certificate $certificate
     * @return null
     * @throws \yii\base\InvalidConfigException
     */
    public function getValue($block, $certificate)
    {
        return \Yii::$app->formatter->asDate(time(), 'long');
    }


}