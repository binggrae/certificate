<?php


namespace app\models;


class TypeYear extends Type
{

    const TYPE = 'year';

    /**
     * @param Block $block
     * @param Certificate $certificate
     * @return null
     */
    public function getValue($block, $certificate)
    {
        return date('Y', time());
    }


}