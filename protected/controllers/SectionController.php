<?php
class SectionController extends Controller
{

    public function filters() {
        return array(
            'accessControl',
            'ajaxOnly + update',
            'listLetters + grid',
        );
    }

    /*
     *      Контроль доступа (используется в фильтре AccessControl
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to access 'list' action.
                'actions'=>array('list'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated users to access all actions
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
                'deniedCallback' => array($this,'deniedUrl'),
            ),
        );
    }

    public function actionList($id) {

        Yii::app()->session['section'] = $id;
        $this->redirect(Yii::app()->homeUrl);

    }

    public function actionGrid() {

        $this->adminMenu = array(
            array('label'=>'Добавить',
                'url'=>array('update','id'=>0), 'linkOptions'=>array(
                'ajax' => array(
                    'url'=>$this->createUrl('update',array('id'=>0)),
                    'success'=>'function(r){$("#idUpdate .modal-body").html(r);
                    $("#idUpdate").modal("show");return false;}',
            ))),
        );

        $model = new CActiveDataProvider('Sections');

        $this->render('grid',array(
            'model'=>$model,
        ));
    }


    /**
     * Updates a particular model. AJAX only!
     */

    public function actionUpdate($id)
    {
        if ($id) {
            $model=$this->loadModel($id);
        }
        else {
            $model=new Sections;
        }


        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Sections'])){
            $model->attributes=$_POST['Sections'];
            if($model->save()){
                    echo 'success';
                    Yii::app()->end();
            }
        }

            $this->renderPartial('edit',array('model'=>$model), false, true);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {

        try {
        $this->loadModel($id)->delete(); }
        catch (CDbException $e) {
            Yii::app()->user->setFlash('errorDelete','Имеются связанные записи.');
               }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('grid'));
    }


    public function loadModel($id)
    {
        $model=Sections::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'Указанная страница не найдена.');
        return $model;
    }

    }
    ?>