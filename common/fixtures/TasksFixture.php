<?php
namespace common\fixtures;

use frontend\models\Task;
use yii\test\ActiveFixture;

class TasksFixture extends ActiveFixture
{
    public $modelClass = Task::class;
}
