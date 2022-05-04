<?php
namespace Ninja;

class EntryPoint {
	private $route;
	private $method;
	private $routes;

	public function __construct(string $route, string $method, \Ninja\Routes $routes) {
		$this->route = $route;
		$this->routes = $routes;
		$this->method = $method;
		$this->checkUrl();
	}

	private function checkUrl() {
		if ($this->route !== strtolower($this->route)) {
			http_response_code(301);
			header('location: ' . strtolower($this->route));
		}
	}

	private function loadTemplate($templateFileName, $variables = []) {
		extract($variables);

		ob_start();
		include  __DIR__ . '/../../templates/' . $templateFileName;

		return ob_get_clean();
	}

	public function run() {

		  $routes = $this->routes->getRoutes();	
	      $authentication = $this->routes->getAuthentication();

		if (isset($routes[$this->route]['login']) && !$authentication->isLoggedIn()) {
		  //header('location: /login/error');  // 5/25/18 JG DEL1L  org
	      header('location: index.php?login/error'); // 5/25/18 JG NEW1L  

		}
		else if (isset($routes[$this->route]['permissions']) && !$this->routes->checkPermission($routes[$this->route]['permissions'])) {
			//header('location: /login/permissionserror'); 6/3/18 JG DEL1L 
			header('location: index.php?login/permissionserror'); // 6/3/18 JG NEW1L 
		}
		else {   // 6/7/18 JG 3 next key statements
			$controller = $routes[$this->route][$this->method]['controller'];
			$action = $routes[$this->route][$this->method]['action'];
			$page = $controller->$action();

			$title = $page['title'];

			if (isset($page['variables'])) {
				$output = $this->loadTemplate($page['template'], $page['variables']);
			}
			else {
				$output = $this->loadTemplate($page['template']);
			}
			$user = $authentication->getUser();
			echo $this->loadTemplate('layout.html.php', ['loggedIn' => $authentication->isLoggedIn(),
			                                             'output' => $output,
			                                             'title' => $title,
														'user' => $user
			                                            ]);

		}

	}
}