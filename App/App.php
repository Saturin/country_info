<?php


namespace App;
use Controller\Country;

Class App
{
    const Controller = "Country";
    public static function render()
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


        if(!file_exists($template)) {
            die("404 2");
        }

        if(!file_exists(MAINPATH . "App/Controller/" . self::Controller . ".php")) {
            die("404 3");
        }

        $page = new Country();

        if(!method_exists($page, $method)) {
            die("404 4");
        }

        echo self::loadView($method, $page->$method($params));
    }

    public static function loadView(string $template, array $data) : string
    {
        $loader = new \Twig\Loader\FilesystemLoader(MAINPATH ."App".DIRECTORY_SEPARATOR."View".DIRECTORY_SEPARATOR.self::Controller.DIRECTORY_SEPARATOR);
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
        ]);
        var_dump(file_exists("F:/Projects/zadanie_rekrutacja/Zadanie_2/App/View/Country/index.twig"));
        $twig->load($template.".twig");
        return  $twig->render($template.".twig", $data);
    }

}