<?php

class Router
{
    private $routes;
    
    public function __construct()
    {
        $routesPath=ROOT.'/config/routes.php';
        $this->routes = include($routesPath);
    }
    
    /**
     * Return request string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        // Получить строку запроса
        $uri = $this->getURI();
        
        // Проверить наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path) {
            
            // Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {
                
                // Получаем внутрений путь из внешнего согласно правилу
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
               
                // Определить какой контроллер, action, параметры
                
                $segments = explode('/', $path);
                
                $controllerName = array_shift($segments);
                $controllerName = array_shift($segments).'Controller';
                $controllerName = ucfirst($controllerName);
                
                $actionName = 'action'.ucfirst(array_shift($segments));
                                
                $parameters = $segments;
                
                // Подключить файл класса-контроллера
                $controllerFile = ROOT . '/controller/' .
                $controllerName . '.php';
                
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                
                // Создать объект, вызвать метод (т.е. action)
                $controllerObject = new $controllerName;
                
                // Ф-ия вызывает метод $actionName у объекта $controllerObject
                // и передает ему массив с параметрами
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                if ($result != null) {
                    break;
                }
            }
        }
        
        // Если есть совпадение, определить какой контроллер
        // и action обрабатывают запрос
        
        // Подключить файл класса-контроллера
        
        // Создать объект, вызвать метод (т.е. action_
    }
}