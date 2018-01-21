<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cert_values".
 *
 * @property int $cert_id
 * @property int $block_id
 * @property string $value
 *
 * @property Block $block
 * @property Certificate $cert
 */
class CertValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cert_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_id'], 'required'],
            [['block_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['block_id'], 'exist', 'skipOnError' => true, 'targetClass' => Block::className(), 'targetAttribute' => ['block_id' => 'id']],
            [['cert_id'], 'exist', 'skipOnError' => true, 'targetClass' => Certificate::className(), 'targetAttribute' => ['cert_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cert_id' => 'Cert ID',
            'block_id' => 'Block ID',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlock()
    {
        return $this->hasOne(Block::className(), ['id' => 'block_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCert()
    {
        return $this->hasOne(Certificate::className(), ['id' => 'cert_id']);
    }
}
