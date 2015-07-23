<?php
	require_once( 'php-console-master/src/PhpConsole/__autoload.php' );
	require_once( 'PhpConsole.phar' );

	$connector = PhpConsole\Connector::getInstance();
	$handler = PhpConsole\Handler::getInstance();
	$handler->start();
	PhpConsole\Helper::register();
?>