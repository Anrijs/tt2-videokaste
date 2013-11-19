<?php 

class Controller_Base extends Controller_Template
{
	public function before()
	{
		if(isset($_GET['srch-term'])) {
			Response::redirect('/explore/?srch='.$_GET['srch-term']);
		}
		parent::before();

		if(Auth::check())
		{
			list($driver, $user_id) = Auth::get_user_id();

			$this->current_user = Model_User::find($user_id);
		}
		else
		{
			$this->current_user = null;
		}

		View::set_global('current_user', $this->current_user);
	}

	public function visual_name($user_id = null) 
	{
		if($user_id)
		{
			$user = Model_User::find($user_id);
			switch ($this->current_user['group_id']) {
				case '10':
					return ' <span style="color:#33b5e5;">'.$user->username.' <span text="Pārbaudīts lietotājs" class="glyphicon glyphicon-ok"> </span></span>';
					break;
				case '100':
					return ' <span style="color:#33dd33;">'.$user->username.' <span text="Administrators" class="glyphicon glyphicon-leaf"> </span></span>';
				break;
				default:
					return ' <span>'.$user->username.'</span>';
					break;
			}
		}
	}
}