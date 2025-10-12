<?php

namespace Framework;

// A Router class to handle HTTP requests and route them to the appropriate controller.
class Router{
    // Store all registered routes
    protected $routes = [];

    /**
     *  Register a new route
     * @param string $method
     * @param string $uri
     * @param string $action
     * @return void
     */
    public function registerRoute ($method, $uri, $action){
        list($controller, $controllerMethod) = explode('@', $action);
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }

    /**
     * Add a GET route
     * 
     * @param string $uri
     * @param string $controller 
     * @return void
     */
    public function get($uri, $controller){
        $this->registerRoute('GET', $uri, $controller);
    }

    /**
     * Add a POST route
     * 
     * @param string $uri
     * @param string $controller 
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->registerRoute('POST', $uri, $controller);
    }

    /**
     * Add a PUT route
     * 
     * @param string $uri
     * @param string $controller 
     * @return void
     */
    public function put($uri, $controller)
    {
        $this->registerRoute('PUT', $uri, $controller);
    }

    /**
     * Add a DELETE route
     * 
     * @param string $uri
     * @param string $controller 
     * @return void
     */
    public function delete($uri, $controller)
    {
        $this->registerRoute('DELETE', $uri, $controller);
    }

    /**
     * Load the error page
     * 
     * @param int $httpCode
     * @retun void
     */
    public function error($httpCode = 404){
        http_response_code($httpCode);
        loadView("error/{$httpCode}");
        exit;
    }


    /**
     * Route the request
     * 
     * @param string $uri
     * @param string $method
     * @return void
     */
    public function route($uri, $method){
        // loops through all registered routes.
        foreach($this->routes as $route){

            // checks if the route matches the request URI and method.
            if($route['uri'] === $uri && $route['method'] === $method){    

                // Extract controller and method
                $controller = 'App\\Controllers\\' . $route['controller'];
                $controllerMethod = $route['controllerMethod'];
                
                // Instantiate the controller and call the method
                $controllerInstance = new $controller();
                $controllerInstance->$controllerMethod();

                // Stop further execution
                return;
            }
        }

        // If no route matches, send a error response.
        $this->error();
    }
}