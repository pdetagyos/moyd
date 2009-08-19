<?php

class Constants {

	static $ROOT_DIR, $APP_DIR, $CONFIG_DIR, $MODEL_DIR, $VIEW_DIR, $CONTROLLER_DIR, $STATIC_DIR, $INCLUDE_DIR;

}

$currDir = dirname(__FILE__);
Constants::$ROOT_DIR = str_replace(basename($currDir), '', $currDir);
$appDir = Constants::$ROOT_DIR . 'application/';
Constants::$APP_DIR  = $appDir;
Constants::$MODEL_DIR  = $appDir . 'model/';
Constants::$VIEW_DIR  = $appDir . 'view/';
Constants::$CONTROLLER_DIR  = $appDir . 'controller/';
Constants::$STATIC_DIR = $appDir . 'static/';

