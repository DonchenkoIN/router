<?php
namespace App\RMVC\Route;

class RouteDispatcher
{
    private RouteConfiguration $routeConfiguration;
    private string $requestUri = '/';
    private array $paramMap = [];


    /**
     * @param RouteConfiguration $routeConfiguration
     */
    public function __construct(RouteConfiguration $routeConfiguration)
    {
        $this->routeConfiguration = $routeConfiguration;
    }

    private function saveRequestUri()
    {
        if($_SERVER['REQUEST_URI'] !== '/') {
            $this->requestUri = $this->clean($_SERVER['REQUEST_URI']);
            $this->routeConfiguration->route = $this->clean($this->routeConfiguration->route);
        }


    }

    private function clean($str): string
    {
        return preg_replace('/(^\/)|(\/$)/', '', $str);
    }

    private function serParamMap()
    {
        $routeArray = explode('/', $this->routeConfiguration->route);

        foreach ($routeArray as $paramKey => $param) {
            if (preg_match('/{.*}/', $param)) {
                $this->paramMap[$paramKey] = preg_replace('/(^{)|(}$)/', '', $param);
            }
        }
    }

    private function makeRegexRequest()
    {
        $requestUriArray = explode('/', $this->requestUri);

        foreach ($this->paramMap as $paramKey => $param) {
            if (!isset($requestUriArray[$paramKey])) {
                return;
            }

            $requestUriArray[$paramKey] = '{.*}';
        }

        $this->requestUri = implode('/', $requestUriArray);

        $this->prepareRegex();

    }

    private function prepareRegex()
    {
        $this->requestUri = str_replace('/', '\/', $this->requestUri);
    }

    private function render()
    {

        $ClassName = $this->routeConfiguration->controller;
        $action = $this->routeConfiguration->action;
        print((new $ClassName)->$action());
        die();
    }

    public function process()
    {
//        echo '<pre>';
//        var_dump($_SERVER['REQUEST_URI']);
//        echo '</pre>';

        $this->saveRequestUri();
        $this->serParamMap();
        $this->makeRegexRequest();
        $this->run();
    }

    public function run()
    {
        if (preg_match("/$this->requestUri/", $this->routeConfiguration->route)) {

            $this->render();
        }
    }
}