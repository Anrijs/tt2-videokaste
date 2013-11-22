<?php

class Controller_Users extends Controller_Base
{
	// User profile page
	public function action_view($id = null)
	{
		$user = Model_User::find($id);

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
			// Chech if username and/or email is not in use
			$users = DB::select('*')->from('users')->where(strtolower('username'), strtolower(Input::post('username')))->or_where(strtolower('email'), strtolower(Input::post('email')))->execute();
			$users_cout = count($users);
			// If nothing found, register user as username and email is not used
			if(!$users_cout) {
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
				Session::set_flash('error','Lietotājvārds vai epasts jau tiek izmantots.<br>Mēģiniet vēlreiz');
			}
		}

		$this->template->navbar = array('' => '');
		$this->template->title = 'Login';
		$this->template->content = View::forge('users/create');
	}


}
