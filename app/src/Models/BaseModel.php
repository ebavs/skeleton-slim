<?php

/**
 * Created by PhpStorm.
 * User: victor
 * Date: 18/4/16
 * Time: 15:24
 */

namespace App\Models;

use App\helpers\DatabaseWrapper;

abstract class BaseModel
{

    /** @var  $db DatabaseWrapper */
    protected $db;

    public function __construct( DatabaseWrapper $db ) {

        $this->db       = $db;

    }

}