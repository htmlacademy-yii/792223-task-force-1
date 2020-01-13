<?php

namespace common\fixtures\helpers;

use Yii;

class FixturesFKHelper
{
    private $locations;
    private $users;

    private function loadLocations()
    {
        if (!$this->locations) {
            $this->locations = Yii::$app->db
                ->createCommand('SELECT id FROM locations ORDER BY RAND()')
                ->queryAll();
        }
    }

    private function loadUsers()
    {
        if (!$this->users) {
            $this->users = Yii::$app->db
                ->createCommand('SELECT id FROM users ORDER BY RAND()')
                ->queryAll();
        }
    }

    public function getLocationID($unique = false)
    {
        $this->loadLocations();

        if ($unique) {
            return array_pop($this->locations)['id'];
        }

        return $this->locations[array_rand($this->locations)]['id'];
    }

    public function getUserID($unique = false)
    {
        $this->loadUsers();

        if ($unique) {
            return array_pop($this->users)['id'];
        }

        return $this->users[array_rand($this->users)]['id'];
    }
}
