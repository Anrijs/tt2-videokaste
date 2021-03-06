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

			// Lang
			Config::set('language', $this->current_user->language);
		}
		// Else set it to null
		else
		{
			$this->current_user = null;


			//Lang
			if(!Cookie::get("videokaste_language")=='lv') {
				$lang = 'en';
				$head = Input::headers( 'Accept-Language');
				if (strpos($head, 'lv')) {
					$lang = 'lv';
				}
            	
           
      		  Config::set('language', $lang);
      		  $cookie_time=Config::get('cookie_language_time');
      		  Cookie::set('language', $lang, $cookie_time);
      		}
      		else {
      		  Config::set('language', Cookie::get("videokaste_language"));
      		}
		}

		Lang::load("main");
		View::set_global('current_user', $this->current_user);
	}
}