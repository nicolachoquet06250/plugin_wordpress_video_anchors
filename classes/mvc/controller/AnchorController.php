<?php

namespace VideoAnchors;


class AnchorController extends Controller {

	public function get(): array {
		if(!isset($_GET['video_id'])) {
			$_GET['video_id'] = $this->model->get_first_video_id();
		}
		return $this->model->get_times_for_video($_GET['video_id']);
	}

	public function post(): bool {
		// TODO: Implement post() method.
		return false;
	}

	public function delete(): bool {
		return false;
		// TODO: Implement delete() method.
	}

	public function update(): bool {
		return false;
		// TODO: Implement update() method.
	}
}