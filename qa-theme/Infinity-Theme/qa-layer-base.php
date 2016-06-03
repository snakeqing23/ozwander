<?php
/*
	don't allow this page to be requested directly from browser 
*/
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}
/*
	Theme Override
*/		
	
class qa_html_theme extends qa_html_theme_base
{
	/*
	* doctype for preparing content before setting up the theme
	*
	* @since 1.0.0
	* @compatible yes
	*/	
		function doctype(){
			// print HTML5 doctype with full plugin compatibility
			ob_start();
			qa_html_theme_base::doctype();
			$output = ob_get_clean();
			$doctype = str_replace('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">', '<!DOCTYPE html>', $output);
			$this->output($doctype);
		}


	/*
	* build html layout
	*
	* @since 1.0.0
	* @compatible no
	*/	
		function html()
		{
			$this->output(
				'<html>'
			);
			
			$this->head();
			$this->body();
			
			$this->output(
				'</html>'
			);
		}
	/*
	* responsive view point
	*
	* @since 1.0.0
	* @compatible yes
	*/	
		function head_metas()
		{
			qa_html_theme_base::head_metas();
			$this->output('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
		}
	/*
	* custom CSS
	*
	* @since 1.0.0
	* @compatible yes
	*/	
		function head_css()
		{
			// prepare CSS
			$this->output('<link rel="stylesheet" type="text/css" href="' .$this->rooturl .'css/bootstrap.min.css"/>');
			if(qa_opt('it_custom_style_created'))
				$this->output('<link rel="stylesheet" type="text/css" href="' .$this->rooturl.'css/dynamic.css"/>');
			else
				$this->output('<style type="text/css">' . qa_opt('it_custom_css') . '</style>');
				
			if (($this->template=='ask') or ($this->template=='question' && substr(qa_get_state(),0,4)=='edit')){
				$this->output('<link rel="stylesheet" type="text/css" href="' .$this->rooturl .'css/ask.css"/>');
			}
			if($this->request=='admin/it_options'){
				$this->output('<link rel="stylesheet" type="text/css" href="' . $this->rooturl . 'css/admin.css"/>');
				$this->output('<link rel="stylesheet" type="text/css" href="' . $this->rooturl . 'css/spectrum.css"/>'); // color picker
			}

			$googlefonts = json_decode(qa_opt('it_typo_googlefonts'), true);
			if (isset($googlefonts) && !empty($googlefonts))
				foreach ($googlefonts as $font_name) {
					$font_name = str_replace(" ", "+", $font_name);
					$link      = 'http://fonts.googleapis.com/css?family=' . $font_name;
					$this->output('<link href="' . $link . '" rel="stylesheet" type="text/css">');
				}

			$fav = qa_opt('it_favicon_url');
			if( $fav )
				$this->output('<link rel="shortcut icon" href="' .  $fav . '" type="image/x-icon">');

			qa_html_theme_base::head_css();
		}	
	/*
	* include JS files
	*
	* @since 1.0.0
	* @compatible yes
	*/	
		function head_script()
		{
			qa_html_theme_base::head_script();
			// set JS variables
			$variables = '';
			$variables .= 'it_root_url = "' . MYTHEME_URL .'";';
			$variables .= 'it_featured_url_abs = "' . qa_opt('it_featured_url_abs') .'";';
			$variables .= 'it_ajax_category_url = "' . MYTHEME_URL . 'ajax_category.php";';
			$variables .= 'it_new_category_icon = "' . qa_opt('it_new_cat_icon') .'";';
			$variables .= 'it_ajax_featured_upload_url = "' . MYTHEME_URL . 'ajax_upload.php";';
			$variables .= 'it_ajax_featured_delete_url = "' . MYTHEME_URL . 'ajax_delete.php";';
			$variables .= 'it_ajax_infinite_page_url = "' . MYTHEME_URL . 'ajax_infinite_page.php";';
			$variables .= 'it_ajax_infinite_page_number = 2;';
			$variables .= 'it_ajax_infinite_page_items_count = ' .qa_opt('page_size_home') . ';';
			if(qa_opt('it_infinite_scroll_auto_enable'))
				$variables .= 'it_ajax_infinite_autoload = 1;';
			else
				$variables .= 'it_ajax_infinite_autoload = 0;';
			$this->output('<script>' . $variables . '</script>');
			// prepare JS scripts include Bootstrap's JS file
			$this->output('<script src="'.$this->rooturl.'js/bootstrap.min.js" type="text/javascript"></script>');
			$this->output('<script src="'.$this->rooturl.'js/isotope.min.js" type="text/javascript"></script>');
			$this->output('<script src="'.$this->rooturl.'js/main.js" type="text/javascript"></script>');
			if (($this->template=='ask') or ($this->template=='question' && substr(qa_get_state(),0,4)=='edit')){
				$this->output('<script src="'.$this->rooturl.'js/ask.js" type="text/javascript"></script>');
				$this->output('<script src="'.$this->rooturl.'js/magicsuggest.min.js" type="text/javascript"></script>');
				$this->output('<script src="'.$this->rooturl.'js/uploadfile.min.js" type="text/javascript"></script>');
			}
			if($this->request=='admin/it_options'){
				$this->output('<script src="'.$this->rooturl.'js/admin.js" type="text/javascript"></script>');
				$this->output('<script src="'.$this->rooturl.'js/jquery.uploadfile.min.js" type="text/javascript"></script>');
				$this->output('<script src="'.$this->rooturl.'js/chosen.jquery.min.js" type="text/javascript"></script>');
				$this->output('<script src="'.$this->rooturl.'js/spectrum.js" type="text/javascript"></script>');
			}
		}	
	
	/*
	* Load main body content
	*
	* @since 1.0.0
	* @compatible no
	*/
		function body_content()
		{
			$this->body_prefix();
			$this->notices();

			$this->widgets('full', 'top');
			
			// Q2A header
			$this->widgets('full', 'high');
			$this->header();

			$this->output('<div class="container-fluid' . (qa_opt('it_nav_fixed')?' fixed-nav-container':'') . '">');

			// list of pages with no sidebar
			$pages = array("qa", "questions", "user", "user-wall", "user-activity", "Linux", "user-questions", "user-answers");
			// pages without sidebar
			if(in_array($this->template, $pages)){ 
				// Q2A default body
				$this->output('<section class="qa-main-content col-md-12">');
				$this->main();     
				$this->output('</section>');
			}// pages with sidebar
			else{
				$postid = @$this->content['q_view']['raw']['postid'];
				if(isset($postid)){
					require_once QA_INCLUDE_DIR.'qa-db-metas.php';
					$image = qa_db_postmeta_get($postid, 'et_featured_image');
					if( (!(empty($image)))&& (substr(qa_get_state(),0,4)!='edit') )
						$this->output('<img class="featured-image img-thumbnail" src="'.qa_opt('it_featured_url_abs')  .'featured/'. $image.'"/>');
				}
				// Q2A sidebar
				if ( ($this->request=='admin/it_options') && (qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN) ){
					// Q2A default body
					$this->output('<section class="qa-main-content col-md-12">');
					$this->main();     
					$this->output('</section>');
				}else{
					$this->output('<aside class="qa-main-sidebar col-md-3">');
					$this->sidepanel();
					$this->output('</aside>');

					// Q2A default body
					$this->output('<section class="qa-main-content col-md-9">');
					$this->main();     
					$this->output('</section>');
				}
			}
			

			$this->output('</div>');
			
			// Q2A Footer
			$this->widgets('full', 'low');
			$this->footer();

			

			$this->widgets('full', 'bottom');
			
			$this->body_suffix();
		}
	/*
	* main_parts
	* load all basic part of content, here changed to load them from php files
	*
	* @since 1.0.0
	* @compatible yes
	*/	
		function main_parts($content)
		{
			$this->output('<article class="qa-q-content-article' . (qa_opt('it_layout_lists')=='qlist'?' qlist-defaul':'') . '">');
			qa_html_theme_base::main_parts($content);
			$this->output('</article>');
		}	
	/*
	* header
	* loads "HEADER" tag including user navigation and logo
	*
	* @since 1.0.0
	* @compatible no
	*/	
		function header()
		{
			$this->output('<header class="main-header">');
			
			$this->output('<nav id="menu" class="navbar navbar-default main-navbar' . (qa_opt('it_nav_fixed')?' navbar-fixed-top':'') . '" role="navigation">');
			
			$nav_type = qa_opt('it_nav_type');
			if($nav_type == 'standard')
				$this->show_nav_standard('main','collapse navbar-collapse nav-main');
			else
				$this->show_nav('main','collapse navbar-collapse nav-main');
			
			$this->output('</nav>');
			
			$this->output('</header>');
		}
		
		function search()
		{
			$search=$this->content['search'];
			
			$this->output(
				'<form '.$search['form_tags'].' class="navbar-form" role="search">',
				@$search['form_extra']
			);
			$this->output('<div class="input-group search-group">
				<input type="text" '.$search['field_tags'].' value="'.@$search['value'].'" class="form-control" placeholder="Search">
				<span class="input-group-btn">
					<button type="reset" class="btn btn-default">
						<span class="fa fa-times">
							<span class="sr-only">Close</span>
						</span>
					</button>
					<button type="submit" class="btn btn-default">
						<span class="fa fa-search">
							<span class="sr-only">Search</span>
						</span>
					</button>
				</span>
				</div>
			');
			
			$this->output(
				'</form>'
			);
		}
	/*
	* show_nav *** Replacement for Q2A theme function "nav()"
	* shows navigation menus
	*
	* @since 1.0.0
	* @compatible no
	*/	
		function show_nav($navtype, $class=null, $level=null)
		{
			$navigation=@$this->content['navigation'][$navtype];//vardump($navigation);
			if (($navtype=='main') && isset($navigation)){
				//Responsive Navigation button
				$this->output('<div class="navbar-header">');
				$this->logo();
				$this->output('<button class="navbar-toggle collapsed" data-target=".nav-main" data-toggle="collapse" type="button"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>');
				$this->output('</div>');
				// Main Nav Items
				$questions_nav = $navigation['questions'];
				$this->output('<div class="' . $class . '">');
				$this->output('<ul class="qa-nav-main-list nav navbar-nav navbar-right ">');

				$page_order = '';
				if( (($this->template=='qa') or ($this->template=='questions')) && ((qa_opt('it_layout_masonry_list')!='qlist') && qa_opt('it_layout_choose')) )
					$page_order = '
						<li class="divider"></li>
						<li role="presentation" class="dropdown-header">List Layout</li>
						<li class="dropdown-layout-container">
							<a href="#" id="masonry-layout-btn" class="btn btn-default" title="Masonry"><i class="fa fa-th"></i></a>
							<a href="#" id="list-layout-btn" class="btn btn-default" title="List"><i class="fa fa-th-list"></i></a>
						</li>

					';
					
				$this->output('
					<li class="dropdown qa-nav-main-single-item qa-nav-main-single-submit qa-nav-main-single-ask">
						<a class="qa-submit-item dropdown-toggle" data-toggle="dropdown" href="' . $questions_nav['url'] .'">Browse</span></a>

							<ul class="dropdown-menu with-arrow sub-nav-brows" role="menu">
								<li><a href="' . qa_path_html('questions') . '">' . qa_lang('main/nav_qs') . '</a></li>
								<li><a href="' . qa_path_html('questions', array('sort' => 'hot')) . '">' . qa_lang_html('main/nav_hot') . '</a></li>
								<li><a href="' . qa_path_html('questions', array('sort' => 'votes')) . '">' . qa_lang('main/nav_most_votes') . '</a></li>
								<li class="divider"></li>
								<li><a href="' . qa_path_html('activity') . '">'  . qa_lang_html('main/nav_activity') . '</a></li>
								' . $page_order . '
							</ul>
					</li>
					<li class="qa-nav-main-single-item qa-nav-main-single-submit qa-nav-main-single-ask">
						<a class="qa-submit-item" href="' . $navigation['ask']['url'] .'">' . qa_lang_html('main/nav_ask') . '</a>
					</li>
				');
				if (qa_is_logged_in()) {
					$handle =  qa_get_logged_in_handle();
					$user_nav = array();
					$this->output('
						<li class="qa-nav-main-single-item qa-nav-main-single-profile dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="' . qa_path_html('user/' .$handle) .'">' . $handle .'</a>
							<ul class="user-nav dropdown-menu with-arrow">
					');
					if (qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR) {
						$user_nav['admin'] = array('label' => qa_lang_html('main/nav_admin'), 'url' => qa_path_html('admin'));
					}
						// Theme Options
					if (qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN) {
						$user_nav['it_options'] = array(
							'label' => 'Theme Options',
							'url' => qa_path_html('admin/it_options'),
						);
						if ($this->request == 'admin/it_options'){
							$user_nav['it_options']['selected'] = true;
						}
					}
					
					$logout = $this->content['navigation']['user']['logout'];
					unset($this->content['navigation']['user']['logout']);
					array_merge($user_nav , $this->content['navigation']['user']);
					$user_nav['profile'] = array('label' => 'profile', 'url' => qa_path_html('user/' . $handle));
					
					if(!(QA_FINAL_EXTERNAL_USERS)) 
						$user_nav['account'] = array('label' => 'account', 'url' => qa_path_html('account'));
					$user_nav['favorites'] = array('label' => 'favorites', 'url' => qa_path_html('favorites'));
					if(!(QA_FINAL_EXTERNAL_USERS))
						$user_nav['wall'] = array('label' => 'wall', 'url' => qa_path_html('user/'.$handle.'/wall'), 'icon' =>'icon-edit');
					$user_nav['recent_activity'] = array('label' => 'recent activity', 'url' => qa_path_html('user/'.$handle.'/activity'), 'icon' =>'icon-time');
					$user_nav['all_questions'] = array('label' => 'all questions', 'url' => qa_path_html('user/'.$handle.'/questions'), 'icon' =>'icon-question');
					$user_nav['all_answers'] = array('label' => 'all answers', 'url' => qa_path_html('user/'.$handle.'/answers'), 'icon' =>'icon-answer');
					$user_nav['logout'] = $logout;
					$navigation=@$user_nav;
					foreach ($navigation as $a) {
                        if (isset($a['url'])) {
                            echo '<li' . (isset($a['selected']) ? ' class="active"' : '') . '><a href="' . @$a['url'] . '" title="' . @$a['label'] . '">' . @$a['label'] . '</a></li>';
							if($a['label']=='Theme Options')
								echo '<li class="divider"></li>';
                        }
                    }
					$this->output('
							</ul>
						</li>
					');
				}else{
					$login=@$this->content['navigation']['user']['login'];
					$register=@$this->content['navigation']['user']['register'];
					if (isset($login) && !QA_FINAL_EXTERNAL_USERS) {
						$this->output('
							<li class="qa-nav-main-single-item qa-nav-main-single-submit qa-nav-main-single-ask dropdown">
								<a class="dropdown-toggle qa-submit-item" data-toggle="dropdown" href="' . $login['url'] .'">Login</a>
								<ul class="user-nav dropdown-menu with-arrow">
						');
						$this->output(
								'<form class="form-signin" id="qa-loginform" action="'.$login['url'].'" method="post">',
								'<input class="form-control" type="text" id="qa-userid" name="emailhandle" placeholder="'.trim(qa_lang_html(qa_opt('allow_login_email_only') ? 'users/email_label' : 'users/email_handle_label'), ':').'" />',
								'<input class="form-control" type="password" id="qa-password" name="password" placeholder="'.trim(qa_lang_html('users/password_label'), ':').'" />',
								'<div id="qa-rememberbox"><input type="checkbox" name="remember" id="qa-rememberme" value="1"/>',
								'<label for="qa-rememberme" id="qa-remember">'.qa_lang_html('users/remember').'</label></div>',
								'<input type="hidden" name="code" value="'.qa_html(qa_get_form_security_code('login')).'"/>',
								'<input type="submit" class="btn btn-primary btn-block" value="'.$login['label'].'" id="qa-login" name="dologin" />',
								'<hr>',
								'<p class="text-muted text-center"><small>Do not have an account?</small></p>
								<a class="btn btn-default btn-block" href="' . $register['url'] . '">Sign Up</a>',
							'</form>'
						);
						$this->output('
								</ul>
							</li>'
						);
					}
				}

				$this->output('</ul>');
				//vardump(@$this->content['navigation']['user']);
							
				$this->search();
				$this->output('</div>');
				//unset($navigation);
			}else
			if (isset($navigation) || ($navtype=='user')) {
				$this->output('<nav class="' . $class . '">');
				
				if ($navtype=='user')
					$this->logged_in();
					
				// reverse order of 'opposite' items since they float right
				foreach (array_reverse($navigation, true) as $key => $navlink)
					if (@$navlink['opposite']) {
						unset($navigation[$key]);
						$navigation[$key]=$navlink;
					}
				
				$this->set_context('nav_type', $navtype);
				$this->nav_list($navigation, 'nav-'.$navtype, $level);
				$this->nav_clear($navtype);
				$this->clear_context('nav_type');
	
				$this->output('</nav>');
			}
		}
	/*
	* show_nav_standard *** Replacement for Q2A theme function "nav()" & Theme function "show_nav"
	* shows navigation menus
	*
	* @since 1.1.0
	* @compatible no
	*/	
		function show_nav_standard($navtype, $class=null, $level=null)
		{
			$navigation=@$this->content['navigation'][$navtype];
			if (($navtype=='main') && isset($navigation)){
				//Responsive Navigation button
				$this->output('<div class="navbar-header">');
				$this->logo();
				$this->output('<button class="navbar-toggle collapsed" data-target=".nav-main" data-toggle="collapse" type="button"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>');
				$this->output('</div>');
				
				$this->output('<div class="' . $class . '">');
				$this->output('<ul class="qa-nav-main-list nav navbar-nav navbar-right ">');

				$page_order = '';
				if( (($this->template=='qa') or ($this->template=='questions')) && ((qa_opt('it_layout_masonry_list')!='qlist') && qa_opt('it_layout_choose')) )
					$page_order = '
						<li class="divider"></li>
						<li role="presentation" class="dropdown-header">List Layout</li>
						<li class="dropdown-layout-container">
							<a href="#" id="masonry-layout-btn" class="btn btn-default" title="Masonry"><i class="fa fa-th"></i></a>
							<a href="#" id="list-layout-btn" class="btn btn-default" title="List"><i class="fa fa-th-list"></i></a>
						</li>

					';
			
			$this->set_context('nav_type', $navtype);
			// reverse order of 'opposite' items since they float right
			foreach (array_reverse($navigation, true) as $key => $navlink) {
				if (@$navlink['opposite']) {
					unset($navigation[$key]);
					$navigation[$key] = $navlink;
				}
			}
			$index = 0;
			foreach ($navigation as $key => $navlink) {
				$this->set_context('nav_key', $key);
				$this->set_context('nav_index', $index++);
				// $this->nav_item($key, $navlink, $class, $level);
				$suffix = strtr($key, array( // map special character in navigation key
					'$' => '',
					'/' => '-',
				));

				$this->output('<li class="qa-nav-main-single-item qa-'.$class.'-item'.(@$navlink['opposite'] ? '-opp' : '').
					(@$navlink['state'] ? (' qa-'.$class.'-'.$navlink['state']) : '').' qa-'.$class.'-'.$suffix.'">');
				$this->nav_link($navlink, $class);

				if (count(@$navlink['subnav']))
					$this->nav_list($navlink['subnav'], $class, 1+$level);

				$this->output('</li>');
			}
			$this->clear_context('nav_key');
			$this->clear_context('nav_index');
			$this->clear_context('nav_type');
		

			if (qa_is_logged_in()) {
					$handle =  qa_get_logged_in_handle();
					$user_nav = array();
					$this->output('
						<li class="qa-nav-main-single-item qa-nav-main-single-profile dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="' . qa_path_html('user/' .$handle) .'">' . $handle .'</a>
							<ul class="user-nav dropdown-menu with-arrow">
					');
					// Theme Options
					if (qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN) {
						$user_nav['it_options'] = array(
							'label' => 'Theme Options',
							'url' => qa_path_html('admin/it_options'),
						);
						if ($this->request == 'admin/it_options'){
							$user_nav['it_options']['selected'] = true;
						}
					}
					
					$logout = $this->content['navigation']['user']['logout'];
					unset($this->content['navigation']['user']['logout']);
					array_merge($user_nav , $this->content['navigation']['user']);
					$user_nav['profile'] = array('label' => 'profile', 'url' => qa_path_html('user/' . $handle));
					
					if(!(QA_FINAL_EXTERNAL_USERS)) 
						$user_nav['account'] = array('label' => 'account', 'url' => qa_path_html('account'));
					$user_nav['favorites'] = array('label' => 'favorites', 'url' => qa_path_html('favorites'));
					if(!(QA_FINAL_EXTERNAL_USERS))
						$user_nav['wall'] = array('label' => 'wall', 'url' => qa_path_html('user/'.$handle.'/wall'), 'icon' =>'icon-edit');
					$user_nav['recent_activity'] = array('label' => 'recent activity', 'url' => qa_path_html('user/'.$handle.'/activity'), 'icon' =>'icon-time');
					$user_nav['all_questions'] = array('label' => 'all questions', 'url' => qa_path_html('user/'.$handle.'/questions'), 'icon' =>'icon-question');
					$user_nav['all_answers'] = array('label' => 'all answers', 'url' => qa_path_html('user/'.$handle.'/answers'), 'icon' =>'icon-answer');
					$user_nav['logout'] = $logout;
					$navigation=@$user_nav;
					foreach ($navigation as $a) {
                        if (isset($a['url'])) {
                            echo '<li' . (isset($a['selected']) ? ' class="active"' : '') . '><a href="' . @$a['url'] . '" title="' . @$a['label'] . '">' . @$a['label'] . '</a></li>';
							if($a['label']=='Theme Options')
								echo '<li class="divider"></li>';
                        }
                    }
					$this->output($page_order);
					$this->output('
							</ul>
						</li>
					');
				}else{
					$login=@$this->content['navigation']['user']['login'];
					$register=@$this->content['navigation']['user']['register'];
					if (isset($login) && !QA_FINAL_EXTERNAL_USERS) {
						$this->output('
							<li class="qa-nav-main-single-item qa-nav-main-single-submit qa-nav-main-single-ask dropdown">
								<a class="dropdown-toggle qa-submit-item" data-toggle="dropdown" href="' . $login['url'] .'">Login</a>
								<ul class="user-nav dropdown-menu with-arrow">
						');
						$this->output(
								'<form class="form-signin" id="qa-loginform" action="'.$login['url'].'" method="post">',
								'<input class="form-control" type="text" id="qa-userid" name="emailhandle" placeholder="'.trim(qa_lang_html(qa_opt('allow_login_email_only') ? 'users/email_label' : 'users/email_handle_label'), ':').'" />',
								'<input class="form-control" type="password" id="qa-password" name="password" placeholder="'.trim(qa_lang_html('users/password_label'), ':').'" />',
								'<div id="qa-rememberbox"><input type="checkbox" name="remember" id="qa-rememberme" value="1"/>',
								'<label for="qa-rememberme" id="qa-remember">'.qa_lang_html('users/remember').'</label></div>',
								'<input type="hidden" name="code" value="'.qa_html(qa_get_form_security_code('login')).'"/>',
								'<input type="submit" class="btn btn-primary btn-block" value="'.$login['label'].'" id="qa-login" name="dologin" />',
								'<hr>',
								'<p class="text-muted text-center"><small>Do not have an account?</small></p>
								<a class="btn btn-default btn-block" href="' . $register['url'] . '">Sign Up</a>',
							'</form>'
						);
						$this->output($page_order);
						$this->output('
								</ul>
							</li>'
						);
					}
				}
				$this->output('</ul>');
				//vardump(@$this->content['navigation']['user']);
							
				$this->search();
				$this->output('</div>');
				//unset($navigation);
			}else
			if (isset($navigation) || ($navtype=='user')) {
				$this->output('<nav class="' . $class . '">');
				
				if ($navtype=='user')
					$this->logged_in();
					
				// reverse order of 'opposite' items since they float right
				foreach (array_reverse($navigation, true) as $key => $navlink)
					if (@$navlink['opposite']) {
						unset($navigation[$key]);
						$navigation[$key]=$navlink;
					}
				
				$this->set_context('nav_type', $navtype);
				$this->nav_list($navigation, 'nav-'.$navtype, $level);
				$this->nav_clear($navtype);
				$this->clear_context('nav_type');
	
				$this->output('</nav>');
			}
		}
	/*
	* nav_item
	* shows navigation menu items
	* Add [ 'qa-nav-main-sub-' + class ] to navigation item's class
	*
	* @since 1.0.0
	* @compatible yes
	*/	
		function nav_item($key, $navlink, $class, $level=null)
		{
			// main navigation items with sub navigations will have a new class
			if ( $level>=1)
				$class.='-sub';
			// main navigation items with No sub navigations will have a new class
			if ($class=='nav-main' && !count(@$navlink['subnav']))
				$class.='-single';
			// main navigation items which are current page will have a new class
			if (@$navlink['selected'])
				$navlink['state']='selected';

			qa_html_theme_base::nav_item($key, $navlink, $class, $level=null);
		}
	/*
	* nav_item
	* shows navigation menu items
	* Add [ 'qa-nav-main-sub-' + class ] to navigation item's class
	*
	* @since 1.0.0
	* @compatible no
	*/			
		function page_title_error()
		{
			if( ($this->template=='question') or ($this->template=='ask') ){
				$this->output('<h1>');
				$this->title();
				$this->output('</h1>');
				if (isset($this->content['error']))
					$this->error(@$this->content['error']);
			}elseif( ($this->template=='tag') or ($this->template=='questions') ){
					// fill array with breadcrumb fields and show them
					$bc = array(); // breadcrumb
					$bc[0]['title']=qa_opt('site_title');
					$bc[0]['content']='<i class="fa fa-home"></i>';
					$bc[0]['url']=qa_opt('site_url');
					if($this->template=='tag'){
						$bc[1]['title']='Tags';
						$bc[1]['content']='Tags';
						$bc[1]['url']=qa_path_html('tags');
						$req = explode('/',$this->request);
						$tag = $req[count($req)-1];
						$bc[2]['title']= $tag;
						$bc[2]['content']='Tag "' . $tag . '"';
						$bc[2]['url']=qa_path_html($this->request, null, null, null, null);
					}elseif($this->template=='questions'){
						$req = explode('/',$this->request);
						$cat = $req[count($req)-1];
						if(count($req)>1){
							$category_name = $this->content["q_list"]["qs"][0]["raw"]["categoryname"];
							$bc[1]['title']='Categories';
							$bc[1]['content']='Categories';
							$bc[1]['url']=qa_path_html('categories');
							$bc[2]['title']= $category_name;
							$bc[2]['content']= $category_name ;
							$bc[2]['url']=qa_path_html($this->request, null, null, null, null);
						}else{
							unset($bc);
						}
					}
					if(isset($bc)){
						$this->output('<div class="header-buttons btn-group btn-breadcrumb pull-left">');
						foreach($bc as $item)
							$this->output(' <a href="' . $item['url'] . '" title="' . $item['title'] . '" class="btn btn-default">' . $item['content'] . '</a>');
						$this->output('</div>');
					}
			}else{
				qa_html_theme_base::page_title_error();
			}
			if( ($this->template=='admin') or ($this->template=='users')  or ($this->template=='user') or (qa_opt('it_nav_type') == 'standard'))
				$this->show_nav('sub','nav navbar-nav sub-navbar pull-right');
			qa_html_theme_base::q_view_clear();
		}
	/*
	* q_view_main
	* form is limited to question buttons and comment section
	*
	* @since 1.0.0
	* @compatible no
	*/
		function q_view_main($q_view)
		{
			$this->output('<div class="qa-q-view-main">');

			$this->view_count($q_view);
			$this->q_view_content($q_view);
			$this->q_view_extra($q_view);
			$this->q_view_follows($q_view);
			$this->q_view_closed($q_view);
			$this->post_tags($q_view, 'qa-q-view');
			$this->post_avatar_meta($q_view, 'qa-q-view');

			if (isset($q_view['main_form_tags']))
				$this->output('<form '.$q_view['main_form_tags'].'>'); // form for buttons on question

			$this->q_view_buttons($q_view);
			$this->c_list(@$q_view['c_list'], 'qa-q-view');
			
			if (isset($q_view['main_form_tags'])) {
				$this->form_hidden_elements(@$q_view['buttons_form_hidden']);
				$this->output('</form>');
			}
			
			$this->c_form(@$q_view['c_form']);
			
			$this->output('</div> <!-- END qa-q-view-main -->');
		}
	/*
	* q_view
	* Question Item : favorite is removed
	*
	* @since 1.0.0
	* @compatible yes
	*/
		function q_view($q_view)
		{
			if (!empty($q_view)) {
				$this->output('<div class="qa-q-view'.(@$q_view['hidden'] ? ' qa-q-view-hidden' : '').rtrim(' '.@$q_view['classes']).'"'.rtrim(' '.@$q_view['tags']).'>');

				$this->q_view_main($q_view);
				$this->q_view_clear();
				
				$this->output('</div> <!-- END qa-q-view -->', '');
			}
		}		
	/*
	* q_list
	* add excerpt
	* add featured image
	* count of question favourites
	* add comments count for questions
	* add a class to all question list items
	*
	* @since 1.0.0
	* @compatible no
	*/
		function q_list($q_list)
		{
			if(qa_opt('it_layout_lists') == 'qlist'){
				qa_html_theme_base::q_list($q_list);
				return;
			}
			if (count(@$q_list['qs'])) { // first check it is not an empty list and the feature is turned on
			//	Collect the question ids of all items in the question list (so we can do this in one DB query)
				$postids=array();
				foreach ($q_list['qs'] as $question)
					if (isset($question['raw']['postid']))
						$postids[]=$question['raw']['postid'];
				
				if (count($postids)) {
				//	Retrieve favourite count
					$userid = qa_get_logged_in_userid();
					$result=qa_db_query_sub('SELECT userid,entityid FROM ^userfavorites WHERE entitytype=$ AND entityid IN (#)', 'Q', $postids);
					while ($row=mysqli_fetch_row($result)){
						if ($row[0]==$userid)// loged in user favorited this post
							$faved_post[$row[1]] = 1;

						if(isset($favs[ $row[1] ])){
							$favs[ $row[1] ] = $favs[ $row[1] ] + 1;
						} else
							$favs[ $row[1] ]=1;
						}
				//	Retrieve comment count
					$result=qa_db_query_sub('SELECT postid,parentid FROM ^posts WHERE type=$ AND parentid IN (#)', 'C', $postids);
					$comment_list=qa_db_read_all_assoc($result, 'postid');
					foreach ($comment_list as $key => $value) 
						if(isset($comments[ $value['parentid'] ]))
							$comments[ $value['parentid'] ] = $comments[ $value['parentid'] ]+1;
						else
							$comments[ $value['parentid'] ]=1;
					if(qa_opt('it_excerpt_field_enable') or qa_opt('it_enable_except')){
					//	Get the regular expression fragment to use for blocked words and the maximum length of content to show
						$blockwordspreg=qa_get_block_words_preg();
						if(qa_opt('it_excerpt_field_enable')){
							$maxlength= qa_opt('it_excerpt_field_length');
							//	Retrieve Excerpt Text for all questions
							$result=qa_db_query_sub('SELECT postid,content FROM ^postmetas WHERE postid IN (#) AND title=$', $postids,'et_excerpt_text');
							$excerpt_text=qa_db_read_all_assoc($result, 'postid');
							// set excerpt from field info
							foreach ($q_list['qs'] as $index => $question) {
								// from field
								if(! empty( $excerpt_text[$question['raw']['postid']]['content']) ){
									$text=qa_viewer_text($excerpt_text[$question['raw']['postid']]['content'], '', array('blockwordspreg' => $blockwordspreg));
									$text=qa_shorten_string_line($text, $maxlength);
									$q_list['qs'][$index]['excerpt']=qa_html($text);
								// from post content
								}elseif(qa_opt('it_enable_except')){
									// Retrieve the content for these questions from the database and put into an array
									$result=qa_db_query_sub('SELECT postid, content, format FROM ^posts WHERE postid IN (#)', $postids);
									$postinfo=qa_db_read_all_assoc($result, 'postid');
									$thispost = @$postinfo[$question['raw']['postid']];
									if (isset($thispost)) {
										$text=qa_viewer_text($thispost['content'], $thispost['format'], array('blockwordspreg' => $blockwordspreg));
										$text=qa_shorten_string_line($text, $maxlength);
										$q_list['qs'][$index]['excerpt']=qa_html($text);
									}
								}
							}

								
						}else{ // qa_opt('it_enable_except')  ==> excerpt from question content instead of excerpt field
							$maxlength= qa_opt('it_except_len');
							$result=qa_db_query_sub('SELECT postid, content, format FROM ^posts WHERE postid IN (#)', $postids);
							$postinfo=qa_db_read_all_assoc($result, 'postid');
							foreach ($q_list['qs'] as $index => $question) {
								$thispost = @$postinfo[$question['raw']['postid']];
								if (isset($thispost)) {
									$text=qa_viewer_text($thispost['content'], $thispost['format'], array('blockwordspreg' => $blockwordspreg));
									$text=qa_shorten_string_line($text, $maxlength);
									$q_list['qs'][$index]['excerpt']=qa_html($text);
								}
							}
						}
					}
				//	Retrieve featured images for all list questions
					if(qa_opt('it_feature_img_enable')){
						$result=qa_db_query_sub('SELECT postid,content FROM ^postmetas WHERE postid IN (#) AND title=$', $postids,'et_featured_image');
						$featured_images=qa_db_read_all_assoc($result, 'postid');
					}
				//	Now meta information for each question
					foreach ($q_list['qs'] as $index => $question) {

						if(qa_opt('it_feature_img_enable')){
							$featured_image = @$featured_images[$question['raw']['postid']]['content'];
							if (isset($featured_image)) {
								$q_list['qs'][$index]['featured']= qa_opt('it_featured_url_abs') .'featured/'. $featured_image;
							}
						}

						if (isset($comments[ $question['raw']['postid'] ])) 
							$q_list['qs'][$index]['comments']= $comments[ $question['raw']['postid'] ];
						else
							$q_list['qs'][$index]['comments']= 0;

						$q_list['qs'][$index]['favourited']=0;
						if (isset($favs[ $question['raw']['postid'] ])){
							$q_list['qs'][$index]['favourites']= $favs[ $question['raw']['postid'] ];
							if(isset($faved_post[ $question['raw']['postid'] ]))
								$q_list['qs'][$index]['favourited']=1;
						}else
							$q_list['qs'][$index]['favourites']= 0;
					}
				}
			}

			if (isset($q_list['qs'])) {
				$this->output('<div class="qa-q-list row'.($this->list_vote_disabled($q_list['qs']) ? ' qa-q-list-vote-disabled' : '').'">', '');
				$this->q_list_items($q_list['qs']);
				$this->output('</div> <!-- END qa-q-list -->', '');
			}
		}
	/*
	* q_list_items
	* add a class to all question list items
	*
	* @since 1.0.0
	* @compatible no
	*/	
		function q_list_items($q_items)
		{
			if(qa_opt('it_layout_lists') == 'qlist'){
				qa_html_theme_base::q_list_items($q_items);
				return;
			}
			foreach ($q_items as $key => $q_item)
				$q_items[$key]['classes'] .= ' col col-md-4';
			qa_html_theme_base::q_list_items($q_items);
		}
	/*
	* q_item_title
	* show featured image in lists
	*
	* @since 1.0.0
	* @compatible no
	*/	
		function q_item_title($q_item)
		{
			$this->output('<div class="qa-q-item-title">');
			$this->output('<a href="'.$q_item['url'].'">');
			if (isset($q_item['featured'])){
				$fileName = $q_item['featured'];
				$exts = substr(strrchr($fileName,'.'),1);
				$withoutExt = preg_replace("/\\.[^.\\s]{3,4}$/", "", $fileName);
				$thumbnailName = $withoutExt.'_.'.$exts;
				$this->output('<img class="featured-image-item" src="' . $thumbnailName . '"/>');
			}
			$this->output('<h2>' . $q_item['title'] . '</h2>');
			if(@$q_item['excerpt'])
				$this->output('<SPAN class="qa-excerpt">' . $q_item['excerpt'] . '</SPAN>');
			$this->output('</a>');
			$this->output('</div>');
		}
	/*
	* a_selection
	* customize "Select best answer" button
	*
	* @since 1.1.0
	* @compatible no
	*/
		public function a_selection($post)
		{
			$this->output('<div class="qa-a-selection">');

			if (isset($post['select_tags']))
				$this->output('<button '.$post['select_tags'].' type="submit" class="btn btn-default qa-a-select"/>' . qa_lang_html('question/select_text') . '</button>');
			elseif (isset($post['unselect_tags']))
				$this->output('<button '.$post['unselect_tags'].' type="submit" class="btn btn-success qa-a-unselect"/>' . @$post['select_text'] . '</button>');
			elseif ($post['selected'])
				$this->output('<div class="qa-a-selected">&nbsp;</div>');

			//if (isset($post['select_text']))
			//	$this->output('<div class="qa-a-selected-text">'.@$post['select_text'].'</div>');

			$this->output('</div>');
		}
	
	/*
	* q_view_buttons
	* only show form buttons to logged in user
	*
	* @since 1.0.0
	* @compatible no
	*/		
		function q_view_buttons($q_view)
		{
			// show buttons if user is logged in
			$userid = qa_get_logged_in_userid();
			if ( isset($userid) )
				qa_html_theme_base::q_view_buttons($q_view);

		}

	
	/*
	* title
	* add link to question title
	*
	* @since 1.0.0
	* @compatible no
	*/		
		function title()
		{
			if (isset($this->content['q_view'])){
				$postid = $this->content['q_view']['raw']['postid'];
				$qlink = qa_q_path($postid, $this->content['q_view']['raw']['title']);
				$this->output('<a class="q-entry-title" href="' . $qlink . '">');
				qa_html_theme_base::title();
				$this->output('</a>');
			}else
				qa_html_theme_base::title();
		}

	/*
	* favorite
	* remove favorite from question item
	*
	* @since 1.0.0
	* @compatible yes
	* @relative a_count()
	* replace with favorite_main()
	*/
		function favorite()
		{

		}
		function favorite_main($post)
		{
			$favorite=@$this->content['favorite'];
			if (isset($favorite)){
				$this->output('<form '.$favorite['form_tags'].'>');
				qa_html_theme_base::favorite();
				$this->form_hidden_elements(@$favorite['form_hidden']);
				$this->output('</form>');
			}
		}
	/*
	* q_item_stats
	* remove Stats: votes , answer count, ...
	*
	* @since 1.0.0
	* @compatible no
	*/		
		function q_item_stats($q_item)
		{
		}
	/*
	* post_tags
	* hide tags in lists
	* add container opener before tags and close after buttons
	*
	* @since 1.0.0
	* @compatible yes
	* @related: post_avatar_meta
	*/
		function post_tags($post, $class)
		{ 
			//if (!( ($this->template=='qa') or ($this->template=='questions') ))
			// if it's not in a question list
			if ($class != 'qa-q-item'){
				$this->voting_inner_html($post);
				qa_html_theme_base::post_tags($post, $class);
			}
		}
	/*
	* post_avatar_meta
	* hide all user meta excerpt user avatar in question lists
	*
	* @since 1.0.0
	* @compatible no
	* @related: post_tags
	*/
		function post_avatar_meta($post, $class, $avatarprefix=null, $metaprefix=null, $metaseparator='<br/>')
		{
			if(qa_opt('it_layout_lists') == 'qlist'){
				qa_html_theme_base::post_avatar_meta($post, $class, $avatarprefix, $metaprefix, $metaseparator);
				return;
			}
			// check if it's a question list or question item	
			if ($class != 'qa-q-item')//if (!( ($this->template=='qa') or ($this->template=='questions') ))
				qa_html_theme_base::post_avatar_meta($post, $class, $avatarprefix, $metaprefix, $metaseparator);
			else
				$this->post_avatar($post, $class, $avatarprefix);
		}
	/*
	* post_avatar
	* hover effect for avatars
	*
	* @since 1.0.0
	* @compatible no
	*/
		function post_avatar($post, $class, $prefix=null)
		{
			if(qa_opt('it_layout_lists') == 'qlist'){
				qa_html_theme_base::post_avatar($post, $class, $prefix=null);
				return;
			}
			// check if it's a question list or question item
			if ($class != 'qa-q-item')//if (!( ($this->template=='qa') or ($this->template=='questions') ))
				qa_html_theme_base::post_avatar($post, $class, $prefix);
			else{
				$qlink = qa_q_path($post['raw']['postid'], $post['raw']['title'],true);
				$this->output('<div class="q-item-meta">');
				// set avatar
				if (isset($post['avatar'])) {
					if (isset($prefix))
						$this->output($prefix);

					$this->output('<section class="'.$class.'-avatar">' . $post['avatar']);
						$this->output('<section class="popup-user-avatar">');
						qa_html_theme_base::post_meta_what($post, $class);
						qa_html_theme_base::post_meta_who($post, $class);
						$this->output('</section>');
					$this->output('</section>');
				}
				// set category
				if ($post["raw"]["categoryid"]){
					require_once QA_INCLUDE_DIR.'qa-db-metas.php';
					$categoryid = $post["raw"]["categoryid"];
					$catname = $post["raw"]["categoryname"];
					$catbackpath = $post["raw"]["categorybackpath"];
					$et_category = json_decode( qa_db_categorymeta_get($categoryid, 'et_category'), true );
					$this->output('<section class="'.$class.'-category">');
						$categorypathprefix = 'questions/';
						$this->output('<a class="'.$class.'-category-link" title="' . $et_category['et_cat_title'] . '" href="'.qa_path_html($categorypathprefix.implode('/', array_reverse(explode('/', $catbackpath)))).'">');
							if (!(empty($et_category['et_cat_icon48'])))
								$this->output('<img class="qa-category-image" width="48" height="48" alt="' . $et_category['et_cat_desc'] . '" src="' . $et_category['et_cat_icon48'] . '">');
							else
								$this->output(qa_html($catname));
						$this->output('</a>');
						if (!(empty($et_category['et_cat_desc']))){
							$this->output('<section class="'.$class.'-category-description">');
							$this->output($et_category['et_cat_desc']);
							$this->output('</section>');
						}
					$this->output('</section>');
				}
				$this->output('</div>');
				$this->output('<div class="qa-item-meta-bar">');
					// Voting
					$this->voting_inner_html($post);
					// favourites
					if(qa_is_logged_in()){
						$favourited = $post['favourited'];
						$favorite=qa_favorite_form(QA_ENTITY_QUESTION, $post['raw']['postid'], $favourited, 
							qa_lang($favourited ? 'question/remove_q_favorites' : 'question/add_q_favorites'));
						if (isset($favorite)){
							//$this->output('<form '.$favorite['form_tags'].'>');
							$this->output('<div class="qa-favoriting qa-favoriting-' . $post['raw']['postid'] . '" '.@$favorite['favorite_tags'].'>');
							$this->favorite_inner_html($favorite,$post['favourites']);
							$this->output('</div>');
							$this->output('<input type="hidden" id="fav_code_'. $post['raw']['postid'] . '" name="fav_code" value="'.@$favorite['form_hidden']['code'].'"/>');
							//$this->output('</form>');
						}
					}else{
						$this->output('<div class="qa-favoriting qa-favoriting-' . $post['raw']['postid'] . '" '.@$favorite['favorite_tags'].'>');
						$this->output('<button class="btn btn-default btn-xs fa fa-heart qa-favorite" type="button" onclick="return qa_favorite_click(this);" name="favorite-login_q' . $post['raw']['postid'] . '" title="Favourite">' . $post['favourites'] . '</button>');
						//<button class="btn btn-default btn-xs fa fa-heart qa-favorite" type="button" onclick="return qa_favorite_click(this);" name="favorite_Q_125_1" title="Add to my favorites">2</button>
						$this->output('</div>');
					}
					// discussions
					$this->output('<div class="qa-list-discussions">');
						$this->output('<a class="btn btn-default btn-xs fa fa-comment discussions-item-list" href="'. $qlink .'">' . ($post['comments']+$post["answers_raw"]) . '</a>');
					$this->output('</div>');
					// Share
					$this->output('<div class="qa-list-share">');
						$this->output('<button type="button" class="btn btn-default btn-xs fa fa-share-alt share-item-list" data-share-link="'. $qlink .'" data-share-title="'.$post['raw']['title'].'"></button>');
					$this->output('</div>');
				$this->output('</div>');
			}
			//qa_html_theme_base::voting_inner_html($post);
		}
	/*
	* favorite_inner_html
	* bootstrap & question list compatible favourite button
	*
	* @since 1.0.0
	* @compatible no
	*/

		function favorite_inner_html($favorite,$favorites=null)
		{
			$tags = '';
			if(isset($favorite['favorite_add_tags'])){
				$tags = $favorite['favorite_add_tags'];
				$class= 'qa-favorite';
			}elseif(isset($favorite['favorite_remove_tags'])){
				$tags = $favorite['favorite_remove_tags'];
				$class= 'qa-unfavorite';
			}
			$this->favorite_button($tags, $class,$favorites);
		}
	/*
	* favorite_button
	* bootstrap & question list compatible favourite buttons
	*
	* @since 1.0.0
	* @compatible no
	*/
	
		function favorite_button($tags, $class,$favorites=null)
		{
			if (isset($tags))
				$this->output('<button ' . $tags . ' class="btn btn-default btn-xs fa fa-heart ' . $class . '" type="button">' . $favorites . '</button>');
		}
	/*
	* voting_inner_html
	* voting for question lists
	*
	* @since 1.0.0
	* @compatible no
	*/
		function voting_inner_html($post){
			// Voting
			if(isset($post['vote_view'])){ // don't show on question edit form
				if( ( ($this->template=='question') or ($this->template=='voting') ) ) {
					if( isset($post['main_form_tags']) )
						$this->output('<form '.$post['main_form_tags'].'>'); // form for voting buttons
					$this->output('<div class="qa-voting-item '.(($post['vote_view']=='updown') ? 'qa-voting-updown' : 'qa-voting-net').'" '.@$post['vote_tags'].'>');
						$this->output('<div class="qa-item-vote-buttons">');
							$this->list_vote_buttons($post);
						$this->output('</div>');
					$this->output('</div>');
					$this->form_hidden_elements(@$post['voting_form_hidden']);
					if( isset($post['main_form_tags']) )
						$this->output('</form>');	
				}else{
					$this->output('<div class="qa-voting-item '.(($post['vote_view']=='updown') ? 'qa-voting-updown' : 'qa-voting-net').'" '.@$post['vote_tags'].'>');
						$this->output('<div class="qa-item-vote-buttons">');
							$this->list_vote_buttons($post);
						$this->output('</div>');
					$this->output('</div>');
				}
			}
}

		
		function list_vote_buttons($post)
		{
			$onclick='onclick="return qa_vote_click(this);"';
			$anchor=urlencode(qa_anchor($post['raw']['type'], $post['raw']['postid']));
			
			//v($post['vote_up_tags']);
			//v($post['vote_down_tags']);
			if ($post['vote_up_tags']==' ')
				$post['vote_up_tags']='title="'.qa_lang_html('main/voted_up_popup').'" name="'.qa_html('vote_'.$post['raw']['postid'].'_0_'.$anchor).'" '.$onclick;
			if ($post['vote_down_tags']==' ')
				$post['vote_down_tags']='title="'.qa_lang_html('main/voted_down_popup').'" name="'.qa_html('vote_'.$post['raw']['postid'].'_0_'.$anchor).'" '.$onclick;
			
			switch (@$post['vote_state'])
			{
				case 'voted_up':
					$this->post_hover_button($post, 'vote_up_tags', '&#xf087;', 'fa btn btn-success qa-item-vote-one-button qa-voted-up');
					$this->output('<div class="qa-list-vote-count">' . $post['netvotes_view']['data'] . '</div>');
					$this->post_hover_button($post, 'vote_down_tags', '&#xf088;', 'fa btn btn-danger qa-item-vote-one-button qa-vote-down');
					break;
					
				case 'voted_up_disabled':
					$this->post_disabled_button($post, 'vote_up_tags', '&#xf087;', 'fa btn btn-success qa-item-vote-one-button qa-vote-up');
					$this->output('<div class="qa-list-vote-count">' . $post['netvotes_view']['data'] . '</div>');
					$this->post_hover_button($post, 'vote_down_tags', '&#xf088;', 'fa btn btn-danger qa-item-vote-one-button qa-voted-down');
					break;
					
				case 'voted_down':
					$this->post_hover_button($post, 'vote_up_tags', '&#xf087;', 'fa btn btn-success qa-item-vote-one-button qa-vote-up');
					$this->output('<div class="qa-list-vote-count">' . $post['netvotes_view']['data'] . '</div>');
					$this->post_hover_button($post, 'vote_down_tags', '&#xf088;', 'fa btn btn-danger qa-item-vote-one-button qa-voted-down');
					break;
					
				case 'voted_down_disabled':
					$this->post_hover_button($post, 'vote_up_tags', '&#xf087;', 'fa btn btn-success qa-item-vote-one-button qa-voted-up');
					$this->output('<div class="qa-list-vote-count">' . $post['netvotes_view']['data'] . '</div>');
					$this->post_disabled_button($post, 'vote_down_tags', '&#xf088;', 'fa btn btn-danger qa-item-vote-one-button qa-vote-down');
					break;
					
				case 'up_only':
					$this->post_hover_button($post, 'vote_up_tags', '&#xf087;', 'fa btn btn-success qa-item-vote-first-button qa-vote-up');
					$this->output('<div class="qa-list-vote-count">' . $post['netvotes_view']['data'] . '</div>');
					$this->post_disabled_button($post, 'vote_down_tags', '&#xf088;', 'fa btn btn-danger qa-item-vote-second-button qa-vote-down');
					break;
				
				case 'enabled':
					$this->post_hover_button($post, 'vote_up_tags', '&#xf087;', 'fa btn btn-success qa-item-vote-first-button qa-vote-up');
					$this->output('<div class="qa-list-vote-count">' . $post['netvotes_view']['data'] . '</div>');
					$this->post_hover_button($post, 'vote_down_tags', '&#xf088;', 'fa btn btn-danger qa-item-vote-second-button qa-vote-down');
					break;

				default:
					$this->post_disabled_button($post, 'vote_up_tags', '&#xf087;', 'fa btn btn-success qa-item-vote-first-button qa-vote-up');
					$this->output('<div class="qa-list-vote-count">' . $post['netvotes_view']['data'] . '</div>');
					$this->post_disabled_button($post, 'vote_down_tags', '&#xf088;', 'fa btn btn-danger qa-item-vote-second-button qa-vote-down');
					break;
			}
		}

	/*
	* page_links_list
	* Related: suggest_next()
	* don't load infinite Scroll with page numbers
	*
	* @since 1.1.0
	* @compatible yes
	*/
		function page_links_list($page_items)
		{
			if(!( ($this->template=='qa' && qa_opt('it_infinite_scroll_home_enable')) || ($this->template=='questions' && qa_opt('it_infinite_scroll_qa_enable')) ))
				qa_html_theme_base::page_links_list($page_items);
		}
	/*
	* suggest_next
	* Ajax infinite page load
	*
	* @since 1.0.0
	* @compatible yes
	*/
		function suggest_next()
		{
			if( ($this->template=='qa' && qa_opt('it_infinite_scroll_home_enable')) || ($this->template=='questions' && qa_opt('it_infinite_scroll_qa_enable')) ){
				$this->output('<div id="infinite-ajax-suggest" class="qa-suggest-next infinite-ajax-suggest">');
				$this->output('<a href="#" id="infinite-ajax-load-more"  class="infinite-ajax-load-more">Load More</a>');
				$this->output('</div>');
			}else
				qa_html_theme_base::suggest_next();
		}
	/*
	* footer
	* HTML5 Footer
	*
	* @since 1.0.0
	* @compatible yes
	*/
		function footer()
		{
			$this->output('<footer class="qa-footer-container">');
			qa_html_theme_base::footer();
			$this->output('</footer>', '');
			$this->output('<div id="ajax-holder" style="display:none;visibility:hidden;"></div>', '');
			$this->output('
			<span id="top-link-block" class="hidden">
				<a href="#top" class="well well-sm"  onclick="$(\'html,body\').animate({scrollTop:0},\'slow\');return false;">
					<i class="fa fa-chevron-up"></i>
				</a>
			</span>
			', '');
		}
	/*
	* footer
	* HTML5 Footer
	*
	* @since 1.1.0
	* @compatible yes
	*/
		public function attribution()
		{
			// Hi there. I'd really appreciate you displaying this link on your Q2A site. Thank you - Gideon
			$this->output(
				'<div class="qa-attribution">',
				', Designed by <a href="http://qa-themes.com/" title="Question2Answer Themes">Q2A Themes</a>',
				'</div>'
			);
			qa_html_theme_base::attribution();
		}
}
	
/*
	Omit PHP closing tag to help avoid accidental output
*/