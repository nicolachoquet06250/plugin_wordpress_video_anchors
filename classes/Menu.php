<?php

namespace VideoAnchors;


class Menu {
	const READ = 'read';
	const WRITE = 'write';

	public static function addBackOfficeTab($page_title, $menu_title, string $callback, $permission = self::READ) {
		$slug = str_replace(['::', '\\'], '_', $callback);
		add_menu_page($page_title, $menu_title, $permission, $slug, $callback);
		return $slug;
	}

	public static function addBackOfficeSubTab($parent_slug, $page_title, $menu_title, string $callback, $permission = self::READ) {
		add_submenu_page($parent_slug, $page_title, $menu_title, $permission, str_replace(['::', '\\'], '_', $callback), $callback);
		return $callback;
	}


}