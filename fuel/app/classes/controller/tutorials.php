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
		// TODO: paginate

		//Check if it's a valid category
		$error_msg = '';
		$search_users = '';
		// If nothing found for this id and id not NULL, to allow all tuts page
		if(!count(Model_Category::find($this->param('id')))&&$this->param('id')!==NULL) {
			$error_msg .= '<div class="well">';
			$error_msg .= '<h2> Sorry, but this category doesn\'t exisy (yet)</h2>';
			$error_msg .= '<p class="hidden-xs"> <span class="glyphicon glyphicon-chevron-left"> </span> Please choose any of categories from list at left side of the page! </p>';
			$error_msg .= '<p class="visible-xs"> <span class="glyphicon glyphicon-chevron-up"> </span> Please choose any of categories from list at top of the page! </p>';
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
		if(!$this->current_user) {
			Response::redirect('/');
		} 

		// Paginate
		$page_limit=Config::get('paginate_single'); // Page limit from settings
		$paginate=0;	// Tutorial offset for SQL

		// If page is set, change current page
		if(isset($_GET['page'])) {
			$paginate=($_GET['page']-1)*$page_limit;
		}
		if($paginate<1) {
			$paginate=0;
		}


		// Make subquery to select users followed by current_user
		$mini = DB::select('following_id')->from('followers')->where('follower_id', $this->current_user->id);
		$exp = DB::expr('`tutorials`.author_id');
		// Now select their real usernames
		$mini_author = DB::select('username')->from('users')->where('id',$exp);

		// Subquery to get category data for tutorial
		$exp2 = DB::expr('`tutorials`.category_id');
		$mini_category = DB::select('title')->from('categories')->where('id',$exp);

		// Get tutorials count, then calculate how many pages there will be (used to disable buttons)
		$tutorials_count = DB::select('*')->from('tutorials')->where('author_id', 'IN', $mini)->order_by('created_at','DESC')->execute()->as_array();
		$page_count=floor(count($tutorials_count)/$page_limit)+1;

		// Select tutorials from page
		//$tutorials = DB::select('*',array($mini_author, 'username'),array($mini_category, 'category'))->from('tutorials')->where('author_id', 'IN', $mini)->order_by('created_at','DESC')->limit($page_limit)->offset($paginate)->execute()->as_array();
		
		$tutorials = Model_Tutorial::find('all', array("related" => array("user", "category"),'order_by' => array('created_at' => 'desc'),'limit' => $page_limit, 'offset' => $paginate));
		
		$this->template->navbar = array('stream' => 'active');
		$this->template->title = 'Videokaste.lv - '.$this->current_user->username;
		$this->template->content = View::forge('tutorials/stream', array(
				'tutorials' => $tutorials, 'page_count' => $page_count
				));
	}

	public function action_create()
	{	
		// Data that will contain input values in case of registration failure
		$post_data['title'] = '';
		$post_data['description'] = '';
		$post_data['contents'] = '';
		$post_data['videourl'] = '';
		$post_data['category'] = '';
		$post_data['visibility0'] = '';
		$post_data['visibility1'] = '';

		// If have POST data atempt tutorial create
		if(Input::method() == 'POST')
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

			// Write POST data to new tutorial without saving it
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

			if(mb_strlen($title)>20||mb_strlen($title)<5) 
			{
				$validated = false;
				$error_msg .= '<li>Virsrakstam jābūt 5-50 simbolus garam</li>';
			}

			if(mb_strlen($description)<30||mb_strlen($description)>400) {
				$validated = false;
				$error_msg .= '<li>Aprakstam jābūt 30-400 simbolus garam</li>';
			}

			if(mb_strlen($contents)>2000) {
				$validated = false;
				$error_msg .= '<li>Papildus informācija nevar būt garāka par 2000 simpoliem</li>';
			}

			// Look for category
			$categorie = DB::select('*')->from('categories')->where('id',$category_id)->execute();
			if(!count($categorie))
			{
				$validated = false;
				$error_msg .= '<li>Nav naorādīta pareiza video kategorija</li>';
			}

			if(is_null($is_public))
			{
				$validated = false;
				$error_msg .= '<li>Nav norādīta pamācības redzamība</li>';
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
					$post_data['visibility1'] = 'checked'; }
				else {
					$post_data['visibility0'] = 'checked'; }

				// Set flash and reload register page
				$validated = false;
				$error_msg .= '<li>Video adrese ir ievadīta nepareizi</li>';
			}

			// If tests OK, try to save
			if($validated) {
				if($tutorial->save())
				{
					Session::set_flash('success', 'Pamācība ir veiksmīgi pievienota!');
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
					$post_data['visibility1'] = 'checked'; }
				else {
					$post_data['visibility0'] = 'checked'; }

				$error_msg = 'Neizdevās pievienot pamācību šādu iemeslu dēļ:<ul>' . $error_msg . '</ul>';

				// Set flash and reload register page
				Session::set_flash('error', $error_msg);
				Response::redirect('/tutorials/create/'.$tutorial->id, array('post_data' => $post_data));
			}
		}
		// Select category list for 
		$categories = DB::select('*')->from('categories')->order_by('title','ASC')->execute()->as_array();

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = 'Tutorials &raquo; Create';
		$this->template->content = View::forge('tutorials/create', array(
			'categories' => $categories,
			'post_data' => $post_data,
			));
	}

	public function action_edit($tutorial_id)
	{	
		

		$categories = DB::select('*')->from('categories')->order_by('title','ASC')->execute()->as_array();
		$tutorial = Model_Tutorial::find_by_id($tutorial_id);
		if(!count($tutorial)) { // No tutorials found, might be deleted.
			Response::redirect_back('/stream', 'refresh');
		}

		// Disallow editing, if this isnt your tuturial (unless you are admin or moderetor)
		if(($tutorial->author_id!=$this->current_user['id'])&&($this->current_user['group_id']<50)) { // -1=ban, 1=user, 50=moderator, 100=admin
			Response::redirect_back('/stream', 'refresh');
		}

		// Try to update tutorial
		if(Input::method() == 'POST')
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

			// Set new values
			$tutorial->title = $title;
			$tutorial->description = $description;
			$tutorial->contents = $contents;
			$tutorial->videourl = $videourl;
			$tutorial->category_id = $category_id;
			$tutorial->is_public = $is_public;

			if(mb_strlen($title)>20||mb_strlen($title)<5) 
			{
				$validated = false;
				$error_msg .= '<li>Virsrakstam jābūt 5-50 simbolus garam</li>';
			}

			if(mb_strlen($description)<30||mb_strlen($description)>400) {
				$validated = false;
				$error_msg .= '<li>Aprakstam jābūt 30-400 simbolus garam</li>';
			}

			if(mb_strlen($contents)>2000) {
				$validated = false;
				$error_msg .= '<li>Papildus informācija nevar būt garāka par 2000 simpoliem</li>';
			}

			// Look for category
			$categorie = DB::select('*')->from('categories')->where('id',$category_id)->execute()->as_array();
			if(!count($categorie))
			{
				$validated = false;
				$error_msg .= '<li>Nav naorādīta pareiza video kategorija</li>';
			}

			if(is_null($is_public))
			{
				$validated = false;
				$error_msg .= '<li>Nav norādīta pamācības redzamība</li>';
			}

			// If link was not valid
			if(!Helper::decode_video_url($videourl)) {
				$validated = false;
				$error_msg .= '<li>Video adrese ir ievadīta nepareizi</li>';
			}

			if($validated) {
				// Try to save it
				if($tutorial->save())
				{
					Session::set_flash('success', 'Pamācība ir veiksmīgi sglabāta!');
					Response::redirect('/tutorials/'.$tutorial->id);
				}
			}
			else {
				// Write data to allow retrying without loosing entered form data
				$post_data['title'] = $title;
				$post_data['description'] = $description;
				$post_data['contents'] = $contents;
				$post_data['videourl'] = $videourl;
				$post_data['category'] = $category_id;
				if($is_public) {
					$post_data['visibility1'] = 'checked'; }
				else {
					$post_data['visibility0'] = 'checked'; }

				$error_msg = 'Neizdevās pievienot pamācību šādu iemeslu dēļ:<ul>' . $error_msg . '</ul>';

				// Set flash and reload register page
				Session::set_flash('error', $error_msg);
				Response::redirect('/tutorials/edit/'.$tutorial->id, array('post_data' => $post_data, 'categories' => $categories));
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
		$tutorial = Model_Tutorial::find($tutorial_id, array("related" =>
			    array("user")
		));

		// If this tutorial doesnt exist, set flash
		if(!count($tutorial)) {
			Session::set_flash('error', 'Pamācība netika artasta. Iespējams, tā ir izdzēsta, vai arī nekad nav eksistējusi.');
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
