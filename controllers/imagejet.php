<?php

class ImageJet_Controller extends Controller {
	public function index() {
		echo 'Image root: ', Kohana::config('imagejet.image_root'), PHP_EOL;
	}

	public function __call($method, $args) {
		// Grab the config data for the requested method.
		$config = Kohana::config('imagejet.'.$method);

		// If an array wasn't returned, we should 404.
		if (! is_array($config)) {
			Event::run('system.404');
			exit;
		}

		// Build the full paths.
		$path = DOCROOT . implode(DIRECTORY_SEPARATOR, $args);
		$thumb_path = implode(DIRECTORY_SEPARATOR,
			array(
				rtrim(Kohana::config('imagejet.image_root'), '\/'),
				$method,
				implode(DIRECTORY_SEPARATOR, $args)
			)
		);

		// If there's no such file, bail.
		if (! file_exists($path)) {
			Event::run('system.404');
			exit;
		}

		// We have all we need, so let's do this!
		$image = new ImageJet($path);
		$image->thumbnail($config, $thumb_path);
	}
}
