<?php

class ImageJet_Core extends Image {
	public function thumbnail($config, $new_path) {
		// If the config is a string, load the array.
		if (is_string($config)) {
			$config = Kohana::config('imagefly.' . $config);
		}

		// If it's not an array, then bail.
		if (! is_array($config)) {
			throw new Exception('Invalid image config');
		}

		// Ensure the path exists.
		$dir = dirname($new_path);
		if (! is_dir($dir)) {
			mkdir($dir, 0775, TRUE);
		}

		// Resize and save the image.
		$this->resize($config['width'], $config['height']);
		$this->save($new_path);

		// Create the new image resource.
		$image = imagecreatetruecolor($w = $config['width'], $h = $config['height']);

		// Fill with the background colour.
		list($r, $g, $b) = $config['background'];
		$background = imagecolorallocate($image, $r, $g, $b);
		imagefill($image, 0, 0, $background);

		// Bring in the original image.
		$orig = imagecreatefromjpeg($new_path);
		$sx = imagesx($orig); $sy = imagesy($orig);
		// imagealphablending($orig, TRUE);
		imagecopy($image, $orig, ($w - $sx) / 2, ($h - $sy) / 2, 0, 0, $sx, $sy);

		// Add the border.
		if ((bool) $config['border']) {
			list ($r, $g, $b) = $config['border'];
			$border = imagecolorallocate($image, $r, $g, $b);
			imagerectangle($image, 0, 0, $w - 1, $h - 1, $border);
		}

		// Re-save our processed image.
		imagejpeg($image, $new_path);

		// Output the file.
		header('Content-Type: image/jpeg');
		$file = fopen($new_path, 'r');
		fpassthru($file);
	}
}
