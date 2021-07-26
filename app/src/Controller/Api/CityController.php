<?php

namespace App\Controller\Api;

use App\Service\WeatherService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class CityController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/check")
     * @Rest\View(serializerGroups={"weather"}, serializerEnableMaxDepthChecks=true)
     */
    public function index(WeatherService $weatherService, Request $request): View
    {
        $city = $request->get('city', null);
        $compare = $request->get('compare', null);
        $method = $request->getMethod();
        if($city === null || is_numeric($city) ){
            return View::create(json_decode('{"error":true}', true), Response::HTTP_BAD_REQUEST);
        }
        if($compare !== null){
            $json = ($weatherService)($city, $method, $compare);
        }else{
            $json = ($weatherService)($city, $method);
        }
        return View::create($json);
    }
}
