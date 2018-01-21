<?php


namespace app\models;


class TypeDay extends Type
{

    const TYPE = 'day';

    /**
     * @param Block $block
     * @param Certificate $certificate
     * @return null
     */
    public function getValue($block, $certificate)
    {
        return date('d', time());
    }


}