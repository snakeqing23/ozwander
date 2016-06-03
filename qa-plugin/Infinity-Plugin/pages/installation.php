<?php
	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}

?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<style type="text/tps">
			body,input {font-size:16px; font-family:Verdana, Arial, Helvetica, sans-serif;}
			body {text-align:center; width:640px; margin:64px auto;}
			table {margin: 16px auto;}
		</style>
	</head>
	<body>
<?php
$version = qa_opt('IT_VERSION');
$suggest='<p><a href="'.qa_path_html('admin', null, null, QA_URL_FORMAT_SAFEST).'">Go to admin center</a></p>';
// first installation
if (empty($version)){
	//reset_theme_options();
	$version = 1;
	qa_opt('IT_VERSION',$version);
	require_once(IT_DIR. '/functions.php');
	it_reset_theme_options();
	echo '<p>Theme is installed.</p>';
}
/*
if ($version < IT_VERSION){
	$version = 1.1;
	qa_opt('IT_VERSION',$version);
	echo '<p>Theme is updated to version 1.1</p>';
}
if ($version < IT_VERSION){
	$version = 1.2;
	qa_opt('IT_VERSION',$version);
	echo '<p>Theme is updated to version 1.2</p>';
}
*/

if ($version==IT_VERSION){
	echo '<p>Your Theme is up to date.</p>';
}
echo $suggest;
?>

	</body>
</html>	