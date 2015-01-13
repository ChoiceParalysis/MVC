<?php

class App {

	protected $controller;
	protected $method;
	protected $parameters;

	public function __construct() {
	  	$this->controller = 'home';
		$this->method = 'index';
		$this->parameters = [];

		$url = $this->parseUrl();

		// Checks if the controller exists in the app/controllers directory
		if (file_exists('../app/controllers/' . $url[0] .'.php')) {
			$this->controller = array_shift($url);
		}

		require '../app/controllers/' . $this->controller . '.php';
		$this->controller = new $this->controller;

		// If at least one argument is still present in the array, this has to be
		// the method (and parameters).
		if (count($url)) {
			if (method_exists($this->controller, $url[0])) {
				$this->method = array_shift($url);
			}
		}

		$this->parameters = $url ? : [];
		call_user_func_array([$this->controller, $this->method], $this->parameters);
	}


	public function parseUrl() {
	  	if (isset($_GET['url'])) {
			return explode('/', filter_var(rtrim($_GET['url'], '/')), FILTER_SANITIZE_URL);
		}
		return null;
	}
}
