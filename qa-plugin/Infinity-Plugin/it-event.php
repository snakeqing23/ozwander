<?php
if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}
require_once QA_INCLUDE_DIR.'qa-db-metas.php';

class it_event {

	function process_event ($event, $userid, $handle, $cookieid, $params) {
		switch ($event) {
		case 'q_queue':
		case 'q_post':
		case 'q_edit':
		//Save Selected Category
			if(qa_opt('it_cat_advanced_enable')){
				$categories = explode("," , qa_post_text('q_category'));
				$category = $categories[0];
				//qa_fatal_error(var_dump($category));
				if (!empty($category))
					$this->AddCategoryToPost($params, $category );
			}
		// Save Featured image
			if(qa_opt('it_feature_img_enable')){
				$image = qa_post_text('featured_image');
				$postid = $params['postid'];
				if( (isset($image)) && (!(empty($image))) ){
					//save image
					qa_db_postmeta_set($postid, 'et_featured_image', $image);
				}else{
					// remove image from db
					qa_db_postmeta_clear($postid, 'et_featured_image');
					// remove image file
					$output_dir = QA_BLOBS_DIRECTORY."featured/";
					$filePath = $output_dir. $image;
					if (file_exists($filePath)) 
					{
						unlink($filePath);
					}
				}
			}
		// Save Excerpt
			if(qa_opt('it_excerpt_field_enable')){
				$excerpt = qa_post_text('q-excerpt');
				if(empty($excerpt))
					qa_db_postmeta_clear($postid, 'et_excerpt_text');
				else
					qa_db_postmeta_set($postid, 'et_excerpt_text', $excerpt);
			}
			break;
		}
	}
	
	function AddCategoryToPost($params, $category){
		require_once QA_INCLUDE_DIR.'qa-db-post-update.php';
		$postid = $params['postid'];
		$result = qa_db_read_one_assoc(qa_db_query_sub('SELECT categoryid,parentid,tags,title,content,qcount,position,backpath FROM ^categories WHERE title=$', $category ),true);
		if(empty($result)){ //create category
			$tags = str_replace(' ', '-', $category);
			$catID = $this->CreatCategory($category,$tags);
			qa_db_post_set_category($postid, $catID, null, null);
			qa_db_posts_calc_category_path($postid);
			$path=qa_db_post_get_category_path($postid);
			qa_db_category_path_qcount_update($path);
		}else{ // update category
			$oldpath=qa_db_post_get_category_path($postid);
			$tags = $result['tags'];
			$catID = $result['categoryid'];
			qa_db_post_set_category($postid, $catID, null, null);
			qa_db_posts_calc_category_path($postid);
			
			$path=qa_db_post_get_category_path($postid);
			qa_db_category_path_qcount_update($oldpath);
			qa_db_category_path_qcount_update($path);
		}
	}
	function CreatCategory($category,$tags){
		require_once QA_INCLUDE_DIR.'qa-db-admin.php';
		//qa_fatal_error(var_dump($tags));
		return qa_db_category_create(null, $category, $tags);
	}
}
/*
	Omit PHP closing tag to help avoid accidental output
*/