<?php

class Controller_ImageJet extends Controller {
	public function action_thumbnail() {
		// Grab the parameters.
		$group = $this->request->param('group');
		$image_path = $this->request->param('image_path');

		// Grab the config data for the requested group.
		$config = Kohana::$config->load('imagejet.' . $group);

		// If an array wasn't returned, we should 404.
		if (! is_array($config)) {
			throw new Exception('No such configuration: ' . $group);
		}

		// Build the full paths.
		$full_path = DOCROOT . $image_path;
		$thumb_path = implode(DIRECTORY_SEPARATOR,
			array(
				rtrim(Kohana::$config->load('imagejet.image_root'), '\/'),
				$group,
				$image_path
			)
		);

		// If there's no such file, bail.
		if (! file_exists($full_path)) {
			throw new Exception('No such file: ' . $path);
		}

		// We have all we need, so let's do this!
		$image = new ImageJet($full_path);
		$image->thumbnail($config, $thumb_path);
	}
}