<?php
/*
	don't allow this page to be requested directly from browser 
*/
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}

/*
	Omit PHP closing tag to help avoid accidental output
*/