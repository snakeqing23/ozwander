<?php
/* Now it uses event to remove file
	
//	Output this header as early as possible
	header('Content-Type: text/plain; charset=utf-8');


//	Ensure no PHP errors are shown in the Ajax response
	@ini_set('display_errors', 0);


//	Load the Q2A base file which sets up a bunch of crucial functions
	require_once '../../qa-include/qa-base.php';
	require_once '../../qa-include/qa-app-users.php';
	qa_report_process_stage('init_ajax');		

//	Get general Ajax parameters from the POST payload, and clear $_GET	
	qa_set_request(qa_post_text('qa_request'), qa_post_text('qa_root'));	
	$output_dir = QA_BLOBS_DIRECTORY."featured/";
	
	//$userid = qa_get_logged_in_userid();
	//var_dump($userid);
	
	if ( (qa_get_logged_in_level() >= QA_USER_LEVEL_MODERATOR) & (isset($_POST['name'])) ){
		$fileName =$_POST['name'];
		$filePath = $output_dir. $fileName;
		if (file_exists($filePath)) 
		{
			unlink($filePath);
		}
		echo "Deleted File ".$fileName;
	}

?>
*/