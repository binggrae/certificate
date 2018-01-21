<?php


namespace app\imagine;

use app\models\Block;
use Imagine\Gd\Imagine;
use Imagine\Image\Palette\Color;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

class Image
{

    /** @var Imagine */
    private $imagine;

    private $canvas;

    private $palette;


    public function __construct($background)
    {
        $this->imagine = new Imagine();
        $image = $this->imagine->open($background);

        $this->palette = new RGB();

        $canvasColor = $this->palette->color('#fff');

        $topLeft = new Point(0, 0);

        $this->canvas = $this->imagine->create($image->getSize(), $canvasColor);
        $this->canvas->paste($image, $topLeft);
    }


    /**
     * @param $text
     * @param Block $block
     */
    public function drawText($text, $block)
    {
        $rate = $block->template->rate;

        $color = $this->palette->color($block->color);
        $path = \Yii::getAlias('@webroot/fonts/' . $block->font->ttf);
        $font = $this->imagine->font($path, $block->font_size * $rate, $color);

        $box = imagettfbbox($font->getSize(), 0, $font->getFile(), $text);

        $posX = ($block->posX * $rate + ($block->width * $rate - $box['2']) / 2);
        $posY = ($block->posY - 2) * $rate;



        $pos = new Point($posX, $posY);
        $this->canvas->draw()->text($text, $font, $pos, 0);
    }

    public function show($format = 'jpg')
    {
        return $this->canvas->show($format);
    }

    public function save($path)
    {
        return $this->canvas->save($path);
    }


}