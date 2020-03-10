<?php

namespace app\controllers;

use Yii;
use app\models\Employee;
use app\models\EmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Employee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'employees' => $this->getEmployees(),
        ]);
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'employees' => $this->getEmployees($id),
        ]);
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the employees without current employee and his inferiors.
     * @param integer $id
     * @return array
     */
    protected function getEmployees($id = null)
    {
        if (! is_null($id))
        {
            $employee_inferiors = $this->getEmployeeInferiors($id);
            $condition = ['not in', 'id', array_merge($employee_inferiors, [$id])];
        }
        else
        {
            $condition = [];
        }

        $employees = Employee::find()->where($condition)->asArray()->all();

        if ($employees === null)
            return [];

        $employees = ArrayHelper::map(
            $employees,
            'id',
            function($element)
            {
                return $element['name'] . ' ' . $element['surname'] . (! empty($element['position']) ? ' (' . $element['position'] . ')' : '');
            });

        return $employees;
    }

    /**
     * Finds employee inferiors.
     * @param integer $employee_id
     * @return array
     */
    protected function getEmployeeInferiors($employee_id = null)
    {
        $inferiors = [];

        if (is_null($employee_id))
            return $inferiors;

        $employees = Employee::find()->asArray()->all();

        $handle_employees = function(& $array, $id) use(& $inferiors, & $handle_employees)
        {
            $inferiors_exist = false;
            foreach ($array as $key => & $element)
            {
                if ($element['chief_id'] == $id)
                {
                    $inferiors[] = $element['id'];
                    unset($array[$key]);
                    $inferiors_exist = true;
                }
            }
            if ($inferiors_exist)
                foreach ($inferiors as $inferior)
                    $handle_employees($array, $inferior);
        };

        $handle_employees($employees, $employee_id);

        return $inferiors;
    }
}
