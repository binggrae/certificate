<?php


namespace app\controllers;


use app\models\Block;
use app\models\Certificate;
use app\models\Template;
use app\models\Type;
use app\models\TypeField;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class AdminController extends Controller
{

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Template::find()->orderBy(['id' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => Certificate::find()->orderBy(['id' => SORT_DESC])->andWhere(['template_id' => $model->id]),
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionStructure($id)
    {
        $types = Type::find()->asArray()->all();
        $blocks = Block::getData($id);

        return Json::encode([
            'blocks' => $blocks,
            'types' => $types,
        ]);
    }


    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if (\Yii::$app->request->isPost) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                foreach (\Yii::$app->request->post('Block') as $data) {
                    $block = null;
                    if (isset($data['uid'])) {
                        $block = Block::findOne(['id' => $data['uid']]);
                    }

                    if (!$block) {
                        $block = new Block();
                    }
                    $block->template_id = $id;
                    $block->type_id = $data['type_id'];
                    $block->posX = (int)$data['posX'];
                    $block->posY = (int)$data['posY'];
                    $block->font_id = (int)$data['font_id'];
                    $block->font_size = (int)$data['font_size'];
                    $block->color = $data['color'];
                    $block->width = (int)$data['width'];

                    $block->save();
                }
                $transaction->commit();
                return $this->redirect(['/admin/update', 'id' => $model->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }

        }


        return $this->render('edit', [
            'model' => $model
        ]);
    }


    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new Template(['scenario' => Template::SCENARIO_CREATE]);

        try {
            if ($model->load(\Yii::$app->request->post())) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->validate()) {
                    $size = getimagesize($model->imageFile->tempName);

                    $model->image = uniqid() . '.' . $model->imageFile->extension;
                    $model->width = $size[0];
                    $model->height = $size[1];
                    $model->rate = $model->width / \Yii::$app->params['preview_with'];

                    if ($model->save() && $model->upload()) {
                        return $this->redirect(['/admin/update', 'id' => $model->id]);
                    }
                }
            }
        } catch (\Exception $e) {
            $model->removeFile();
            throw $e;
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }


    public function actionCreateType()
    {
        if (\Yii::$app->request->isPost) {
            $type = new TypeField();
            $type->title = \Yii::$app->request->post('field');

            if ($type->save()) {
                return Json::encode($type->attributes);
            }
        }
        return Json::encode(null);
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Template
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        $model = Template::findOne(['id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException('Шаблон не найден');
        }

        return $model;

    }


}