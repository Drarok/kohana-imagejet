<?php

/**
 * Add a route for imagejet-served images.
 */
Route::set('imagejet', 'imagejet/<group>/<image_path>', array('image_path' => '(.*?)'))
	->defaults(array(
		'controller' => 'imagejet',
		'action' => 'thumbnail',
	));