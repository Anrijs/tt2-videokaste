<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	//'users' => 'users/index',
	'explore' => 'tutorials/explore',
	'stream' => 'tutorials/stream',
	'explore/:id' => 'tutorials/explore/$1',
	'about' => 'welcome/about',
	'tutorials/create' => 'tutorials/create',
	'tutorials/edit/:id' => 'tutorials/edit/$1',
	'tutorials/delete/:id' => 'tutorials/delete/$1',
	'tutorials/:id' => 'tutorials/view/$1',
	'u/:username' => 'users/view/$1',
	
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);