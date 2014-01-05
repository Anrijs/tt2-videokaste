<?php

class Controller_Users extends Controller_Base
{
	// User profile page
	public function action_view($username = null)
	{
		$user = Model_User::find_by_username($username);

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = $user->username.'\'s profile ';
		$this->template->content = View::forge('users/view', array(
			'user' => $user,
		));
	}

	// Login page and processing
	public function action_login()
	{
		// If got POST data (user tried to login)
		if(Input::method() == 'POST')
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
		Auth::logout();

		Session::set_flash('success', 'You have logge out!');

		Response::redirect('/');
	}

	//Register user
	public function action_create()
	{
		// If got POST data, atempt to register user
		if(Input::method() == 'POST')
		{
			// Set params
			$validated = true;
			$post_username = Input::post('username');
			$post_email = Input::post('email');
			$post_password = Input::post('password');
			$post_password_confirm = Input::post('c_password');

			$error_msg='';
			// Server-side validation
			// Check username length
			if(mb_strlen($post_username)>20||mb_strlen($post_username)<4)
			{
				$validated = false;
				$error_msg .='<li>Lietotājvārdam jābūt 3-20 simbolus garam </li>';
			}
			// Check password length
			if(mb_strlen($post_password)<6) 
			{
				$validated = false;
				$error_msg .= '<li>Ievadītajai parolei jābūt vismaz 6 simbolus garai </li>';
			}
			// Check if passwords match
			if($post_password!==$post_password_confirm)
			{
				$validated = false;
				$error_msg .='<li>Abām parolēm ir jāsakrīt </li>';
			}
			// Check if email is valid form
			if(!filter_var($post_email, FILTER_VALIDATE_EMAIL)) {
				$validated = false;
				$error_msg .='<li>Epasts nav ievadīts pareizi </li>';
			}
			// Chech if username and/or email is not in use
			$users = DB::select('*')->from('users')->where(strtolower('username'), strtolower($post_username))->or_where(strtolower('email'), strtolower($post_email))->execute();
			$users_cout = count($users);

			// If users not found and validation passed
			if(!$users_cout&&$validated) {
				if(Auth::create_user(Input::post('username'), Input::post('password'), Input::post('email'), 1))
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
					$error_msg = '<li>Lietotājvārds vai epasts jau tiek izmantots</li>' . $error_msg;
				}
				$error_msg = 'Reģistrācija neizdevās šādu iemeslu dēļ:<ul>' . $error_msg . '</ul>';
				Session::set_flash('error',$error_msg);
			}
		}

		$this->template->navbar = array('' => '');
		$this->template->title = 'Login';
		$this->template->content = View::forge('users/create');
	}


	//Edit user
	//Register user
	public function action_edit()
	{
		// If got POST data, atempt to register user
		if(Input::method() == 'POST')
		{
			// Only for testing!
			$values = array('email' => Input::post('input2'), 'foo' => 'bar', 'free' => 'beer');
			Auth::update_user($values, $this->current_user['username']);
		}

		$this->template->navbar = array('' => '');
		$this->template->title = 'Login';
		$this->template->content = View::forge('users/edit');
	}

}
