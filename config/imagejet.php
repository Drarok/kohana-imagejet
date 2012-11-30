<?php

return array(
	// Where the full-size image files are stored. *Must* be in the DOCROOT somewhere.
	'image-root' => DOCROOT . 'imagejet/',

	// If we need to create folders, what permissions should they have?
	'folder-permissions' => 0755,

	/**
	 * Define width and height "groups" here.
	 * Note that the generated images will be this size with the source image
	 * centred, and specified background and border colours.
	 */
	'default' => array(
		// Width of the generated thumbnails.
		'width' => 150,

		// Height of the generated thumbnails.
		'height' => 150,

		// Background colour (for non-square images).
		'background' => array(255, 255, 255),

		// Border colour to apply. Set to FALSE to disable.
		'border' => array(0, 0, 0),
	),
);