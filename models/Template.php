<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "templates".
 *
 * @property int $id
 * @property string $title
 * @property string $image
 * @property double $rate
 * @property int $width
 * @property int $height
 * @property int $status
 * @property int $main
 *
 * @property Certificate[] $certificates
 */
class Template extends \yii\db\ActiveRecord
{


    const SCENARIO_CREATE = 'create';
    const SCENARIO_DRAFT = 'draft';
    const SCENARIO_STATUS = 'draft';

    /** @var UploadedFile */
    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'imageFile'], 'required', 'on' => self::SCENARIO_CREATE],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['rate'], 'number'],
            [['width', 'height', 'status', 'main'], 'integer'],
            [['title', 'image'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['title', 'image', 'imageFile', 'width', 'height', 'rate'];
        $scenarios[self::SCENARIO_STATUS] = ['status', 'main'];

        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'image' => 'Image',
            'rate' => 'Rate',
            'width' => 'Width',
            'height' => 'Height',
            'status' => 'Status',
            'main' => 'Main',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCertificates()
    {
        return $this->hasMany(Certificate::className(), ['template_id' => 'id']);
    }


    public function upload()
    {
        if ($this->imageFile->saveAs($this->getWebRootPath())) {
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        $this->removeFile();
        parent::afterDelete();
    }

    public function removeFile()
    {
        if ($this->image && file_exists($this->getWebRootPath())) {
            unlink($this->getWebRootPath($this->image));
        }
    }

    public function getWebRootPath($path = null)
    {
        if(!$path) {
            $path = $this->image;
        }
        return Yii::getAlias('@webroot/uploads/' . $path);
    }

    public function getWebPath($path = null)
    {
        if(!$path) {
            $path = $this->image;
        }
        return Yii::getAlias('@web/uploads/' . $path);
    }
}
