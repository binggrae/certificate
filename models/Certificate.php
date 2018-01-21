<?php

namespace app\models;

use app\imagine\Image;
use Yii;

/**
 * This is the model class for table "certificates".
 *
 * @property int $id
 * @property int $template_id
 * @property int $created_at
 * @property string $email
 *
 * @property CertValue $certValues
 * @property Template $template
 */
class Certificate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'certificates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'email'], 'required'],
            [['template_id', 'created_at'], 'integer'],
            [['email'], 'string', 'max' => 255],
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
            'template_id' => 'Template ID',
            'created_at' => 'Created At',
            'email' => 'Email',
        ];
    }

    public function beforeSave($insert)
    {
        $this->created_at = time();
        return parent::beforeSave($insert);
    }

    public function afterDelete()
    {
        $path = $this->getFullPath();
        if(file_exists($path)) {
            unlink($path);
        }

        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCertValues()
    {
        return $this->hasOne(CertValue::className(), ['cert_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    public function render()
    {
        $cert = new Image($this->template->getWebRootPath());

        /** @var CertValue[] $values */
        $values = CertValue::find()->where(['cert_id' => $this->id])->with('block')->all();

        foreach ($values as $value) {
            $cert->drawText($value->value, $value->block);
        }

        $cert->save($this->getFullPath());
    }

    public function show()
    {
        $cert = new Image($this->template->getWebRootPath());

        /** @var CertValue[] $values */
        $values = CertValue::find()->where(['cert_id' => $this->id])->with('block')->all();

        foreach ($values as $value) {
            $cert->drawText($value->value, $value->block);
        }

        $cert->show();
    }


    public function getPath()
    {
        $name = md5($this->id);
        return '/certificates/' . substr($name, 0, 2) . '/' . substr($name, 2, 2) . '/' . $name . '.jpg';
    }

    public function getFullPath()
    {
        $name = md5($this->id);
        $path = Yii::getAlias('@webroot/certificates/' . substr($name, 0, 2));
        if (!file_exists($path)) {
            mkdir($path);
        }

        $path .= '/' . substr($name, 2, 2);
        if (!file_exists($path)) {
            mkdir($path);
        }

        return $path . '/' . $name . '.jpg';
    }

}
