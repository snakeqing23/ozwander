<?php

//	Output this header as early as possible
	header('Content-Type: text/plain; charset=utf-8');


//	Ensure no PHP errors are shown in the Ajax response
	//@ini_set('display_errors', 0);


//	Load the Q2A base file which sets up a bunch of crucial functions
	require_once '../../qa-include/qa-base.php';
	qa_report_process_stage('init_ajax');		

//	Get general Ajax parameters from the POST payload, and clear $_GET
	qa_set_request(qa_post_text('qa_request'), qa_post_text('qa_root'));
	
	require_once QA_INCLUDE_DIR.'qa-db-selects.php';
	require_once QA_INCLUDE_DIR.'qa-app-format.php';
	require_once QA_INCLUDE_DIR.'qa-app-users.php';
	require_once QA_INCLUDE_DIR.'qa-app-options.php';
	require_once QA_INCLUDE_DIR.'qa-app-q-list.php';
	//require_once QA_INCLUDE_DIR.'qa-page.php';
	//qa_set_template('qa');
	$pagesize=qa_opt('page_size_home');
	$page_number = (int)$_POST['page'];
	$limit = (int)$pagesize * $page_number;
	$userid=qa_get_logged_in_userid();
	
	list($questions1, $questions2)=qa_db_select_with_pending(
		qa_db_qs_selectspec($userid, 'created', 0, null, null, false, false, $limit),
		qa_db_recent_a_qs_selectspec($userid, 0, null, null, false, false, $limit)
	);
	$questions=qa_any_sort_and_dedupe(array_merge($questions1, $questions2));
	
	array_splice($questions, 0,(int)$pagesize * ($page_number-1) );
	
	$qa_content=it_q_list_page_content(
		$questions, // questions
		$pagesize, // questions per page
		0, // start offset
		null, // total count (null to hide page links)
		'', // title if some questions
		'', // title if no questions
		null, // categories for navigation
		null, // selected category id
		false, // show question counts in category navigation
		'', // prefix for links in category navigation
		null, // prefix for RSS feed paths (null to hide)
		'', // next 3 lines to check end of question list
		//(count($questions)<$pagesize) // suggest what to do next
		//	? qa_html_suggest_ask($categoryid)
		//	: qa_html_suggest_qs_tags(qa_using_tags(), qa_category_path_request($categories, $categoryid)),
		null, // page link params
		null // category nav params
	);

	//echo "QA_AJAX_RESPONSE\n1\n";
	
	
	$themeclass=qa_load_theme_class(qa_get_site_theme(), 'qa', null, null);
	$themeclass->q_list($qa_content["q_list"]);
	die();

function it_q_list_page_content($questions, $pagesize, $start, $count, $sometitle, $nonetitle,
		$navcategories, $categoryid, $categoryqcount, $categorypathprefix, $feedpathprefix, $suggest,
		$pagelinkparams=null, $categoryparams=null, $dummy=null)
	{
		
		require_once QA_INCLUDE_DIR.'qa-app-format.php';
		require_once QA_INCLUDE_DIR.'qa-app-updates.php';
	
		$userid=qa_get_logged_in_userid();
		
		
	//	Chop down to size, get user information for display

		if (isset($pagesize))
			$questions=array_slice($questions, 0, $pagesize);
	
		$usershtml=qa_userids_handles_html(qa_any_get_userids_handles($questions));


		$qa_content['q_list']['form']=array(
			'tags' => 'method="post" action="'.qa_self_html().'"',
			
			'hidden' => array(
				'code' => qa_get_form_security_code('vote'),
			),
		);
		
		$qa_content['q_list']['qs']=array();
		
		if (count($questions)) {
			$qa_content['title']=$sometitle;
		
			$defaults=qa_post_html_defaults('Q');
				
			foreach ($questions as $question)
				$qa_content['q_list']['qs'][]=qa_any_to_q_html_fields($question, $userid, it_cookie_get(),
					$usershtml, null, qa_post_html_options($question, $defaults));

		} else
			$qa_content['title']=$nonetitle;
		
			
		if (isset($count) && isset($pagesize))
			$qa_content['page_links']=qa_html_page_links(qa_request(), $start, $pagesize, $count, qa_opt('pages_prev_next'), $pagelinkparams);
		
			
		return $qa_content;
	}
	function it_cookie_get()
	{
		return isset($_COOKIE['qa_id']) ? qa_gpc_to_string($_COOKIE['qa_id']) : null;
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/