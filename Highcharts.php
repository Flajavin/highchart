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

class Highcharts extends Widget {

    protected static $counter = 0;

    /**
     * Container ID;
     * @var string
     */
    public $id;

    /**
     * List of modules to load from highcharts folder %ASSETs%/js/modules/{name}.js
     * @var array
     */
    public $modules = [];

    /**
     * Chart options
     * @var string[]
     */
    public $chart;

    /**
     * Html options for the container.
     * @var string[]
     */
    public $htmlOptions;

    public $title;
    public $subtitle;
    public $xAxis;
    public $yAxis;
    public $tooltip;
    public $legend;
    public $series;

    /**
     * Any extra options from Highchart that is not covered in the above params;
     * @var string[]
     */
    public $options = [];

    /**
     * Setup options that are called before a new highchart
     * @var string[]
     */
    public $setupOptions = [];

    /**
     * If you want to manually init the chart later then fill the name of the method to be created here. Then you can call this method when
     * you want to init the chart.
     * @var string
     */
    public $callback;

    /**
     * Constructor from Highcharts js class. Default is "Chart"
     * @var string
     */
    protected $constr = 'Chart';

    /**
     * Process dev options;
     * @param array $config
     * @return null
     * @throws \Exception
     */
    protected function init($config = []) {
        if (!$this->id) {
            if (isset($this->htmlOptions['id'])) {
                $this->id = $this->htmlOptions['id'];
            } else {
                $this->id = "highchart" . (self::$counter++);
            }
        }
        $this->htmlOptions['id'] = $this->id;
        foreach (['chart', 'title', 'subtitle', 'xAxis', 'yAxis', 'tooltip', 'legend', 'series'] as $option) {
            if ($this->$option) { // if any of the options in the list above are set then it will add them to options array
                $this->options[$option] = $this->$option;
            }
        }
        $this->options['chart']['renderTo'] = $this->id;
        return parent::init($config);
    }

    /**
     * Will display assets, tag and js code. If you want to manually do any of this steps you can call assets() or jsCode()
     * methods separated and ignore this method.
     * @return string
     */
    public function display() {
        return $this->assets()
        . Html::get()->tag("div", "", $this->htmlOptions)
        . $this->jsCode();
    }

    /**
     * @return string
     */
    public function jsCode() {
        $setup = json_encode($this->setupOptions);
        $options = json_encode($this->options);
        $js = "Highcharts.setOptions($setup); new Highcharts.{$this->constr}($options);";
        return Html::get()->script($this->callback ? ("function {$this->callback}(data) {$js}") : $js);
    }

    public function assets() {
        $assetsURL = AssetsPublisher::get()->publishFolder(__DIR__ . DIRECTORY_SEPARATOR . 'assets');
        $r = Html::get()->mpfScriptFile('jquery.js')
            . Html::get()->scriptFile($assetsURL . "js/highcharts.js");
        foreach ($this->modules as $module) {
            $r .= Html::get()->scriptFile($assetsURL . "js/$module.js");
        }
        return $r;
    }

} 
