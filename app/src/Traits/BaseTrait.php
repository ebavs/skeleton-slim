<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 18/5/16
 * Time: 17:40
 */

namespace App\Traits;


trait BaseTrait
{
    /**
     * @var $container \Slim\Container
     */
    protected $container;

    /**
     * @var $view \Slim\Views\Twig
     */
    protected $view;

    public function __construct(\Slim\Container $container)
    {
        $this->container    = $container;
        $this->view         = $container['view'];

    }

    public function __get($name)
    {

        if ( isset( $this->container[$name]) ) {
            return $this->container[$name];
        }

    }
}