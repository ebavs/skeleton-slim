<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 07/09/16
 * Time: 15:39
 */

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController extends BaseController
{

    public function __invoke(Request $request, Response $response, $args)
    {

        $columns = array(
            'field 1'    => 'field1',
            'field 2'    => 'field2',
            'field 3'    => 'field3',
            'field 4'    => 'field4'
        );

        $this->view->render($response, 'datatable-example.twig', [
            'table_columns'     => $columns
        ]);

    }

    /**
     * this is the home controller method example
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     */
    public function getData(Request $request, Response $response, $args) {

        $order      = [];
        $start      = 0;
        $len        = 0;
        $search     = '';

        extract(
            $this->extractVars(
                $request->getQueryParams()
            )
        );

        /**
         * @var $home \App\Models\HomeModel
         */
        $model      = $this->container['model.home'];
        $home_data  = $model->homeTest();
        $data       = $this->processData( $home_data, $start, count($home_data) );

        return $response->withStatus(200)->withJson($data);



    }

}