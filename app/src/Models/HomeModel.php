<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 07/09/16
 * Time: 15:35
 */

namespace App\Models;


final class HomeModel extends BaseModel
{

    public function homeTest () {

        // THIS DON'T WORK, CHANGE
        //$sql = 'SELECT * FROM ...';

        //$var = $this->db->fetchAll($sql, []);

        $var = [
            [
                'field1' => 'value 1',
                'field2' => 'value 2',
                'field3' => 'value 3',
                'field4' => 'value 4'
            ],
            [
                'field1' => 'value 1',
                'field2' => 'value 2',
                'field3' => 'value 3',
                'field4' => 'value 4'
            ],
            [
                'field1' => 'value 1',
                'field2' => 'value 2',
                'field3' => 'value 3',
                'field4' => 'value 4'
            ],
            [
                'field1' => 'value 1',
                'field2' => 'value 2',
                'field3' => 'value 3',
                'field4' => 'value 4'
            ]
        ];

        return $var;

    }
}