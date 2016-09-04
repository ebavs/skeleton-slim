<?php
/**
 * Copyright 2016 1f7@runetcms.ru
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


namespace App\Middleware;

use App\Helpers\TracyDBPanel;
use App\Helpers\TracyTwigpanel;
use Tracy\Debugger;

class TracyDBMiddleware
{
    private $db;
    private $twig;

    public function __construct( $db, $profile )
    {
        $this->db   = &$db;
        $this->twig = &$profile;
    }

    public function __invoke($request, $response, $next)
    {
        $res = $next($request, $response);

        $d = $this->db->getQueryLog();

        // uncomment if you want raw data
        //Debugger::barDump($d, 'DB log');

        Debugger::getBar()->addPanel(new TracyDBPanel($d));
        Debugger::getBar()->addPanel(new TracyTwigpanel($this->twig));

        return $res;
    }
}