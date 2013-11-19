<?php

class Model_Category extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'title',
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
	protected static $_table_name = 'categories';

	protected static $_has_many = array(
		'tutorials' => array(
			'key_to' => 'category_id'
			));

}
