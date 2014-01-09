<?php

class Model_Comment extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'comment',
		'user_id',
		'tutorial_id',
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
        protected static $_belongs_to = 
                array(
                    'tutorial' => array (
                        'key_from' => 'tutorial_id',
                        'model_to' => 'Model_Tutorial',
                        'key_to' => 'id'
                    ),
                    'user' => array (
                        'key_from' => 'user_id',
                        'model_to' => 'Model_User',
                        'key_to' => 'id'
                    )                    
                );
        
        
	protected static $_table_name = 'comments';

}
