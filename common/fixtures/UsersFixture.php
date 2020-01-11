<?php
namespace common\fixtures;

use frontend\models\User;
use yii\test\ActiveFixture;

class UsersFixture extends ActiveFixture
{
    public $modelClass = User::class;
    //dependencies do not work without template, and Locations are data-only fixture
    //public $depends = ['common\fixtures\LocationFixture'];
}
