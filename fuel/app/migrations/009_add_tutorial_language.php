<?php

namespace Fuel\Migrations;

class Add_tutorial_language
{
	public function up()
	{
		\DBUtil::add_fields('tutorials', array(
    		'language' => array('constraint' => 2, 'type' => 'varchar'),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('tutorials', 'language');
	}
}