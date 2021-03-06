<?php
/*
 * This file initializes the system into a running system by configuring
 * all the parameters and including the basic required files.
 *
 * Author: Bismay Kumar Mohapatra bismay4u@gmail.com
 * Author: Kshyana Prava kshyana23@gmail.com
 * Version: 1.2
 */

if(!defined('ROOT')) exit('No direct script access allowed');

//Check Installation
$bpath=dirname(dirname(__FILE__));
if(!file_exists("$bpath/config/basic.cfg")) {
	if(file_exists("$bpath/install/")) {
		echo "Initiating Installation Sequence ...";
		header("Location:install/");
	} else {
		echo "<h1 align=center style='color:#BF2E11'>Error In Logiks Installation Or Corrupt Installation. <br/>Please Contact Admin.</h1>";
	}
	exit();
}
if(!is_writable("$bpath/tmp")) {
	echo "<h1 align=center style='color:#BF2E11'>Error In Logiks TMP Directory, its not writable.</h1>";
	exit();
}

if(!isset($initialized)) {
	ob_start();
	//clearstatcache();
	@session_start();

	include_once ('commons.php');
	
	_envData("SESSION",'REQUEST_PAGE_START',microtime(true));

	if(!defined("InstallFolder")) define('InstallFolder',basename(dirname(__DIR__))."/");

	if(!defined("WEBROOT")) define ('WEBROOT', 'http' . (_server('HTTPS') ? 's' : '') . '://' . 
			_server('HTTP_HOST').dirname(_server('SCRIPT_NAME'))."/");
	if(!defined("SiteLocation")) define ('SiteLocation', 'http' . (_server('HTTPS') ? 's' : '') . '://' . 
			_server('HTTP_HOST')."/".InstallFolder);
	
	include_once ROOT. "config/classpath.php";

	require_once ('bootlogiks.php');

	logiksRequestPreboot();

	require_once ('configurator.php');

	loadConfigs([
			ROOT . "config/basic.cfg",
			ROOT . "config/php.cfg",
			ROOT . "config/system.cfg",
			ROOT . "config/developer.cfg",
			ROOT . "config/errorlog.cfg",
			ROOT . "config/security.cfg",
			ROOT . "config/folders.cfg",
			ROOT . "config/others.cfg",
			ROOT . "config/xtras.cfg",
			ROOT . "config/framework.cfg",
			ROOT . "config/appPage.cfg",
		]);

	if(PRINT_PHP_HEADERS) header("X-Powered-By: ".Framework_Title." [".Framework_Site."]",false);

	require_once ROOT. "api/libs/errorLogs/boot.php";

	LogiksConfig::fixPHPINIConfigs();

	logiksRequestBoot();

	include_once ROOT. "api/libs/logiksCache/boot.php";
	include_once ROOT. "api/libs/loaders/boot.php";
	include_once ROOT. "api/system.php";
	include_once ROOT. "api/security.php";
	include_once ROOT. "api/app.php";

	include_once ROOT. "api/libs/logiksUser/boot.php";
	include_once ROOT. "api/libs/logiksTemplate/boot.php";

	include_once ROOT. "api/libs/logiksPages/boot.php";

	loadHelpers(array("urltools","hooks","mobility","outputbuffer","shortfuncs"));

	$initialized=true;
	runHooks("postinit");

	_envData("SESSION",'SESS_ACTIVE_SITE',SITENAME);
	
	if(!defined("APPS_NAME")) define("APPS_NAME",getConfig("APPS_NAME"));
	if(!defined("APPS_VERS")) define("APPS_VERS",getConfig("APPS_VERS"));
}
?>
