<?php
/**
 * SwiftMailer Extension to handle email bounces in MediaWiki
 */
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'SwiftMailer',
	'author' => array(
		'Tony Thomas'
	),
	'url' => "https://github.com/tonythomas01/SwiftMailer",
	'description' => 'This extension provides an Alternate mailer for MediaWiki',
	'version'  => '1.0',
	'license-name' => "GPL V2.0",
);

/* Setup*/
$dir = __DIR__ ;

//Hooks files
$wgAutoloadClasses['SwiftMailerHooks'] =  $dir. '/SwiftMailerHooks.php';

//Register Hooks
$wgHooks['AlternateUserMailer'][] = 'SwiftMailerHooks::UseSwiftMailer';

