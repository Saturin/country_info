<?php


namespace App;
use Controller\Country;

Class App
{
    const Controller = "Country";
    public static function render():void
    {
        if(!isset($_GET['url'])) {
            $_GET['url'] = "index";
        }

        if(strpos($_GET['url'], "/") !== false) {
            $tmp =  explode("/",$_GET['url']);
            $method = $tmp[0];
            $params = $tmp[1];
            unset($tmp);
        } else {
            $method = $_GET['url'] ?? null;
            $params = null;
        }

        $template = MAINPATH . "App/View/".self::Controller."/".$method.".twig";



        if(!file_exists(MAINPATH . "App/Controller/" . self::Controller . ".php")) {
            die("404 3");
        }

        $page = new Country();

        if(!method_exists($page, $method)) {
            die($method);
            die("404 4");
        }

        echo self::loadView($method, $page->$method($params));
    }

    public static function loadView(string $template, array $data) : string
    {
        $loader = new \Twig\Loader\FilesystemLoader(MAINPATH ."App".DIRECTORY_SEPARATOR."View".DIRECTORY_SEPARATOR);
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);
        $twig->load(self::Controller.DIRECTORY_SEPARATOR.$template.".twig");

        return  $twig->render(self::Controller.DIRECTORY_SEPARATOR.$template.".twig", $data);
    }

}