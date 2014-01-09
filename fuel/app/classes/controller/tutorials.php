<?php

class Controller_Tutorials extends Controller_Base
{

	// If user not loged in, redirect to landing page, else redirect to users stream
	public function action_index() {
		if(!$this->current_user) {
			Response::redirect('/');
		} 
		Response::redirect('/stream');
	}

	public function action_explore($category_id = null)
	{
		Lang::load('tutorial_explore');
		// TODO: paginate

		//Check if it's a valid category
		$error_msg = '';
		$search_users = '';
		// If nothing found for this id and id not NULL, to allow all tuts page
		if(!count(Model_Category::find($this->param('id')))&&$this->param('id')!==NULL) {
			$error_msg .= '<div class="well">';
			$error_msg .= '<h2> '.__('CATEGORY_NOT_EXIST').'</h2>';
			$error_msg .= '<p class="hidden-xs"> <span class="glyphicon glyphicon-chevron-left"> </span> '.__('CHOOSE_CATEGORY').__('LEFT_SIDE_OF_PAGE').' </p>';
			$error_msg .= '<p class="visible-xs"> <span class="glyphicon glyphicon-chevron-up"> </span>'.__('CHOOSE_CATEGORY').__('TOP_OF_PAGE').' </p>';
			$error_msg .= '</div>';
		}

		// If search set, go for it.   This should be moved to search action!
		if(isset($_GET['srch'])&&$_GET['srch']!==NULL) {
			$term = $_GET['srch'];
			$active_category = '0';

			$tutorials = $tutorial = Model_Tutorial::find('all', array(
				'where' => array(
    				    array('title', 'LIKE', '%'.$term.'%'),
    				    'or' => array(
    				        array('description', 'LIKE', '%'.$term.'%'),
    				    ),
    			),
				"related" => array("user", "category"),
				'order_by' => array('created_at' => 'desc')));

		}
		// Else if category is set, select tutorials from it
		else if($this->param('id')) {
			$tutorials = $tutorial = Model_Tutorial::find('all', array(
				"where" => array("category_id" => $this->param('id')),
				"related" => array("user", "category"),
				'order_by' => array('created_at' => 'desc')));
			//$tutorials = where('category_id', $this->param('id'));
			$active_category = $this->param('id');
		}
		// Else - nothing is set, show everything i've got
		else {
			$tutorials = Model_Tutorial::find('all', array("related" => array("user", "category"),'order_by' => array('created_at' => 'desc')));
			$active_category = '0';
		}

		// Get full category list
		$categories = Model_Category::find('all');

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = 'Explore';
		$this->template->content = View::forge('tutorials/explore', array(
				'tutorials' => $tutorials,
				'categories' => $categories,
				'active_category' => $active_category,
				'error_msg' => $error_msg,
				'search_users' => $search_users
				), false);
	}

	public function action_stream()
	{
		Lang::load('tutorial_stream');
		if(!$this->current_user) {
			Response::redirect('/');
		} 


        $followers = Model_Follower::find('all', array(
        	'where' => array(
        		'follower_id' => $this->current_user->id), 
        	'related' => array(
        		'user' => array(
        			'related' => array(
        				'tutorials',  ))), 'limit' => 20));

		$this->template->navbar = array('stream' => 'active');
		$this->template->title = 'Videokaste.lv - '.$this->current_user->username;
		$this->template->content = View::forge('tutorials/stream', array(
				'followers' => $followers, 
				));
	}

	public function action_create()
	{	
		Lang::load('tutorial_edit');
		// Data that will contain input values in case of registration failure
		$post_data['title'] = '';
		$post_data['description'] = '';
		$post_data['contents'] = '';
		$post_data['videourl'] = '';
		$post_data['category'] = '';
		$post_data['visibility0'] = false;
		$post_data['visibility1'] = false;
		$post_data['language_en'] = false;
		$post_data['language_lv'] = false;

		// If have POST data atempt tutorial create
		if(Input::method() == 'POST'&&Security::check_token())
		{
			$validated = true;
			$error_msg = '';
			// Get POST data
			$title = Input::post('title');
			$description = Input::post('description');
			$contents = Input::post('contents');
			$videourl = Input::post('videourl');
			$category_id = Input::post('category');
			$is_public = Input::post('visibility');
			$language = Input::post('language');

			// Write POST data to new tutorial without saving it
			$tutorial = Model_Tutorial::forge()->set(array(
				'title' => $title,
				'description' => $description,
				'contents' => $contents,
				'videourl' => $videourl,
				'category_id' => $category_id,
				'author_id' => $this->current_user['id'],
				'is_public' => $is_public,
				'views' => '0',
				'language' => $language,
			));

			if(mb_strlen($title)>50||mb_strlen($title)<5) 
			{
				$validated = false;
				$error_msg .= '<li>'.__('TITLE_PARAMS').'</li>';
			}

			if(mb_strlen($description)<30||mb_strlen($description)>400) {
				$validated = false;
				$error_msg .= '<li>'.__('DESCRIPTION_PARAMS').'</li>';
			}

			if(mb_strlen($contents)>2000) {
				$validated = false;
				$error_msg .= '<li>'.__('CONTENTS_PARAMS').'</li>';
			}

			// Look for category
			$categorie = Model_Category::find('first', array('where' => array(array('id', $category_id))));
			if(!count($categorie))
			{
				$validated = false;
				$error_msg .= '<li>'.__('CATEGORY_PARAMS').'</li>';
			}

			if(is_null($is_public))
			{
				$validated = false;
				$error_msg .= '<li>'.__('VISIBILITY_PARAMS').'</li>';
			}

			// If youtube link was not valid
			if(!Helper::decode_video_url($videourl)) {

				// Write data to allow retrying without loosing entered form data
				$post_data['title'] = $title;
				$post_data['description'] = $description;
				$post_data['contents'] = $contents;
				$post_data['videourl'] = $videourl;
				$post_data['category'] = $category_id;
				if($is_public) {
					$post_data['visibility1'] = true; }
				else {
					$post_data['visibility0'] = true; }

				if($language=='en') {
					$post_data['language_en'] = true; }
				else {
					$post_data['language_lv'] = true; }
				


				// Set flash and reload register page
				$validated = false;
				$error_msg .= '<li>'.__('VIDEO_URL_PARAMS').'</li>';
			}

			// If tests OK, try to save
			if($validated) {
				if($tutorial->save())
				{
					Session::set_flash('success', __('NEW_TUTORIAL_SUCCESS'));
					Response::redirect('/tutorials/'.$tutorial->id);
				}
			}
			// If couldn't save for some unknown reason
			else {
				// Write data to allow retrying without loosing entered form data
				$post_data['title'] = $title;
				$post_data['description'] = $description;
				$post_data['contents'] = $contents;
				$post_data['videourl'] = $videourl;
				$post_data['category'] = $category_id;
				if($is_public) {
					$post_data['visibility1'] = true; }
				else {
					$post_data['visibility0'] = true; }

				if($language=='en') {
					$post_data['language_en'] = true; }
				else {
					$post_data['language_lv'] = true; }
		
				$error_msg = __('TUTORIAL_ERROR').'<ul>' . $error_msg . '</ul>';

				// Set flash and reload register page
				Session::set_flash('error', $error_msg);
				Response::redirect('/tutorials/create/'.$tutorial->id, array('post_data' => $post_data));
			}
		}
		// Select category list for 
		$categories = Model_Category::find('all');

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = 'Tutorials &raquo; Create';
		$this->template->content = View::forge('tutorials/create', array(
			'categories' => $categories,
			'post_data' => $post_data,
			));
	}

	public function action_edit($tutorial_id)
	{	
		Lang::load('tutorial_edit');
		$categories = Model_Category::find('all');
		$tutorial = Model_Tutorial::find_by_id($tutorial_id);
		if(!count($tutorial)) { // No tutorials found, might be deleted.
			Response::redirect_back('/stream', 'refresh');
		}

		// Disallow editing, if this isnt your tuturial (unless you are admin or moderetor)
		if(($tutorial->author_id!=$this->current_user['id'])&&($this->current_user['group_id']<50)) { // -1=ban, 1=user, 50=moderator, 100=admin
			Response::redirect_back('/stream', 'refresh');
		}

		// Try to update tutorial
		if(Input::method() == 'POST'&&Security::check_token())
		{	
			$validated = true;
			$error_msg = '';

			// Get POST data
			$title = Input::post('title');
			$description = Input::post('description');
			$contents = Input::post('contents');
			$videourl = Input::post('videourl');
			$category_id = Input::post('category');
			$is_public = Input::post('visibility');
			$language = Input::post('language');

			// Set new values
			$tutorial->title = $title;
			$tutorial->description = $description;
			$tutorial->contents = $contents;
			$tutorial->videourl = $videourl;
			$tutorial->category_id = $category_id;
			$tutorial->is_public = $is_public;
			$tutorial->language = $language;

			if(mb_strlen($title)>50||mb_strlen($title)<5) 
			{
				$validated = false;
				$error_msg .= '<li>'.__('TITLE_PARAMS').'</li>';
			}

			if(mb_strlen($description)<30||mb_strlen($description)>400) {
				$validated = false;
				$error_msg .= '<li>'.__('DESCRIPTION_PARAMS').'</li>';
			}

			if(mb_strlen($contents)>2000) {
				$validated = false;
				$error_msg .= '<li>'.__('CONTENTS_PARAMS').'</li>';
			}

			// Look for category
			$categorie = Model_Category::find('first', array('where' => array(array('id', $category_id))));
			if(!count($categorie))
			{
				$validated = false;
				$error_msg .= '<li>'.__('CATEGORY_PARAMS').'</li>';
			}

			if(is_null($is_public))
			{
				$validated = false;
				$error_msg .= '<li>'.__('VISIBILITY_PARAMS').'</li>';
			}

			// If link was not valid
			if(!Helper::decode_video_url($videourl)) {
				$validated = false;
				$error_msg .= '<li>'.__('VIDEO_URL_PARAMS').'</li>';
			}

			if($validated) {
				// Try to save it
				if($tutorial->save())
				{
					Session::set_flash('success', __('NEW_TUTORIAL_SUCCESS'));
					Response::redirect('/tutorials/'.$tutorial->id);
				}
			}
		}

		if($tutorial->is_public=='1') { $tutorial->is_public = true; }
		else { $tutorial->is_public = false; }

		if($tutorial->language=='en') { $tutorial->language = true; }
		else { $tutorial->language = false; }

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = 'Tutorials &raquo; Edit';
		$this->template->content = View::forge('tutorials/edit', array(
			'categories' => $categories,
			'tutorial' => $tutorial
			));
	}


	public function action_delete($tutorial_id)
	{
		Lang::load('tutorial_edit');
		//Find tutorial
		$tutorial = Model_Tutorial::find_by_id($tutorial_id);
		//If tutorial doesn't exist, redirect to home
		if(!$tutorial) {
			Response::redirect('/stream');
		}
		// Disallow deleteing, if this isnt your tuturial (unless you are admin or moderetor)
		if(($tutorial->author_id!=$this->current_user['id'])&&($this->current_user['group_id']<50)) { // -1=ban, 1=user, 50=moderator, 100=admin
			Response::redirect_back('/stream', 'refresh');
		}
		else if($tutorial) {
			// Delete tutorial
			$tutorial->delete();
		}

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = 'Tutorial deleted!';
		$this->template->content = View::forge('tutorials/delete', array(
			'tutorial' => $tutorial
			));
	}

	public function action_view($tutorial_id)
	{
		Lang::load('tutorial_view');
		$tutorial = Model_Tutorial::find($tutorial_id, array(
                    "related" => array(
                        "user", "comments" => array(
                            "related" => "user"
                        ))
		));

		// If this tutorial doesnt exist, set flash
		if(!count($tutorial)) {
			Session::set_flash('error', __('TUTORIAL_NOT_FOUND'));
			Response::redirect_back('/stream', 'refresh');
		}
		
		// Set up view cookie
		$cookie_id='vk_watched_'.$tutorial_id;
		$cookie_value='1';
		// Get video cookie
		$cookie_time=Config::get('cookies_watch_time');

		// If cookie doesn't exist and this this user video, add watch time and create cookie
		if(Cookie::get($cookie_id)!='1'&&$tutorial['author_id']!==$this->current_user['id']) {
			Cookie::set($cookie_id,$cookie_value,$cookie_time);
			$tutorial->views = $tutorial->views+1;
			$tutorial->save();
		}

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = 'View Tutorial';
		$this->template->content = View::forge('tutorials/view', array(
			'tutorial' => $tutorial
			));
	}

}
