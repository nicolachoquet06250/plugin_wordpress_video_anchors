<?php
/**
 * @package Video_anchors
 * @version 0.1.0
 */
/*
Plugin Name: Video Anchors
Description: Ajout d'encres sur les videos
Author: Nicolas Choquet
Version: 0.1.0
*/

require_once __DIR__.'/classes/Menu.php';
require_once __DIR__.'/classes/Installation.php';
require_once __DIR__ . '/classes/mvc/views/Templates.php';
require_once __DIR__.'/classes/mvc/model/Requests.php';
require_once __DIR__.'/classes/mvc/controller/Controller.php';
require_once __DIR__.'/classes/mvc/controller/VideoController.php';
require_once __DIR__.'/classes/mvc/controller/AnchorController.php';


// Runs when plugin is activated
register_activation_hook(__FILE__,VideoAnchors\Installation::class.'::db_install');

// Runs when plugin is desactivated
register_deactivation_hook(__FILE__, VideoAnchors\Installation::class.'::db_delete');

add_action('admin_menu', 'add_menu_plugin');

function add_menu_plugin() {
	$slug_menu = VideoAnchors\Menu::addBackOfficeTab('Ajouter une vidéo', 'Ajouter une vidéo', VideoAnchors\Templates::class.'::Admin_RegisterVideos');
	VideoAnchors\Menu::addBackOfficeSubTab($slug_menu, 'Ajouter une vidéo', 'Ajouter une vidéo', VideoAnchors\Templates::class.'::Admin_RegisterVideos');
	VideoAnchors\Menu::addBackOfficeSubTab($slug_menu, 'Ajouter une ancre dans une vidéo', 'Ajouter une ancre dans une vidéo', VideoAnchors\Templates::class.'::Admin_RegisterTimeAnchors');
}