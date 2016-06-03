<?php
class it_process 
{	
	/**
	 * save category meta data if category is updated 
	 */
	function init_page() 
	{
		if ( qa_clicked('dosavecategory') && !qa_clicked('docancel') ){
			require_once QA_INCLUDE_DIR.'qa-db-metas.php';
			$categoryid = qa_post_text('edit');
			if(!(empty($categoryid))){
				// update admin/category form to save form's category meta data
				$et_category['et_cat_title'] = qa_post_text('et_category_link_title');
				if(!(isset( $et_category['et_cat_title'] )))
					$et_category['et_cat_title'] = '';
				
				$et_category['et_cat_desc'] = qa_post_text('et_category_description');
				if(!(isset( $et_category['et_cat_desc'] )))
					$et_category['et_cat_desc'] = '';
					
				$et_category['et_cat_icon48'] = qa_post_text('et_category_icon_48');
				if(!(isset( $et_category['et_cat_icon48'] )))
					$et_category['et_cat_icon48'] = '';
					
				$et_category['et_cat_icon64'] = qa_post_text('et_category_icon_64');
				if(!(isset( $et_category['et_cat_icon64'] )))
					$et_category['et_cat_icon64'] = '';
					
				qa_db_categorymeta_set($categoryid, 'et_category', json_encode($et_category));
			}
		}
		
		// when loading question page after edit if category is updated, Q2A will attempt to Re-Update question category based on "q_category_X" field.
		// this happens when category already exists, and we attempt to change it while editing question.
		// here we will set category id to "q_category_1" field, so it wont change it to null after question is updated
		$questionid=qa_request_part(0);
		$num_questionid = (int)$questionid;
		if( (qa_clicked('q_dosave')) && ($questionid==$num_questionid) ){
			$result = qa_db_read_one_assoc(qa_db_query_sub('SELECT categoryid FROM ^posts WHERE postid=$', $num_questionid ),true);
			$_POST['q_category_1'] = $result['categoryid'];
		}
	}
	
}