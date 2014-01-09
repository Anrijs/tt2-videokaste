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
		'group',
        'language',
	);

	protected static $_has_many = array(
                'tutorials' => array(
                'key_from' => 'id',
                'model_to' => 'Model_Tutorial',
                'key_to' => 'author_id',
                'cascade_save' => true,
                'cascade_delete' => false,
                ),
                'followers' => array(
                'key_from' => 'id',
                'model_to' => 'Model_Follower',
                'key_to' => 'follower_id',
                'cascade_save' => false,
                'cascade_delete' => false,
                ),
                'following' => array(
                'key_from' => 'id',
                'model_to' => 'Model_Follower',
                'key_to' => 'following_id',
                'cascade_save' => false,
                'cascade_delete' => false,
                )
                );

}