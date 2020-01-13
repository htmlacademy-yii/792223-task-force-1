<?php

namespace frontend\controllers;

use Carbon\Carbon;
use yii\web\Controller;
use frontend\models\Task;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
                     ->with(['category', 'location'])
                     ->where(['status' => 'new'])
                     ->andWhere(['>', 'expired_at', Carbon::now('UTC')->toDateTimeString()])
                     ->orderBy(['created_at' => SORT_DESC])
                     ->all();

        return $this->render('browse', ['tasks' => $tasks]);
    }
}
