<?php

namespace VideoAnchors;


class Installation {
	public static function db_install() {
		global $wpdb;
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		dbDelta(["CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."video_anchor (
	id int(11) NOT NULL AUTO_INCREMENT, 
  	id_video int(11) NOT NULL,
	time_h int(11) NOT NULL,
	time_i int(11) NOT NULL,
	time_s int(11) NOT NULL,
	title varchar(255),
	PRIMARY KEY (`id`)
);", "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."video (
	id int(11) NOT NULL AUTO_INCREMENT,
	id_product int(11) NOT NULL,
	video varchar(255),
	PRIMARY KEY (`id`)
);"]);
	}

	public static function db_delete() {
		global $wpdb;
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		dbDelta([
			"DROP TABLE IF EXISTS ".$wpdb->prefix."video_anchor;",
			"DROP TABLE IF EXISTS ".$wpdb->prefix."video"
		]);
	}
}