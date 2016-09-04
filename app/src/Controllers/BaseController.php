<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 18/5/16
 * Time: 17:40
 */

namespace App\Controllers;

use App\Traits\BaseTrait;

abstract class BaseController
{

    use BaseTrait;

    protected function extractVars( $vars ) {

        $start          = 0;
        $len            = 10;

        $qsStart        = (int)$vars["start"];
        $qsLen          = (int)$vars["length"];
        $order          = isset($vars['order'][0]) ? $vars['order'][0] : NULL;
        $search         = isset($vars['search']['value']) && !empty($vars['search']['value']) ? $vars['search']['value'] : NULL;

        if ($qsStart) {
            $start      = $qsStart;
        }

        if ($qsLen) {
            $len        = $qsLen;
        }

        return compact('start', 'len', 'order', 'search');
    }

    protected function processData( $data, $start = 0, $count = 0 ) {

        $object             = new \queryData();

        $object->start      = $start;
        $object->recordsTotal = $count === 0 ? count($data) : $count;
        $object->recordsFiltered = $count === 0 ? count($data) : $count;
        $object->data       = $data;

        return $object;
    }

}