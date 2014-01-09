<?php

class Model_Follower extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'follower_id',
		'following_id',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);
	protected static $_table_name = 'followers';
        
        protected static $_belongs_to = array(
        'user' => array(
                'key_from' => 'following_id',
                'model_to' => 'Model_User',
                'key_to' => 'id'
        ));

}
