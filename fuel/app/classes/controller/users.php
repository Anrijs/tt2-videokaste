<?php

class Controller_Users extends Controller_Base
{

	public function action_view($id = null)
	{
		$user = Model_User::find($id);

		$this->template->navbar = array('explore' => 'active');
		$this->template->title = $user->username.'\'s profile ';
		$this->template->content = View::forge('users/view', array(
			'user' => $user,
		));
	}

	public function action_login()
	{
		if(Input::method() == 'POST')
		{
			if(Auth::login(Input::post('username'), Input::post('password')))
			{
				Session::set_flash('success', 'You have logged in!');

				Response::redirect('/');
			}
			else
			{
				exit('Invalid login');
			}
		}

		$this->template->navbar = array('login' => 'active');
		$this->template->title = 'Login';
		$this->template->content = View::forge('users/login');
	}

	public function action_logout()
	{
		Auth::logout();

		Session::set_flash('success', 'You have logge out!');

		Response::redirect('/');
	}

	public function action_create()
	{
		$error = NULL;
		if(Input::method() == 'POST')
		{
			$users = DB::select('*')->from('users')->where(strtolower('username'), strtolower(Input::post('username')))->or_where(strtolower('email'), strtolower(Input::post('email')))->execute();
			$users_cout = count($users);
			if(!$users_cout) {
				if(Auth::create_user(Input::post('username'), Input::post('password'), Input::post('email'), 1))
				{
					Session::set_flash('success', 'You have registred in!');
					if(Auth::login(Input::post('username'), Input::post('password')))
					{
						Session::set_flash('success', 'You have logged in!');
						Response::redirect('/');
					}
					Response::redirect('/');
				}
			}
			else
			{
				$error = 'Username or email is already in use.<br>Please try another one';
			}
		}

		$this->template->navbar = array('' => '');
		$this->template->title = 'Login';
		$this->template->content = View::forge('users/create', array(
				'error' => $error
				),false);
	}


}
