<?php

class Controller_Tutorials extends Controller_Base
{
	public function action_index() {
		if(!$this->current_user) {
			Response::redirect('/');
		} 
		Response::redirect('/stream');
	}

	public function action_explore($category_id = null)
	{
		// TODO: paginate

		//check if it's a valid category
		$error_msg = '';
		$search_users = '';
		if(!count(DB::select('*')->from('categories')->where('id', $category_id)->execute())&&$this->param('id')!==NULL) {
			$error_msg .= '<div class="well">';
			$error_msg .= '<h2> Sorry, but this category doesn\'t exisy (yet)</h2>';
			$error_msg .= '<p class="hidden-xs"> <span class="glyphicon glyphicon-chevron-left"> </span> Please choose any of categories from list at left side of the page! </p>';
			$error_msg .= '<p class="visible-xs"> <span class="glyphicon glyphicon-chevron-up"> </span> Please choose any of categories from list at top of the page! </p>';
			$error_msg .= '</div>';
		}

		//Make subquery for additional details
		$exp = DB::expr('`tutorials`.author_id');
		$mini_author = DB::select('username')->from('users')->where('id',$exp);

		$exp2 = DB::expr('`tutorials`.category_id');
		$mini_category = DB::select('title')->from('categories')->where('id',$exp2);

		// Select depending on selected category or search query
		if(isset($_GET['srch'])&&$_GET['srch']!==NULL) {
			$term = $_GET['srch'];
			$tutorials = DB::select('*', array($mini_author, 'username'),array($mini_category, 'category'))->from('tutorials')->
				where('title', 'LIKE', '%'.$term.'%')->
				or_where('description', 'LIKE', '%'.$term.'%')->
				order_by('created_at','DESC')->execute()->as_array();
			$search_users = DB::select('*')->from('users')->where('username', 'LIKE', '%'.$term.'%')->order_by('username', 'ASC')->execute()->as_array();
			$active_category = '0';
		}
		else if($this->param('id')) {
			$tutorials = DB::select('*', array($mini_author, 'username'),array($mini_category, 'category'))->from('tutorials')->where('category_id', $this->param('id'))->order_by('created_at','DESC')->execute()->as_array();
			$active_category = $this->param('id');
		}
		else {
			$tutorials = DB::select('*', array($mini_author, 'username'),array($mini_category, 'category'))->from('tutorials')->order_by('created_at','DESC')->execute()->as_array();
			$active_category = '0';
		}

		// Get full category list
		$categories = DB::select('*')->from('categories')->order_by('title','ASC')->execute()->as_array();

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
		if(!$this->current_user) {
			Response::redirect('/');
		} 

		// Plain SQL
		// SELECT * FROM `tutorials` WHERE `author_id` IN (SELECT `following_id` FROM `followers` WHERE `follower_id` LIKE 1)
		// $this->current_user->id

		//New - gimme also author name!
		/* 
			SELECT *,(SELECT `username` FROM `users` WHERE `id` LIKE `tutorials`.`author_id`) as author,
					 (SELECT `title` FROM `categories` WHERE `id` LIKE `tutorials`.`category_id`) as category 
					 FROM `tutorials` WHERE `author_id` IN 
					 (SELECT `following_id` FROM `followers` WHERE `follower_id` LIKE 2)
		*/

		$mini = DB::select('following_id')->from('followers')->where('follower_id', $this->current_user->id);
		$exp = DB::expr('`tutorials`.author_id');
		$mini_author = DB::select('username')->from('users')->where('id',$exp);

		$exp2 = DB::expr('`tutorials`.category_id');
		$mini_category = DB::select('title')->from('categories')->where('id',$exp);

		$page_limit=5;
		$paginate=0;
		if(isset($_GET['page'])) {
			$paginate=($_GET['page']-1)*$page_limit;
		}
		if($paginate<1) {
			$paginate=0;
		}

		$tutorials_count = DB::select('*')->from('tutorials')->where('author_id', 'IN', $mini)->order_by('created_at','DESC')->execute()->as_array();
		$page_count=floor(count($tutorials_count)/$page_limit)+1;

		$tutorials = DB::select('*',array($mini_author, 'username'),array($mini_category, 'category'))->from('tutorials')->where('author_id', 'IN', $mini)->order_by('created_at','DESC')->limit($page_limit)->offset($paginate)->execute()->as_array();
		
		$this->template->navbar = array('stream' => 'active');
		$this->template->title = 'Videokaste.lv - '.$this->current_user->username;
		$this->template->content = View::forge('tutorials/stream', array(
				'tutorials' => $tutorials, 'page_count' => $page_count
				));
	}

	public function action_create()
	{	
		$error = NULL;
		if(Input::method() == 'POST')
		{
			$title = Input::post('title');
			$description = Input::post('description');
			$contents = Input::post('contents');
			$videourl = Input::post('videourl');
			$category_id = Input::post('category');
			$is_public = Input::post('visibility');
			//$users = DB::select('*')->from('users')->where(strtolower('username'), strtolower(Input::post('username')))->or_where(strtolower('email'), strtolower(Input::post('email')))->execute();
			//$users_cout = count($users);
			$tutorial = Model_Tutorial::forge()->set(array(
				'title' => $title,
				'description' => $description,
				'contents' => $contents,
				'videourl' => $videourl,
				'category_id' => $category_id,
				'author_id' => $this->current_user['id'],
				'is_public' => $is_public,
				'views' => '0' 
			));

			if($tutorial->save())
			{
				Session::set_flash('success', 'Pamācība ir veiksmīgi pievienota!');
				Response::redirect('/tutorials/'.$tutorial->id);
			}
		}

		$categories = DB::select('*')->from('categories')->order_by('title','ASC')->execute()->as_array();
		$this->template->navbar = array('explore' => 'active');
		$this->template->title = 'Tutorials &raquo; Create';
		$this->template->content = View::forge('tutorials/create', array(
			'categories' => $categories
			));
	}

	public function action_edit($tutorial_id)
	{	
		
		$categories = DB::select('*')->from('categories')->order_by('title','ASC')->execute()->as_array();
		$tutorial = Model_Tutorial::find_by_id($tutorial_id);
		if(!count($tutorial)) { // No tutorials found, might be deleted.
			Response::redirect_back('/stream', 'refresh');
		}

		if(($tutorial->author_id!=$this->current_user['id'])&&($this->current_user['group_id']<50)) { // -1=ban, 1=user, 50=moderator, 100=admin
			Response::redirect_back('/stream', 'refresh');
		}

		if(Input::method() == 'POST')
		{	

			$title = Input::post('title');
			$description = Input::post('description');
			$contents = Input::post('contents');
			$videourl = Input::post('videourl');
			$category_id = Input::post('category');
			$is_public = Input::post('visibility');

			$tutorial->title = $title;
			$tutorial->description = $description;
			$tutorial->contents = $contents;
			$tutorial->videourl = $videourl;
			$tutorial->category_id = $category_id;
			$tutorial->is_public = $is_public;

			if($tutorial->save())
			{
				Session::set_flash('success', 'Pamācība ir veiksmīgi sglabāta!');
				Response::redirect('/tutorials/'.$tutorial->id);
			}
		}

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = 'Tutorials &raquo; Edit';
		$this->template->content = View::forge('tutorials/edit', array(
			'categories' => $categories,
			'tutorial' => $tutorial
			));
	}

	public function action_delete($tutorial_id)
	{
		//Find tutorial
		$tutorial = Model_Tutorial::find_by_id($tutorial_id);
		//Get permissions
		if(!$tutorial) {
			Response::redirect('/stream');
		}
		if(($tutorial->author_id!=$this->current_user['id'])&&($this->current_user['group_id']<50)) { // -1=ban, 1=user, 50=moderator, 100=admin
			Response::redirect_back('/stream', 'refresh');
		}

		//delete it!
		if($tutorial) {
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
		$categories = DB::select('*')->from('categories')->order_by('title','ASC')->execute()->as_array();
		$tutorial = Model_Tutorial::find_by_id($tutorial_id);

		if(!count($tutorial)) {
			Session::set_flash('error', 'Pamācība netika artasta. Iespējams, tā ir izdzēsta, vai arī nekad nav eksistējusi.');
			Response::redirect_back('/stream', 'refresh');
		}
		

		$author = Model_User::find_by_id($tutorial['author_id']);
		
		$cookie_id='vk_watched_'.$tutorial_id;
		$cookie_value='1';
		$cookie_time=Config::get('cookies_watch_time');

		if(Cookie::get($cookie_id)!='1'&&$tutorial['author_id']!==$this->current_user['id']) { // Not seen
			Cookie::set($cookie_id,$cookie_value,$cookie_time);
			$tutorial->views = $tutorial->views+1;
			$tutorial->save();
		}

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = 'View Tutorial';
		$this->template->content = View::forge('tutorials/view', array(
			'categories' => $categories,
			'tutorial' => $tutorial,
			'author' => $author
			));
	}

}
