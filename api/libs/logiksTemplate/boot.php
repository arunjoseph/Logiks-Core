<?php
/*
 * This bootstraps the template system.
 *
 * Author: Bismay Kumar Mohapatra bismay4u@gmail.com
 * Version: 2.0
 */
if(!defined('ROOT')) exit('No direct script access allowed');

include_once dirname(__FILE__)."/engines/AbstractTemplateEngine.inc";
include_once dirname(__FILE__)."/LogiksTemplate.inc";

if(!function_exists("_template")) {
	function _template($file,$dataArr=null,$sqlQuerySet=null) {
		//$file=str_replace(".","/",$file);
		if(strtolower(strstr($file,"."))!=".tpl") {
			$file.=".tpl";
		}
		if(!file_exists($file)) {
			if(file_exists(APPROOT.TEMPLATE_FOLDER.$file)) {
				$file=APPROOT.TEMPLATE_FOLDER.$file;
			} elseif(file_exists(ROOT.TEMPLATE_FOLDER.$file)) {
				$file=ROOT.TEMPLATE_FOLDER.$file;
			} else {
				return false;
			}
		}
		$ext=explode(".", $file);
		$ext=$ext[count($ext)-1];
		$ext=".{$ext}";
		$engine=LogiksTemplate::getEngineForExtension($ext);

		$lt=new LogiksTemplate($engine);
		$lt->loadSQL($sqlQuerySet);
		if(MASTER_DEBUG_MODE)
			$lt->printTemplate($file,$dataArr,true);
		else
			$lt->printTemplate($file,$dataArr);
	}

	function _templateData($templateData,$dataArr=null,$sqlData="",$editable=true) {
		if($dataArr==null) {
			$dataArr=array();
			$dataArr["date"]=date(getConfig("PHP_DATE_FORMAT"));
			$dataArr["time"]=date(getConfig("TIME_FORMAT"));
			$dataArr["datetime"]=date(getConfig("PHP_DATE_FORMAT")." ".getConfig("TIME_FORMAT"));

			$dataArr["site"]=SITENAME;
			if(isset($_REQUEST["page"])) $dataArr["page"]=$_REQUEST["page"]; else $dataArr["page"]="home";

			if(isset($_SESSION["SESS_USER_ID"])) $dataArr["user"]=$_SESSION["SESS_USER_ID"]; else $dataArr["user"]="Guest";
			if(isset($_SESSION["SESS_PRIVILEGE_ID"])) $dataArr["privilege"]=$_SESSION["SESS_PRIVILEGE_ID"];  else $dataArr["privilege"]="Guest";
			if(isset($_SESSION["SESS_USER_NAME"])) $dataArr["username"]=$_SESSION["SESS_USER_NAME"];  else $dataArr["user_name"]="Guest";
			if(isset($_SESSION["SESS_PRIVILEGE_NAME"])) $dataArr["privilegename"]=$_SESSION["SESS_PRIVILEGE_NAME"];  else $dataArr["privilege_name"]="Guest";
		} else {
			if(!isset($dataArr["date"])) $dataArr["date"]=date(getConfig("PHP_DATE_FORMAT"));
			if(!isset($dataArr["time"])) $dataArr["time"]=date(getConfig("TIME_FORMAT"));
			if(!isset($dataArr["datetime"])) $dataArr["datetime"]=date(getConfig("PHP_DATE_FORMAT")." ".getConfig("TIME_FORMAT"));

			if(!isset($dataArr["site"])) $dataArr["site"]=SITENAME;
			if(!isset($dataArr["page"])) {
				if(isset($_REQUEST["page"])) $dataArr["page"]=$_REQUEST["page"]; else $dataArr["page"]="home";
			}
			if(!isset($dataArr["user"])) {
				if(isset($_SESSION["SESS_USER_ID"])) $dataArr["user"]=$_SESSION["SESS_USER_ID"]; else $dataArr["user"]="Guest";
			}
			if(!isset($dataArr["privilege"])) {
				if(isset($_SESSION["SESS_PRIVILEGE_ID"])) $dataArr["privilege"]=$_SESSION["SESS_PRIVILEGE_ID"];  else $dataArr["privilege"]="Guest";
			}
			if(!isset($dataArr["username"])) {
				if(isset($_SESSION["SESS_USER_NAME"])) $dataArr["username"]=$_SESSION["SESS_USER_NAME"];  else $dataArr["user_name"]="Guest";
			}
			if(!isset($dataArr["privilegename"])) {
				if(isset($_SESSION["SESS_PRIVILEGE_NAME"])) $dataArr["privilegename"]=$_SESSION["SESS_PRIVILEGE_NAME"];  else $dataArr["privilege_name"]="Guest";
			}
		}

		$body=TemplateEngine::processTemplate($templateData,$dataArr,$editable);

		return $body;
	}
}
?>
