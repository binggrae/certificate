<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "fonts".
 *
 * @property int $id
 * @property string $name
 * @property string $ttf
 *
 * @property Block[] $blocks
 */
class Font extends \yii\db\ActiveRecord
{

    /** @var UploadedFile */
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fonts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'file'], 'required'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'ttf', 'checkExtensionByMimeType' => false],
            [['name', 'ttf'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'ttf' => 'Шрифт',
            'file' => 'Файл',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlocks()
    {
        return $this->hasMany(Block::className(), ['font_id' => 'id']);
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->ttf = uniqid() . '.' . $this->file->extension;
            $this->file->saveAs(Yii::getAlias('@webroot/fonts/' . $this->ttf));
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        $path = Yii::getAlias('@webroot/fonts/' . $this->ttf);
        if(file_exists($path)) {
            unlink($path);
        }
        parent::afterDelete();
    }
}
