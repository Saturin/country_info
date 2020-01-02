<?php


namespace App;
use Controller\Country;

Class App
{
    const Controller = "Country";
    public static function render()
    {
        if(strpos($_GET['url'], "/") !== false) {
            $tmp =  explode("/",$_GET['url']);
            $method = $tmp[0];
            $params = $tmp[1];
            unset($tmp);
        } else {
            $method = $_GET['url'] ?? null;
            $params = null;
        }
        $template = "./View/".self::Controller."/".$method.".twig";
        if(!file_exists("./View/".self::Controller."/".$method.".twig")) {
            die("404");
        }

        if(!file_exists("./Controller" . self::Controller . ",php")) {
            die("404");
        }

        $page = new Country();

        if(!method_exists($page, $method)) {
            die("404");
        }

        self::loadView($template, $page->$method($params));
    }

    public static function loadView(string $template, array $data) : string
    {
        $loader = new \Twig\Loader\FilesystemLoader('/path/to/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);

        return  $twig->render($template, $data);
    }

}