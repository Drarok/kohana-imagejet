<?php

class ImageJet_ImageJet {
	/**
	 * Name of the config group to use for generated thumbnails.
	 *
	 * @var string
	 */
	protected $_group;

	/**
	 * Config cache.
	 *
	 * @var array
	 */
	protected $_config;

	/**
	 * Image root cache.
	 *
	 * @var string
	 */
	protected $_image_root;

	/**
	 * Object constructor.
	 *
	 * @param string $group Config group to use for generated thumbnails.
	 */
	public function __construct($group) {
		$this->_group = $group;
	}

	/**
	 * Create a thumbnail from a given image path.
	 *
	 * @param string $image_path Path to the original image, relative to configured image-root.
	 * @param bool   $output     Pass true to output the generated thumbnail.
	 *
	 * @return void
	 */
	public function thumbnail($image_path, $output = false) {
		// You can't trust anybody these days.
		$image_path = str_replace('..', '', $image_path);

		// Grab the config.
		$config = $this->_get_config();

		// Build the paths to the files.
		$original_path = $this->_get_image_root() . $image_path;
		$thumb_path = $this->_get_image_root() . $this->_group . DIRECTORY_SEPARATOR . $image_path;

		// Ensure the path exists.
		$dir = dirname($thumb_path);
		if (! is_dir($dir)) {
			if (! mkdir($dir, Kohana::$config->load('imagejet.folder-permissions'), TRUE)) {
				throw new Exception('Failed to create directory.');
			}
		}

		// Grab repeated config parameters.
		$width = $config['width'];
		$height = $config['height'];

		// Resize and save to the thumbnail file.
		$image = Image::factory($original_path);
		$image->resize($width, $height);
		$image->save($thumb_path);
		unset($image);

		// Create the new image resource.
		$image = imagecreatetruecolor($width, $height);

		// Fill with the background colour.
		list($r, $g, $b) = $config['background'];
		$background = imagecolorallocate($image, $r, $g, $b);
		imagefill($image, 0, 0, $background);

		// Bring in the thumbnail image, centred in the background.
		$orig = imagecreatefromjpeg($thumb_path);
		$sx = imagesx($orig);
		$sy = imagesy($orig);
		imagecopy($image, $orig, ($width - $sx) / 2, ($height - $sy) / 2, 0, 0, $sx, $sy);

		// Add the border *after* we import the thumbnail, if enabled.
		if ((bool) $config['border']) {
			list ($r, $g, $b) = $config['border'];
			$border = imagecolorallocate($image, $r, $g, $b);
			imagerectangle($image, 0, 0, $width - 1, $height - 1, $border);
		}

		// Re-save our processed image.
		imagejpeg($image, $thumb_path);

		// Output the file?
		if ($output) {
			header('Content-Type: image/jpeg');
			$file = fopen($thumb_path, 'r');
			fpassthru($file);
		}
	}

	/**
	 * Cached getter for the config.
	 *
	 * @return array
	 */
	protected function _get_config() {
		if ($this->_config === NULL) {
			$this->_config = Kohana::$config->load('imagejet.' . $this->_group);

			if (! $this->_config) {
				throw new Exception('No such group configured: ' . $this->_group);
			}
		}

		return $this->_config;
	}

	/**
	 * Cached getter for the image root.
	 *
	 * @return string
	 */
	protected function _get_image_root() {
		if ($this->_image_root === NULL) {
			$this->_image_root = Kohana::$config->load('imagejet.image-root');

			if (! $this->_image_root) {
				throw new Exception('Invalid image root configuration.');
			}

			// Make sure we have a slash on the end, but not two.
			$this->_image_root = rtrim($this->_image_root, '\/') . DIRECTORY_SEPARATOR;
		}

		return $this->_image_root;
	}
}
