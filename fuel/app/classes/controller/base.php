<?php 

class Controller_Base extends Controller_Template
{
	public function before()
	{
		// If user is trying to search for something, redirect to explore and 
		//search for it
		if(isset($_GET['srch-term'])) {
			Response::redirect('/explore/?srch='.$_GET['srch-term']);
		}

		parent::before();
		// If auth-ed, Set current user data to current_user array
		if(Auth::check())
		{
			list($driver, $user_id) = Auth::get_user_id();

			$this->current_user = Model_User::find($user_id);
		}
		// Else set it to null
		else
		{
			$this->current_user = null;
		}

		View::set_global('current_user', $this->current_user);
	}
}