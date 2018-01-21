<?php


namespace app\models;


class TypeField extends Type
{

    const TYPE = 'field';

    /**
     * @param Block $block
     * @param Certificate $certificate
     * @return null
     */
    public function getValue($block, $certificate)
    {
        $post = \Yii::$app->request->post('CertValue');
        return isset($post[$block->id]['value']) ? $post[$block->id]['value'] : null;
    }


}