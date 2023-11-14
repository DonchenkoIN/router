<?php
namespace App\RMVC\Route;

class RouteConfiguration
{
    public string $route;
    public string $controller;
    public string $action;
    private string $name;
    private string $middleware;

    /**
     * @param string $name
     */
    public function name(string $name): RouteConfiguration
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $middleware
     */
    public function middleware(string $middleware): RouteConfiguration
    {
        $this->middleware = $middleware;
        return $this;
    }


    /**
     * @param string $route
     * @param string $controller
     * @param string $action
     */
    public function __construct(string $route, string $controller, string $action)
    {
        $this->route = $route;
        $this->controller = $controller;
        $this->action = $action;
    }


}