<?php

class Model_User extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'username',
		'email',
		'updated_at',
		'created_at',
		'profile_fields',
		'group_id',
	);

	protected static $_has_many = array(
		'tutorials' => array(
        	'key_from' => 'id',
        	'model_to' => 'Model_Tutorial',
        	'key_to' => 'tutorial_id',
        	'cascade_save' => true,
        	'cascade_delete' => false,
   		)
		);

}