<?php

namespace VideoAnchors;

class Requests {
	/** @var \wpdb $wpdb */
	private $wpdb;
	private $prefix;
	public function __construct($wpdb, $prefix) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$this->prefix = $prefix;
		$this->wpdb = $wpdb;
	}

	public function get_videos() {
		$this->wpdb->query( "SELECT * FROM " . $this->prefix . "video" );
		return $this->wpdb->last_result;
	}

	public function update_video($id, $video) {
		\dbDelta( "UPDATE " . $this->prefix . "video 
							SET id_product=" . $video['associated_course'] . ", 
								video=\"" . $video['video_url'] . "\" 
							WHERE id=" . $id . ";" );
	}

	public function delete_video($id) {
		\dbDelta( "DELETE FROM " . $this->prefix . "video 
					  		WHERE id=" . $id . ";" );
	}

	public function add_video($video) {
		\dbDelta( "INSERT INTO " . $this->prefix . "video 
					  		SET id_product=" . $video['associated_course'] . ", 
				  				video=\"" . $video['video_url'] . "\";" );
	}

	public function get_times_for_video(int $video_id) {
		$this->wpdb->query("SELECT * from ". $this->prefix . "video_anchor WHERE id_video = ". $video_id .";");
		return $this->wpdb->last_result;
	}

	public function add_time_for_video(string $time, string $title, int $video_id) {
		$this->wpdb->query( "INSERT INTO " . $this->prefix . "video_anchor 
					  		SET id_video=" . $video_id . ", 
				  				title=\"" . $title . "\",
				  				time_h=\"". explode(':', $time)[0] ."\",
				  				time_i=\"". explode(':', $time)[1] ."\",
				  				time_s=\"". explode(':', $time)[2] ."\";" );
	}

	public function update_time_for_video(string $time, string $title, int $anchor_id) {
		$this->wpdb->query( "UPDATE " . $this->prefix . "video_anchor 
							SET title=\"" . $title . "\",
				  				time_h=\"". explode(':', $time)[0] ."\",
				  				time_i=\"". explode(':', $time)[1] ."\",
				  				time_s=\"". explode(':', $time)[2] ."\"
							WHERE id=".$anchor_id );
	}

	public function delete_time_for_video(int $video_id, int $time_id) {
		$this->wpdb->query("DELETE FROM " . $this->prefix . "video_anchor WHERE id_video=" . $video_id . " AND id=". $time_id);
	}

	public function get_first_video_id() {
		$videos = $this->get_videos();
		return (int)$videos[0]->id;
	}

	public function video_is_accessible(int $id_video) {
		dbDelta("SELECT * FROM ".$this->prefix."video WHERE id=".$id_video.";");
		$result = $this->wpdb->last_result;
		if($result) $result = $result[0];
		return ($result->status_required && is_user_logged_in()) || (!$result->status_required && !is_user_logged_in());
	}

	public function get_courses() {
		return get_posts();
	}
}