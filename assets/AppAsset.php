<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'web/libs/bootstrap/css/bootstrap.min.css',
        'web/fonts/line-awesome/css/line-awesome.min.css',
        'web/fonts/montserrat/styles.css',
        'web/libs/tether/css/tether.min.css',
        'web/libs/jscrollpane/jquery.jscrollpane.css',
        'web/libs/flag-icon-css/css/flag-icon.min.css',
        'web/styles/common.css',
        'web/styles/themes/primary.min.css',
        'web/styles/themes/sidebar-black.min.css',
        'web/fonts/kosmo/styles.css',
        'web/fonts/weather/css/weather-icons.min.css',
        'web/libs/c3js/c3.min.css',
        'web/libs/noty/noty.css',
        'web/libs/jquery-confirm/jquery-confirm.min.css',
        'web/styles/widgets/payment.min.css',
        'web/styles/widgets/panels.min.css',
        'web/styles/dashboard/tabbed-sidebar.min.css',
        'web/styles/site.css',
        //'web/styles/widgets/payment.min.css',
    ];
    public $js = [
        'web/libs/jquery/jquery.min.js',
        'web/libs/responsejs/response.min.js',
        'web/libs/loading-overlay/loadingoverlay.min.js',
        'web/libs/tether/js/tether.min.js',
        'web/libs/bootstrap/js/bootstrap.min.js',
        'web/libs/jscrollpane/jquery.jscrollpane.min.js',
        'web/libs/jscrollpane/jquery.mousewheel.js',
        'web/libs/flexibility/flexibility.js',
        'web/libs/noty/noty.min.js',
        'web/libs/velocity/velocity.min.js',
        'web/scripts/common.min.js',
        'web/scripts/script.js',
        'web/libs/d3/d3.min.js',
        'web/libs/c3js/c3.min.js',
        'web/libs/noty/noty.min.js',
        'web/libs/maplace/maplace.min.js',
        'web/libs/jquery-confirm/jquery-confirm.min.js',
        'https://maps.google.com/maps/api/js?libraries=geometry&v=3.26&key=AIzaSyBBjLDxcCjc4s9ngpR11uwBWXRhyp3KPYM',
        'https://code.highcharts.com/highcharts.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
