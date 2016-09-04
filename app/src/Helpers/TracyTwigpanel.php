<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 22/08/16
 * Time: 15:44
 */
namespace App\Helpers;

use Tracy\IBarPanel;

class TracyTwigpanel implements IBarPanel
{
    private $data;

    private $dumper;

    public function __construct($data=null)
    {
        $this->data = $data;
        $this->dumper = new \Twig_Profiler_Dumper_Html();
    }

    public function getTab()
    {
        return '<span title="Twig Info">&nbsp; Twig &nbsp; </span>';
    }

    public function getPanel()
    {
        return '<h1>Slim 3 / Twig</h1><div class="tracy-inner"><p>
                            <table width="100%"><thead><tr><th><b>Twig profiler result</b></th></tr></thead><tr class="yes"><th><b>'.
                                    $this->dumper->dump($this->data) .'</b></th></tr></table></p></div>';
    }
}