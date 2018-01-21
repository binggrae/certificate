<?php


namespace app\controllers;


use app\imagine\Image;
use app\models\Block;
use app\models\Certificate;
use app\models\CertValue;
use app\models\Template;
use app\models\Type;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CertificateController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index', [
            'model' => Template::find()->orderBy(['id' => SORT_DESC])->all()
        ]);
    }


    /**
     * @param null|int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     * @throws \Exception
     */
    public function actionCreate(int $id = null)
    {
        /** @var Template $template */
        $template = is_null($id) ?
            Template::find()->orderBy(['id' => SORT_DESC])->one() :
            Template::find()->where(['id' => $id])->one();

        $certificate = new Certificate();

        /** @var Type $fieldType */
        $fieldType = Type::find()->select(['id'])->where(['type' => 'field'])->asArray()->all();

        $ids = [];
        foreach ($fieldType as $item) {
            $ids[] = $item['id'];
        }

        /** @var Block[] $fieldBlocks */
        $fieldBlocks = Block::find()
            ->with('type')
            ->andWhere(['template_id' => $template->id])
            ->andWhere(['type_id' => $ids])
            ->all();

        $certValue = new CertValue();

        if ($certificate->load(\Yii::$app->request->post()) && $certificate->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $certificate->save();

                /** @var Block[] $allBlocks */
                $allBlocks = Block::find()
                    ->with('type')
                    ->andWhere(['template_id' => $id])
                    ->all();

                foreach ($allBlocks as $block) {
                    $model = new CertValue();
                    $model->cert_id = $certificate->id;
                    $model->block_id = $block->id;
                    $model->value = $block->getValue($certificate);
                    $model->save();
                }

                $certificate->render();
                $transaction->commit();

                return $this->redirect(['/certificate/view', 'id' => $certificate->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }


        if (!$template) {
            throw new NotFoundHttpException('Сертификат не найден');
        }

        return $this->render('create', [
            'template' => $template,
            'certificate' => $certificate,
            'blocks' => $fieldBlocks,
            'certValue' => $certValue,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        return $this->redirect('/admin/index');
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);

        \Yii::$app->response->format = Response::FORMAT_RAW;
        \Yii::$app->response->setDownloadHeaders($model->id . ".jpg");
        \Yii::$app->response->headers->add('content-type', 'image/png');
        \Yii::$app->response->data = file_get_contents($model->getFullPath());
        return \Yii::$app->response;
    }


    /**
     * @param $id
     * @return Certificate
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        $model = Certificate::findOne(['id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException('Шаблон не найден');
        }

        return $model;
    }

}