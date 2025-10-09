<?php

class Router{
    protected $routes = [];
    public function registeredRoutes ($method, $uri, $controller){
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
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
        $this->registeredRoutes('GET', $uri, $controller);
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
        $this->registeredRoutes('POST', $uri, $controller);
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
        $this->registeredRoutes('PUT', $uri, $controller);
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
        $this->registeredRoutes('DELETE', $uri, $controller);
    }

    /**
     * Routing the request
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
                
                require basePath($route['controller']);
                return;
            }
        }
    }
}