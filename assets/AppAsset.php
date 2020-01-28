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
        '/libs/bootstrap/css/bootstrap.min.css',
        '/fonts/line-awesome/css/line-awesome.min.css',
        '/fonts/montserrat/styles.css',
        '/libs/tether/css/tether.min.css',
        '/libs/jscrollpane/jquery.jscrollpane.css',
        '/libs/flag-icon-css/css/flag-icon.min.css',
        '/styles/common.css',
        '/styles/themes/primary.min.css',
        '/styles/themes/sidebar-black.min.css',
        '/fonts/kosmo/styles.css',
        '/fonts/weather/css/weather-icons.min.css',
        '/libs/c3js/c3.min.css',
        '/libs/noty/noty.css',
        '/styles/apps/projects/grid-board.css',
        '/libs/jquery-confirm/jquery-confirm.min.css',
        '/libs/flatpickr/flatpickr.min.css',
        '/styles/widgets/payment.min.css',
        '/styles/widgets/panels.min.css',
        '/styles/dashboard/tabbed-sidebar.min.css',
        '/styles/site.css',
        '/styles/pricing/subscriptions.min.css',
        '/styles/pickmeup.min.css',
        '/styles/chosen.css',
        '/scripts/charts/radial-progress/radial-progress.chart.min.css',
        '/styles/anno.css',
        //'/styles/widgets/payment.min.css',
    ];
    public $js = [
        '/libs/jquery/jquery.min.js',
        '/libs/responsejs/response.min.js',
        '/libs/loading-overlay/loadingoverlay.min.js',
        '/libs/tether/js/tether.min.js',
        '/libs/bootstrap/js/bootstrap.min.js',
        '/libs/jscrollpane/jquery.jscrollpane.min.js',
        '/libs/jscrollpane/jquery.mousewheel.js',
        '/libs/flexibility/flexibility.js',
        '/libs/noty/noty.min.js',
        '/libs/velocity/velocity.min.js',
        '/scripts/common.min.js',
        '/scripts/script.js',
        '/libs/d3/d3.min.js',
        '/libs/c3js/c3.min.js',
        '/libs/noty/noty.min.js',
        '/libs/maplace/maplace.min.js',
        '/libs/jquery-confirm/jquery-confirm.min.js',
        '/libs/jquery/jquery-ui.min.js',
        '/libs/count-up/count-up.min.js',
        '/libs/jquery-form-validator/jquery.form-validator.min.js',
        '/libs/flatpickr/flatpickr.min.js',
        '/scripts/kosmo.widget-controls.min.js',
        '/scripts/jquery.pickmeup.min.js',
        '/scripts/chosen.jquery.min.js',
        '/scripts/jquery.mark.js',
        'https://maps.google.com/maps/api/js?libraries=geometry&v=3.26&key=AIzaSyBBjLDxcCjc4s9ngpR11uwBWXRhyp3KPYM',
        'https://code.highcharts.com/highcharts.js',
        '/scripts/tinymce/tiny_mce/tiny_mce.js',
        '/scripts/tinymce_config.v1.js',
        '/scripts/anno.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
