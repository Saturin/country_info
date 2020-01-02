<?php


namespace Controller;


class Country
{
    const WSDL = "http://webservices.oorsprong.org/websamples.countryinfo/CountryInfoService.wso?WSDL";

    public function index()
    {

        return [

        ];
    }

    public function fullList()
    {
        header('Content-Type: application/json');
        $con = file_get_contents(MAINPATH . "/App/Cache/countrylistfull.json");
        $this->checkCache();

        if(!isset($_REQUEST)) {

        }

        echo $con;
        die();
    }


    public function country( $params = null ) {

        $con = file_get_contents(MAINPATH . "/App/Cache/countrylist.json");
        $this->checkCache();
        return [
            "selected" => $_COOKIE['selected'] ?? "Poland"
        ];
    }

    public function loadCache($full = false)
    {
        header('Content-Type: application/json');
        if($full) {
            echo file_get_contents(MAINPATH . "/App/Cache/countrylistfull.json");
        } else {
            echo file_get_contents(MAINPATH . "/App/Cache/countrylist.json");
        }

        die();
    }

    public function findCountry()
    {
        $resp = [];
        if(isset($_REQUEST['type']) && $_REQUEST['text']) {
            $this->checkCache();
            if($_REQUEST['type'] !='lang') {
                $con = json_decode(file_get_contents(MAINPATH . "/App/Cache/countrylist.json"),true);
            } else {
                $con = json_decode(file_get_contents(MAINPATH . "/App/Cache/countrylistfull.json"),true);
            }

            foreach ($con as &$item) {
                 if ($_REQUEST['type'] == "name") {
                     if (strpos($item['sName'], $_REQUEST['text']) !== false) {
                         $resp[] = $item;
                     }
                 }elseif ($_REQUEST['type'] == "code") {
                     if(strpos($item['sISOCode'], strtoupper($_GET['text'])) !==false) {
                         $resp[] = $item;
                     }
                 } elseif ($_REQUEST['type']=='lang') {

                     foreach ($item["Languages"] as $lang ) {
                        if(isset($lang[0])) {
                            foreach ($lang as $l ) {
                                if(strpos($l['sName'], $_REQUEST['text'])!==false) {
                                    $resp[] = $item;
                                }
                            }
                        } else {
                            if(strpos($lang['sName'], $_REQUEST['text'])!==false) {
                                $resp[] = $item;
                            }
                        }
                     }

                 }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($resp);
        die();
    }

    public function getCountry()
    {
        header('Content-Type: application/json');
        $resp = [];
        if(isset($_REQUEST['code'])) {
            $resp = $this->callSoap("FullCountryInfo",["FullCountryInfo"=> ["sCountryISOCode" => $_REQUEST['code']]]);
        }
        echo json_encode($resp);
        die();
    }

    private function callSoap(string $func, array $params = [])
    {
        $data = null;
        try {
            $soap = new \SoapClient(self::WSDL, array('trace' => true, 'exception' => true));
            $data = $soap->__soapCall($func, $params);

        } catch (SoapFault $e) {
            print_r($e);
        }
        return $data;
    }

    private function checkCache()
    {
        if(!file_exists(MAINPATH . "/App/Cache/countrylist.json") || empty(file_get_contents(MAINPATH . "/App/Cache/countrylist.json"))) {
            $con = $this->callSoap("ListOfCountryNamesByName");
            $con = json_encode($con->ListOfCountryNamesByNameResult->tCountryCodeAndName);
            file_put_contents(MAINPATH . "/App/Cache/countrylist.json", $con);
        }
        if(!file_exists(MAINPATH . "/App/Cache/countrylistfull.json") || empty(file_get_contents(MAINPATH . "/App/Cache/countrylistfull.json"))) {
            $con = $this->callSoap("FullCountryInfoAllCountries")->FullCountryInfoAllCountriesResult->tCountryInfo;
            $con = json_encode($con);
            file_put_contents(MAINPATH . "/App/Cache/countrylistfull.json", $con);
        }
    }
}