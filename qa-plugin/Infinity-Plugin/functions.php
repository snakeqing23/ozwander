<?php
// just dump content for debuging
if (!(function_exists('v'))) { 
	function v($c){
		echo "<pre>";
		var_dump($c);
		echo "</pre>";
	}
}
function it_reset_theme_options(){
    // General
    qa_opt('it_favicon_url', '');
	qa_opt('it_infinite_scroll_home_enable', true );
	qa_opt('it_infinite_scroll_qa_enable', false );
	qa_opt('it_infinite_scroll_auto_enable', true );
	qa_opt('it_social_share_qa_enable', true );
	qa_opt('it_nav_type', 'minimal' );
	qa_opt('it_excerpt_field_enable', false );
	qa_opt('it_excerpt_access_level', 120 ); // super admin
	qa_opt('it_excerpt_field_length', 256 );
	qa_opt('it_excerpt_pos_ask', 4 );
	qa_opt('it_excerpt_pos_edit', 2 );
    qa_opt('it_cat_advanced_enable', false);
	qa_opt('it_cat_pos_ask', 3 );
	qa_opt('it_cat_pos_edit', 2 );
	qa_opt('it_feature_img_enable', false );
	//qa_opt('it_featured_url_abs', '' );

    // Layout
    qa_opt('it_nav_fixed', false);
    qa_opt('it_enble_back_to_top', false);
    qa_opt('it_enable_except', true);
    qa_opt('it_except_len', 256);
    qa_opt('it_layout_lists', 'masonry');
    qa_opt('it_layout_choose', true);

    // Styling
    qa_opt('it_bg_select', 'bg_default');
    qa_opt('it_bg_color', '');
    qa_opt('it_text_color', '');
    qa_opt('it_border_color', '');
    qa_opt('it_q_link_color', '');
    qa_opt('it_q_link_hover_color', '');
    qa_opt('it_nav_link_color', '');
    qa_opt('it_nav_link_color_hover', '');
    qa_opt('it_subnav_link_color', '');
    qa_opt('it_subnav_link_color_hover', '');
    qa_opt('it_link_color', '');
    qa_opt('it_link_hover_color', '');
    qa_opt('it_highlight_color', '');
    qa_opt('it_highlight_bg_color', '');
    qa_opt('it_custom_css', '');

	// Typography
	$typo = array('h1','h2','h3','h4','h5','p','span','quote','qtitle','qtitlelink','pcontent','mainnav');
	foreach($typo as $k ){
		qa_opt('typo_options_family_' . $k , '');
		qa_opt('typo_options_style_' . $k , '');
		qa_opt('typo_options_size_' . $k , '');
		qa_opt('typo_options_linehight_' . $k , '');
		qa_opt('typo_options_backup_' . $k , '');
	}
	qa_opt('it_typo_googlefonts', '');	
	
	// Q2A Customizations
	qa_opt('avatar_q_list_size', 48); // set default avatar size in question lists to 48px
}
