<?php
/**
 * Created by PhpStorm.
 * User: Mirel Mitache
 * Date: 18.10.2015
 * Time: 11:03
 */

namespace mpf\components\highchart;


use mpf\base\Widget;
use mpf\web\AssetsPublisher;
use mpf\web\helpers\Html;

class Highchart extends Widget{

    protected static $counter = 0;

    /**
     * List of modules to load from highcharts folder %ASSETs%/js/modules/{name}.js
     * @var array
     */
    public $modules = [];

    /**
     *
     * @var string[]
     */
    public $char;

    public $title;
    public $subtitle;
    public $xAxis;
    public $yAxis;
    public $tooltip;
    public $legend;
    public $series;


    public function display(){
        $return = $this->assets();



        return $return;
    }

    public function assets(){
        $assetsURL = AssetsPublisher::get()->publishFolder(__DIR__ . DIRECTORY_SEPARATOR . 'assets');
        return Html::get()->mpfScriptFile('jquery.js')
            . Html::get()->scriptFile($assetsURL ."js/highcharts.js");
    }

} 