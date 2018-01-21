<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "types".
 *
 * @property int $id
 * @property string $type
 * @property string $title
 *
 * @property Block[] $blocks
 */
class Type extends \yii\db\ActiveRecord
{
    const TYPE = null;

    public function init()
    {
        $this->type = static::TYPE;
        parent::init();
    }

    public static function find()
    {
        return new TypeQuery(get_called_class());
    }

    public static function instantiate($row)
    {
        switch ($row['type']) {
            case 'id':
                return new TypeId();
            case 'field':
                return new TypeField();
            case 'day':
                return new TypeDay();
            case 'month':
                return new TypeMonth();
            case 'year':
                return new TypeYear();
            case 'date':
                return new TypeDate();

            default:
                return new Type();
        }

    }

    /**
     * @param Block $block
     * @param Certificate $certificate
     * @return null
     */
    public function getValue($block, $certificate)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'title'], 'required'],
            [['type', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlocks()
    {
        return $this->hasMany(Block::className(), ['type_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->type = static::TYPE;
        return parent::beforeSave($insert);
    }
}


class TypeQuery extends ActiveQuery
{
    public $type;

    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['type' => $this->type]);

        }
        return parent::prepare($builder);
    }

}
