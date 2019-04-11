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
		if(isset($_POST['anchor_time']) && isset($_POST['anchor_title']) && isset($_POST['video_id'])) {
			$this->model->add_time_for_video( $_POST['anchor_time'], $_POST['anchor_title'], $_POST['video_id'] );
			return true;
		}
		return false;
	}

	public function delete(): bool {
		if(isset($_POST['video_id']) && isset($_POST['anchor_id'])) {
			$this->model->delete_time_for_video($_POST['video_id'], $_POST['anchor_id']);
			return true;
		}
		return false;
	}

	public function update(): bool {
		if(isset($_POST['anchor_id']) && isset($_POST['anchor_time']) && isset($_POST['anchor_title']) && isset($_POST['video_id'])) {
			$this->model->update_time_for_video($_POST['anchor_time'], $_POST['anchor_title'], $_POST['anchor_id']);
			return true;
		}
		return false;
	}
}