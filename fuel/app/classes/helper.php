<?php 
Class Helper {

	public static function visual_name_nav($user_id = null) 
	{
		if($user_id)
		{
			$user = Model_User::find($user_id);
			switch ($user['group']) {
				case '10':
					return ' <span style="color:#33b5e5;">'.$user->username.' <span title="'.__('VERIFIED_USER').'" class="glyphicon glyphicon-ok"> </span></span>';
					break;
				case '50':
					return ' <span style="color:#33b5e5;">'.$user->username.' <span title="'.__('MODERATOR').'" class="glyphicon glyphicon-cog"> </span></span>';
					break;
				case '100':
					return ' <span style="color:#33dd33;">'.$user->username.' <span title="'.__('ADMINISTRATOR').'" class="glyphicon glyphicon-leaf"> </span></span>';
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
			switch ($user['group']) {
				case '10':
					return $user->username.' <span style="font-size:0.7em;" title="'.__('VERIFIED_USER').'" class="glyphicon glyphicon-ok"> </span>';
					break;
				case '50':
					return $user->username.' <span style="font-size:0.7em;" title="'.__('MODERATOR').'" class="glyphicon glyphicon-cog"> </span>';
					break;
				case '100':
					return $user->username.' <span style="font-size:0.7em;" title="'.__('ADMINISTRATOR').'" class="glyphicon glyphicon-leaf"> </span>';
				break;
				default:
					return ' <span>'.$user->username.'</span>';
					break;
			}
		}
	}

	public static function decode_video_url($url)
	{
		parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		if(isset($my_array_of_vars['v'])) {
			return $my_array_of_vars['v'];
		}
		else return 0;
	}

	public static function get_language($user) {
		if($user) {
			Config::set('language', $user->language);
		}
		else {
			if(!Cookie::get("videokaste_language")=='lv') {
				Config::set('language', 'en');
				$cookie_time=Config::get('cookie_language_time');
				Cookie::set('language', 'en', $cookie_time);
			}
			else {
				Config::set('language', Cookie::get("videokaste_language"));
			}
		}
	}

}