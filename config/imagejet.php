<?php

return array(
	/**
	 * Where the full-size image files are stored.
	 */
	'image_root' => DOCROOT . 'imagejet/',

	/**
	 * Define width and height "groups" here.
	 * Note that the generated images will be this size with the source image
	 * centred, and specified background and border colours.
	 */
	'default' => array(
		'width' => 150,
		'height' => 150,
		'background' => array(255, 255, 255),
		'border' => array(0, 0, 0),
	),
);