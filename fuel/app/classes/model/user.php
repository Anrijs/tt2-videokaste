<?php

class Model_User extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'username',
		'email',
		'created_at',
		'group_id',
	);

	protected static $_has_many = array(
		'tutorials' => array(
			'key_to' => 'author_id'
			));

}
