<?php

class Controller_Users extends Controller_Base
{
	// User profile page
	public function action_view($username = null)
	{
		$user = Model_User::find_by_username($username, array("related" => array("following", "tutorials"=> array("related" => "comments"))));
		if(!$user) {
			Response::redirect('/');
		}
                //
                //$tutorials = Model_Tutorial::
		$this->template->navbar = array('explore' => 'active');
		$this->template->title = $user->username.'\'s profile ';
		$this->template->content = View::forge('users/view', array(
			'user' => $user,
		));
	}

	// Login page and processing
	public function action_login()
	{
		Lang::load('user_auth');
		// If got POST data (user tried to login)
		if(Input::method() == 'POST'&&Security::check_token())
		{
			// Login with input data
			if(Auth::login(Input::post('username'), Input::post('password')))
			{
				Response::redirect('/');
			}
			// Couldn't login, show flash
			else
			{
				Session::set_flash('error', 'Nepareizs lietotājvārds vai parole!');
				Response::redirect('/login');
			}
		}

		$this->template->navbar = array('login' => 'active');
		$this->template->title = 'Login';
		$this->template->content = View::forge('users/login');
	}

	// Log out user, redirect to landing page
	public function action_logout()
	{
		Lang::load('user_auth');
		Auth::logout();

		Session::set_flash('success', __('LOGGED_OUT'));

		Response::redirect('/');
	}

	//Register user
	public function action_create()
	{
		Lang::load('user_auth');
		// If got POST data, atempt to register user
		if(Input::method() == 'POST'&&Security::check_token())
		{
			// Set params
			$validated = true;
			$post_username = Input::post('username');
			$post_email = Input::post('email');
			$post_password = Input::post('password');
			$post_password_confirm = Input::post('c_password');
			$post_language = Input::post('language');

			$error_msg='';
			// Server-side validation
			// Check username length
			if(mb_strlen($post_username)>20||mb_strlen($post_username)<3)
			{
				$validated = false;
				$error_msg .='<li>'.__('USER_USERNAME_PARAMS').'</li>';
			}
			// Check password length
			if(mb_strlen($post_password)<6) 
			{
				$validated = false;
				$error_msg .= '<li>'.__('USER_PASSWORD_PARAMS_1').'</li>';
			}
			// Check if passwords match
			if($post_password!==$post_password_confirm)
			{
				$validated = false;
				$error_msg .='<li>'.__('USER_PASSWORD_PARAMS_2').'</li>';
			}
			// Check if email is valid form
			if(!filter_var($post_email, FILTER_VALIDATE_EMAIL)) {
				$validated = false;
				$error_msg .='<li>'.__('USER_EMAIL_PARAMS').'</li>';
			}
			// Chech if username and/or email is not in use
			$users = DB::select('*')->from('users')->where(strtolower('username'), strtolower($post_username))->or_where(strtolower('email'), strtolower($post_email))->execute();
			$users_cout = count($users);

			// If users not found and validation passed
			if(!$users_cout&&$validated) {
				if(Auth::create_user(Input::post('username'), Input::post('password'), Input::post('email'), 1, Input::post('language')))
				{
					Session::set_flash('success', 'Reģistrācija notika veiksmīgi!');
					// After creating user, do auto-login
					if(Auth::login(Input::post('username'), Input::post('password')))
					{
						Response::redirect('/');
					}
					Response::redirect('/');
				}
			}
			// If something found, username or email is already in use
			// Tell user to try again
			else
			{
				// Ser error message to session
				if($users_cout) 
				{
					$error_msg = '<li>'.__('USER_USERNAME_TAKEN').'</li>' . $error_msg;
				}
				$error_msg = __('USER_FAILED').':<ul>' . $error_msg . '</ul>';
				Session::set_flash('error',$error_msg);
			}
		}

		$this->template->navbar = array('' => '');
		$this->template->title =  __('REGISTER');
		$this->template->content = View::forge('users/create');
	}


	//Edit user
	//Register user
	public function action_edit()
	{
		Lang::load('user_auth');
		// If got POST data, atempt to register user
		if(Input::method() == 'POST'&&Security::check_token())
		{
			// Set params
			$validated = true;
			$post_username = Input::post('username');
			$post_email = Input::post('email');
			$post_old_password = Input::post('old_password');
			$post_new_password = Input::post('password');
			$post_password_confirm = Input::post('c_password');
			$post_language = Input::post('language');

			$error_msg='';
			// Server-side validation
			// Check username length
			if(mb_strlen($post_username)>20||mb_strlen($post_username)<3)
			{
				$validated = false;
				$error_msg .='<li>'.__('USER_USERNAME_PARAMS').'</li>';
			}
			// Check password length
			if(mb_strlen($post_new_password)<6&&$post_new_password!='') 
			{
				$validated = false;
				$error_msg .= '<li>'.__('USER_PASSWORD_PARAMS_1').'</li>';
			}
			// Check if passwords match
			if($post_new_password!==$post_password_confirm)
			{
				$validated = false;
				$error_msg .= '<li>'.__('USER_PASSWORD_PARAMS_2').'</li>';
			}
			// Check if email is valid form
			if(!filter_var($post_email, FILTER_VALIDATE_EMAIL)) {
				$validated = false;
				$error_msg .='<li>'.__('USER_EMAIL_PARAMS').'</li>';
			}

			// Chech if username and/or email is not in use
			$users_cout = 0;
			if(!$post_username==$this->current_user['username']) {
				$users = Model_User::find('all', array(
					'where' => array('username', $post_username)));
				$users_cout += count($users);
			}
			if (!$post_email==$this->current_user['email']) {
				$users = Model_User::find('all', array(
					'where' => array('email', $post_email)));
				$users_cout += count($users);
			}

			// If users not found and validation passed
			if(!$users_cout&&$validated) {
				if($post_new_password=='') {
					$post_new_password = $post_old_password;
				}
				if(Auth::change_password($post_old_password, $post_new_password, $username = $this->current_user['username'])) {
					$this->current_user->username = $post_username;
					$this->current_user->email = $post_email;
					$this->current_user->save();
					Session::set_flash('success',__('USER_UPDATE_SUCCESS'));
					Auth::login($post_username, $post_new_password);
					Response::redirect('/u/'.$post_username);
				}
				else {
					$error_msg = '<li>'.__('USER_PASSWORD_PARAMS_3').'</li>' . $error_msg;
					Session::set_flash('error',$error_msg);
				}
			}
			// If something found, username or email is already in use
			// Tell user to try again
			else
			{
				// Ser error message to session
				if($users_cout) 
				{
					$error_msg = '<li>'.__('USER_USERNAME_TAKEN').'</li>' . $error_msg;
				}
				$error_msg = __('USER_FAILED').':<ul>' . $error_msg . '</ul>';
				Session::set_flash('error',$error_msg);
			}
		}

		$this->template->navbar = array('' => '');
		$this->template->title =  __('USER_EDIT');
		$this->template->content = View::forge('users/edit');
	}
        
        
        public function action_follow($username=null)
        {
            if ($this->current_user && $username)
            {
                $user = Model_User::find_by_username($username);
                if($user->id == $this->current_user->id)
                {
                    Response::redirect("/u/".$this->current_user->username);
                }
                
                if(!Model_Follower::find("all", array("where"=>array("following_id" => $user->id, "follower_id" => $this->current_user->id))))
                {
                    $follower = new Model_follower;
                    $follower->follower_id = $this->current_user->id;
                    $follower->following_id = $user->id;
                    $follower->save();
                    Response::redirect('/u/'.$username);
                }
                Response::redirect('u'.$username);
            }
            Response::redirect('/');            
        }
        
        public function action_unfollow($username=null)
        {
            if ($this->current_user && $username)
            {
                $user = Model_User::find_by_username($username);
                if($user->id == $this->current_user->id)
                {
                    Response::redirect("/u/".$this->current_user->username);
                }
                $follower = Model_Follower::find("first", array("where"=>array("following_id" => $user->id, "follower_id" => $this->current_user->id)));
                
                if($follower)
                {
                    $follower->delete();
                    Response::redirect('/u/'.$username);
                }
                Response::redirect('u'.$username);
            }
            Response::redirect('/');        
        }

}
