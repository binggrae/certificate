<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "blocks".
 *
 * @property int $id
 * @property int $template_id
 * @property int $type_id
 * @property int $posX
 * @property int $posY
 * @property int $font_id
 * @property int $font_size
 * @property int $width
 * @property string $color
 *
 * @property Font $font
 * @property Template $template
 * @property Type $type
 * @property CertValue[] $certValues
 */
class Block extends \yii\db\ActiveRecord
{
    public $uid;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blocks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'type_id', 'posX', 'posY', 'font_id', 'font_size', 'color', 'width'], 'required'],
            [['template_id', 'type_id', 'posX', 'posY', 'font_id', 'font_size', 'width'], 'integer'],
            [['color'], 'string', 'max' => 255],
            [['font_id'], 'exist', 'skipOnError' => true, 'targetClass' => Font::className(), 'targetAttribute' => ['font_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Type::className(), 'targetAttribute' => ['type_id' => 'id']],
            [['template_id'], 'exist', 'skipOnError' => true, 'targetClass' => Template::className(), 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_id' => 'Type ID',
            'type_id' => 'Type ID',
            'posX' => 'Pos X',
            'posY' => 'Pos Y',
            'font_id' => 'Font ID',
            'font_size' => 'Font Size',
            'color' => 'Color',
            'width' => 'Width',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFont()
    {
        return $this->hasOne(Font::className(), ['id' => 'font_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCertValues()
    {
        return $this->hasMany(CertValue::className(), ['block_id' => 'id']);
    }

    public static function getData($id)
    {
        /** @var Block[] $models */
        $models = Block::find()->all();

        $return = [];
        foreach ($models as $model) {
            $return[] = [
                'uid' => $model->id,
                'typeId' => $model->type_id,
                'templateId' => $model->template_id,
                'posX' => $model->posX,
                'posY' => $model->posY,
                'fontId' => $model->font_id,
                'fontSize' => $model->font_size,
                'color' => $model->color,
                'width' => $model->width,
            ];
        }
        return $return;
    }


    /**
     * @param Certificate $certificate
     * @return int
     */
    public function getValue($certificate)
    {
        return $this->type->getValue($this, $certificate);
    }

}
