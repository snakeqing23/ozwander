<?php
/* don't allow this page to be requested directly from browser */	
if (!defined('QA_VERSION')) {
		header('Location: /');
		exit;
}

function qa_page_routing()
{
    $pages = qa_page_routing_base();
    $pages['it_installation'] = '../qa-plugin/Infinity-Plugin/pages/installation.php'; // changed to include a new file instead of default page
    return $pages;
}