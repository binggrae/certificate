<?php


namespace app\models;


class TypeId extends Type
{

    const TYPE = 'id';

    /**
     * @param Block $block
     * @param Certificate $certificate
     * @return null|string
     */
    public function getValue($block, $certificate)
    {
        return (string) $certificate->id;
    }


}