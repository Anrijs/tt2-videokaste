<?php

class Model_Comment extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'email',
		'content'
	);

	// protected static $_belongs_to = array(
	// 'user' => array(
	//     'key_from' => 'author_id',
	//     'model_to' => 'Model_User',
	//     'key_to' => 'id')
    // );


}
