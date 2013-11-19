<?php

class Controller_Welcome extends Controller_Base
{
	public function action_index()
	{	
		
		if($this->current_user) {
			// DO if signed in!
			Response::redirect('/stream');
		}
		$total_users = count(DB::select('*')->from('users')->execute());
		$total_tutorials = count(DB::select('*')->from('tutorials')->execute());
		
		$this->template->title = 'Videokaste.lv';
		$this->template->navbar = array('stream' => 'active');
		$this->template->content = View::forge('welcome/index', array(
			'total_tutorials' => $total_tutorials,
			'total_users' => $total_users
			));
	}

	public function action_about()
	{
		$this->template->title = 'Videokaste.lv';
		$this->template->navbar = array('none' => 'active');
		$this->template->content = View::forge('welcome/about');
	}

}
