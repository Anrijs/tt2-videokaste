<?php

namespace Fuel\Migrations;

class Add_user_language
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
    		'language' => array('constraint' => 2, 'type' => 'varchar'),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', 'language');
	}
}