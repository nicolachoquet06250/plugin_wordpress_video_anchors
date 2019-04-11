<?php

namespace VideoAnchors;


abstract class Controller {
	protected $wpdb, $prefix, $model;
	public function __construct($wpdb) {
		$this->wpdb = $wpdb;
		$this->prefix = $wpdb->prefix;
		$this->model = new Requests($wpdb, $this->prefix);
	}

	public abstract function get(): array;

	public abstract function post(): bool;

	public abstract function delete(): bool;

	public abstract function update(): bool;
}