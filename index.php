<?php
/**
 * Site Initialization
 *
 * @author Christopher Troup <chris@norex.ca>
 * @package CMS
 * @subpackage Core
 * @version 2.0
 */
include_once 'include/Debugger.php';
error_reporting(E_ALL);
$debugger = Debugger::instance();
$oldErrorHandler = set_error_handler(array (&$debugger, 'errorHandler'), E_ALL);
$debugger->debug("first call");

/*
 * Kicks things off with initiliziation of the general framework infrastructure.
 */
include_once 'include/Site.php';

error_reporting(E_ALL);
/*
 * Assess whether we are logging in on this page request.
 */
if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
	$auth_container = new CMSAuthContainer();
	$auth = new Auth($auth_container, null, 'authInlineHTML');
	$auth->start();
}


if (@!isset($_REQUEST['module'])) {
	$options = Config::singleton()->options;
	$_REQUEST['module'] = 'Content';
	$_REQUEST['page'] = $options['defaultPage'];
}


require_once 'HTML/AJAX/Helper.php';
$ajaxHelper = new HTML_AJAX_Helper ( );

if ( $ajaxHelper->isAJAX () ){
	echo Module::factory($_REQUEST['module'], $smarty)->getUserInterface($_REQUEST);
} else {
	//$smarty->addJS('/AJAX/server.php?client=all');

	//$smarty->addJS('/js/login.js');
	$smarty->addJS('/js/scriptaculous.js');
	$smarty->addJS('/modules/Menu/js/sfMenus.js');
	//$smarty->addJS('/js/frontend.js');
	
	$smarty->content[$_REQUEST['module']] = Module::factory($_REQUEST['module'], $smarty)->getUserInterface($_REQUEST);
	$smarty->assign ( 'module', $_REQUEST['module'] );
	if (isset($_SESSION['authenticated_user'])) {
		$smarty->assign ( 'user', $_SESSION['authenticated_user'] );
	}
	$smarty->render ( 'db:site.tpl' );
}
?>