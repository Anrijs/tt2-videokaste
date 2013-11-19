<?php 
Class Helper {

public static function visual_name_nav($user_id = null) 
	{
		if($user_id)
		{
			$user = Model_User::find($user_id);
			switch ($user['group_id']) {
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

public static function visual_name($user_id = null) 
	{
		if($user_id)
		{
			$user = Model_User::find($user_id);
			switch ($user['group_id']) {
				case '10':
					return $user->username.' <span text="Pārbaudīts lietotājs" class="glyphicon glyphicon-ok"> </span>';
					break;
				case '100':
					return $user->username.' <span text="Administrators" class="glyphicon glyphicon-leaf"> </span>';
				break;
				default:
					return ' <span>'.$user->username.'</span>';
					break;
			}
		}
	}

}