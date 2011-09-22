<?php

// Module initialisation.

Route::set(
	'imagejet',
	'imagejet(/<method>(/<image_path>))',
	array('image_path' => '(.*?)')
)
->defaults(array(
	'controller' => 'imagejet',
	'method' => 'default',
	'action' => 'thumbnail',
));