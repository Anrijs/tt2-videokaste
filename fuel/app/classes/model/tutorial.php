<?php

class Model_Tutorial extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'title',
		'description',
		'contents',
		'author_id',
		'category_id',
		'is_public',
		'videourl',
		'views',
		'created_at',
		'updated_at',
		'language',
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
	protected static $_table_name = 'tutorials';
	
	protected static $_has_many = array(
        'comments' => array(
            'model_to' => 'Model_Comment',
            'key_from' => 'id',
            'key_to' => 'tutorial_id',           
            'cascade_save' => true,
            'cascade_delete' => false,
        )
	);

	protected static $_belongs_to = array(
	'user' => array(
	    'key_from' => 'author_id',
	    'model_to' => 'Model_User',
	    'key_to' => 'id'
    ),
    'category' => array(
	    'key_from' => 'category_id',
	    'model_to' => 'Model_Category',
	    'key_to' => 'id'
    ));
}
