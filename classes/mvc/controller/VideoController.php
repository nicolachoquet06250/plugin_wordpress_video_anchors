<?php

namespace VideoAnchors;


class VideoController extends Controller {
	public function get(): array {
		return $this->model->get_videos();
	}

	public function post(): bool {
		if ( isset( $_POST['video_url'] ) && isset( $_POST['associated_course'] ) ) {
			$this->model->add_video([
				'video_url' => $_POST['video_url'],
				'associated_course' => $_POST['associated_course'],
			]);
			return true;
		}
		return false;
	}

	public function delete(): bool {
		if ( isset( $_POST['video_id'] ) ) {
			$this->model->delete_video($_POST['video_id']);
			return true;
		}
		return false;
	}

	public function update(): bool {
		if ( isset( $_POST['video_id'] ) && isset( $_POST['video_url'] ) && isset( $_POST['associated_course'] ) ) {
			$this->model->update_video($_POST['video_id'], [
				'video_url' => $_POST['video_url'],
				'associated_course' => $_POST['associated_course'],
			]);
			return true;
		}
		return false;
	}
}