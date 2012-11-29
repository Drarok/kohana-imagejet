<?php

class Controller_ImageJet extends Controller {
	public function action_thumbnail() {
		// Grab the parameters.
		$group = $this->request->param('group');
		$image_path = $this->request->param('image_path');

		// Let's rock this!
		$image = new ImageJet($group);
		$image->thumbnail($image_path, true);
	}
}