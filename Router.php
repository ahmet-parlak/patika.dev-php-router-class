<?php
class Router
{
    private $routeHandled = false;

    public function parse_url()
    {
        $dirname = dirname($_SERVER['SCRIPT_NAME']);
        $dirname = $dirname != '/' ? $dirname : null;
        $basename = basename($_SERVER['SCRIPT_NAME']);
        $request_uri = str_replace([$dirname, $basename], null, $_SERVER['REQUEST_URI']);
        return $request_uri;
    }

    public function run($url, $callback, $method = 'GET')
    {
        $method = explode('|', strtoupper($method));

        if (in_array($_SERVER['REQUEST_METHOD'], $method)) {
            $patterns = [
                '{url}' => '([0-9a-z-A-Z]+)',
                '{id}' => '([0-9]+)',
            ];

            $url = str_replace(array_keys($patterns), array_values($patterns), $url);

            $request_uri = $this->parse_url();
            if (preg_match('@^' . $url . '$@', $request_uri, $parameters)) {

                unset($parameters[0]);

                if (is_callable($callback)) {
                    call_user_func_array($callback, $parameters);
                    $this->routeHandled = true;

                } else {
                    $controller = explode('@', $callback);
                    $controllerFile = __DIR__ . '\controllers\\' . strtolower($controller[0]) . '.php';
                    if (file_exists($controllerFile)) {
                        require $controllerFile;
                        call_user_func_array([new $controller[0], $controller[1]], $parameters);

                        $this->routeHandled = true;
                    }
                }
            }
        }

    }

    public function get($url, $callback)
    {
        $this->run($url, $callback, 'GET');
    }

    public function post($url, $callback)
    {
        $this->run($url, $callback, 'POST');
    }

    private function notFound()
    {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    public function endRouter()
    {
        if (!$this->routeHandled) {
            $this->notFound();
        }
    }

}

?>