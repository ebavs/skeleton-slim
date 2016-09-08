Composer Packages in this Skeleton:

    slim/slim
    slim/twig-view
    slim/flash
    monolog/monolog
    robmorgan/phinx
    tuupola/slim-basic-auth
    dompdf/dompdf
    slim/csrf
    tracy/tracy

Install composer packages:

    composer update

Phinx Initialize:

    vendor/bin/phinx init
    
    vendor/bin/phinx create InitialDatabase
    
Phinx migrate database:
 
    vendor/bin/phinx migrate

Nginx configuration for work in subfolder:
    
    location /base-web {
        root /var/sites/base-web;
        rewrite ^/base-web/?(.*)$ /base-web/public/$1 last;
    }

    location /base-web/public {
        try_files $uri $uri/ /base-web/public/index.php$is_args$args;
    }
    
Then change base_path in app/config/settings.php

When You create a Controller or Model, extend for their base because you can access the container:

    final class HomeAction extends BaseAction
    {

        public function __invoke(Request $request, Response $response, $args)
        {
            /**
            * @var $model \App\Models\HomeModel
            */
            $model   = $this->container['model.home'];

            $data    = $model->homeTest();

            $this->view->render($response, 'normal-example.twig', [
                'data'        => $data
            ]);
        }

    }


This is a very earlier approach to Silm Skeleton for our projects.