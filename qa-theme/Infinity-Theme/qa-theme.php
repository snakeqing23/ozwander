<?php
/*
	don't allow this page to be requested directly from browser 
*/
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}
/*
	Definitions
*/	
	define('MYTHEME_DIR', dirname( __FILE__ ));
	define('MYTHEME_URL',  qa_opt('site_url') . 'qa-theme/' . qa_get_site_theme() . '/');
	
	// set layout cookies
	$layout = qa_opt('it_layout_lists');
	if($layout)
		setcookie('layoutdefault', $layout, time()+86400*3650, '/', QA_COOKIE_DOMAIN);
	else
		setcookie('layoutdefault', 'masonry', time()+86400*3650, '/', QA_COOKIE_DOMAIN);

	require MYTHEME_DIR. '/functions.php';		
	require MYTHEME_DIR. '/qa-layer-base.php';		
	
	if(isset($_REQUEST['qat_ajax_req'])){
		qa_register_layer('/qa-layer-ajax.php', 'QAT Ajax Theme Layer', MYTHEME_DIR , MYTHEME_URL );
		die();
	}

	

/*
	Omit PHP closing tag to help avoid accidental output
*/