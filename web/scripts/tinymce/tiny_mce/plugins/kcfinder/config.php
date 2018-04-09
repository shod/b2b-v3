<?php

/** This file is part of KCFinder project
  *
  *      @desc Base configuration file
  *   @package KCFinder
  *   @version 2.52-dev
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

// IMPORTANT!!! Do not remove uncommented settings in this file even if
// you are using session configuration.
// See http://kcfinder.sunhater.com/install for setting descriptions

$vs = new Vitalis();
$aid = $vs->session()->get('aid');
$bv = Config::get_base_vars();

if(!isset($aid)) {
   exit();
}

$_CONFIG = array(

// GENERAL SETTINGS

    'disabled' => false,
    'theme' 	=> "oxygen",
    'uploadURL' => $bv['MAIN_STATIC_WEB']."/img",
    'uploadDir' => $_SERVER['DOCUMENT_ROOT']."/img",

    'types' => array(

    // TinyMCE types
        'work'   =>  "*img swf flv avi mpg mpeg qt mov wmv asf rm",
    ),

// IMAGE SETTINGS

    'imageDriversPriority' => "imagick gmagick gd",
    'jpegQuality' => 100,
    'thumbsDir' => ".thumbs",

    'maxImageWidth' => 0,
    'maxImageHeight' => 0,

    'thumbWidth' => 96,
    'thumbHeight' => 96,

	'defaultThumbnails' => false,
	/*
	'watermark' => array(
    	'file' => "images/pngfile.png",
    	'left' => null,
    	'top' => false
	),
*/
// DISABLE / ENABLE SETTINGS

    'denyZipDownload' => false,
    'denyUpdateCheck' => false,
    'denyExtensionRename' => false,


// PERMISSION SETTINGS

    'dirPerms' => 0755,
    'filePerms' => 0644,

    'access' => array(

        'files' => array(
            'upload' => true,
            'delete' => true,
            'copy'   => true,
            'move'   => true,
            'rename' => true
        ),

        'dirs' => array(
            'create' => true,
            'delete' => true,
            'rename' => true
        )
    ),

    'deniedExts' => "exe com msi bat php phps phtml php3 php4 cgi pl",


// MISC SETTINGS

    'filenameChangeChars' => array(
        ' ' => "_",
        ':' => "."
    ),

    'dirnameChangeChars' => array(
        ' ' => "_",
        ':' => "."
    ),

    'mime_magic' => "",

    'cookieDomain' => "",
    'cookiePath' => "",
    'cookiePrefix' => 'KCFINDER_',


// THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION SETTINGS

    '_check4htaccess' => true,
    '_tinyMCEPath' => "../../../tiny_mce",

    '_sessionVar' => &$_SESSION['KCFINDER'],
);

?>
