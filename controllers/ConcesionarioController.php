<?php

namespace app\controllers;

use Codeception\Lib\HelperModule;
use yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Concesionario;
use app\models\FormConcesionario;
use yii\helpers\Url;
use app\models\FormFiltroConcesionario;


    class ConcesionarioController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $form = new FormFiltroConcesionario();
                $search = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $search = Html::encode($form->buscar);
                        $table = Concesionario::find()
                            ->where(['like', 'concesionario', $search])                                                        
                            ->orderBy('id asc');
                        $count = clone $table;
                        $pages = new Pagination([
                            'pageSize' => 20,
                            'totalCount' => $count->count()
                        ]);
                        $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    } else {
                        $form->getErrors();
                    }
                } else {
                    $table = Concesionario::find()                        
                        ->orderBy('id asc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
                }
                return $this->render('index', [
                    'model' => $model,
                    'form' => $form,
                    'search' => $search,
                    'pagination' => $pages,

                ]);
            }else{
                return $this->redirect(["site/login"]);
            }

        }

        public function actionNuevo()
        {
            $model = new FormConcesionario;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = new Concesionario;
                    $table->concesionario = $model->concesionario;
                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        //$model->proceso = null;
                    } else {
                        $msg = "error";
                    }
                } else {
                    $model->getErrors();
                }
            }

            return $this->render('nuevo', ['model' => $model, 'msg' => $msg, 'tipomsg' => $tipomsg]);
        }

        public function actionEditar()
        {
            $model = new FormConcesionario;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = Concesionario::find()->where(['id' => $model->id])->one();
                    if ($table) {
                        $table->id = $model->id;
                        $table->concesionario = $model->concesionario;
                        if ($table->update()) {
                            $msg = "El registro ha sido actualizado correctamente";
                        } else {
                            $msg = "El registro no sufrio ningun cambio";
                            $tipomsg = "danger";
                        }
                    } else {
                        $msg = "El registro seleccionado no ha sido encontrado";
                        $tipomsg = "danger";
                    }
                } else {
                    $model->getErrors();
                }
            }

            if (Yii::$app->request->get("id")) {
                $id_concesionario = Html::encode($_GET["id"]);
                $table = Concesionario::find()->where(['id' => $id_concesionario])->one();
                if ($table) {
                    $model->id = $table->id;
                    $model->concesionario = $table->concesionario;
                } else {
                    return $this->redirect(["concesionario/index"]);
                }
            } else {
                return $this->redirect(["concesionario/index"]);
            }
            return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
        }
        
        public function actionEliminar($id) {
        if (Yii::$app->request->post()) {
            $concesionario = Concesionario::findOne($id);
            if ((int) $id) {
                try {
                    Concesionario::deleteAll("id=:id", [":id" => $id]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
                    $this->redirect(["concesionario/index"]);
                } catch (IntegrityException $e) {
                    $this->redirect(["concesionario/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el concesionario ' . $concesionario->id . ' tiene registros asociados');
                } catch (\Exception $e) {

                    $this->redirect(["concesionario/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el concesionario ' . $concesionario->id . ' tiene registros asociados');
                }
            } else {
                // echo "Ha ocurrido un error al eliminar el cliente, redireccionando ...";
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("concesionario/index") . "'>";
            }
        } else {
            return $this->redirect(["concesionario/index"]);
        }
    }
}