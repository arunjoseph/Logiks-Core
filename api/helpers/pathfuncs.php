<?php
/*
 * Some Special System Functions for generating paths
 * 
 * Author: Bismay Kumar Mohapatra bismay4u@gmail.com
 * Author: Kshyana Prava kshyana23@gmail.com
 * Version: 1.0
 */
 
if(!defined('ROOT')) exit('No direct script access allowed');
//Some Special System Functions
if(!function_exists("getRelativePath")) {
	function getRelativePath($file,$fullPath=true) {
		$s=str_replace(ROOT,"/",dirname($file) . "/");
		$basepath="";
		$s=str_replace("//","/",$s);
		for($j=0;$j<substr_count($s,"/");$j++) {
			$basepath.="../";
		}
		return $basepath;
	}
	function getWebPath($file) {
		return WEBROOT.dirname(str_replace(ROOT,"",$file))."/".basename($file);
	}
	function getRootPath($file) {
		return ROOT.dirname(str_replace(ROOT,"",$file))."/".basename($file);
	}
	function getBasePath() {
		if(_server('PATH_INFO')) {
			$file=_server('PATH_INFO');
			return getRelativePath($file);
		} else return "";
	}
	function getConfigPath($local=true) {
		if($local && defined("APPROOT")) {
			return APPROOT.APPS_CONFIG_FOLDER;
		} else {
			return ROOT.CFG_FOLDER;
		}
	}
}
?>
