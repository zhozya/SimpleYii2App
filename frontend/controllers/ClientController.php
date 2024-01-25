<?php

namespace frontend\controllers;

use app\models\Client;
use app\models\ClientClubs;
use app\models\ClientFilterForm;
use app\models\Club;
use app\models\ClubFilterForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => [
                                'index',
                                'create',
                                'view',
                                'delete',
                                'update',
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Client models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $filterForm = new ClientFilterForm();
        $filterForm->load($this->request->post());

        $query = Client::find()->where(['deleted_at' => null])->with('clubs');
        if ($filterForm->name) {
            $query->andWhere(['like', 'name', $filterForm->name]);
        }
        if (
            !is_null($filterForm->isMale) &&
            isset($this->request->post('ClientFilterForm')['isMale']) &&
            $this->request->post('ClientFilterForm')['isMale'] !== ''
        ) {
            $query->andWhere(['is_male' => $filterForm->isMale]);
        }
        if ($filterForm->validateDateRange()) {
            $query->andWhere(['between', 'date_of_birth', $filterForm->getDateStart(), $filterForm->getDateEnd()]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filterForm' => $filterForm,
        ]);
    }

    /**
     * Displays a single Client model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $request = $this->request;
        $model = new Client();
        $clubs = Club::findAllActive();

        if ($request->isPost && $model->load($request->post())) {
            try {
                $model->creator_id = Yii::$app->user->id;
                $model->created_at = time();

                $clubsIDs = null;
                if (
                    isset($request->post('Client')['clubs']) &&
                    is_array($request->post('Client')['clubs'])
                ) {
                    $clubsIDs = $request->post('Client')['clubs'];
                }

                if ($model->saveWithClubs($clubsIDs)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (\Throwable $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'clubs' => $clubs,
        ]);
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $request = $this->request;
        $model = $this->findModel($id);
        $clubs = Club::findAllActive();

        if ($request->isPost && $model->load($request->post())) {
            try {
                $model->updater_id = Yii::$app->user->id;
                $model->updated_at = time();

                $clubsIDs = null;
                if (
                    isset($request->post('Client')['clubs']) &&
                    is_array($request->post('Client')['clubs'])
                ) {
                    $clubsIDs = $request->post('Client')['clubs'];
                }

                if ($model->saveWithClubs($clubsIDs)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (\Throwable $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'clubs' => $clubs
        ]);
    }

    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try {
            $model = $this->findModel($id);
            $model->deleter_id = Yii::$app->user->id;
            $model->deleted_at = time();

            if ($model->save()) {
                ClientClubs::deleteAll(['client_id' => $model->primaryKey]);
                Yii::$app->session->setFlash('success', "Клиент '$model->name' удален");
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при удалении клиента '$model->name'");
            }
        } catch (NotFoundHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException("Клиент '$id' не найден");
    }
}
