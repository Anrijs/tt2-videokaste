<?php

namespace Fuel\Migrations;

class Create_monkeys
{
	public function up()
	{
		\DBUtil::create_table('monkeys', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'age' => array('constraint' => 11, 'type' => 'int'),
			'weight' => array('type' => 'float'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('monkeys');
	}
}