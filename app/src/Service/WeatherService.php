<?php

namespace App\Service;

use App\Model\Dto\WeatherAPIResponse;
use App\Service\Utils\HttpClientInterface;
use Exception;

class WeatherService
{

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function __invoke(string $city, string $method, string $compare = 'Köln'): array
    {
        $city = $this->makeRequest($city, $method);
        $compare = $this->makeRequest($compare, $method);

        $response = $this->generateResponse($city, $compare);

        return json_decode($response, true);
    }

    private function makeRequest(string $city, string $method): WeatherAPIResponse
    {
        $key = $_ENV["APP_WEATHER"];
        $response = $this->httpClient->request(
        $method, 
        "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$key",
        []
        );
        $status = $response->getStatusCode(); 
        
        if ($status !== 200){
            throw new Exception("Error Processing Request", 1);
        }

        $content = $response->getContent();
        $json = json_decode($content, true);
        return new WeatherAPIResponse($json['name'], $json['dt'], $json['main'], $json['sys']);
    }

    private function generateResponse(WeatherAPIResponse $city, WeatherAPIResponse $compare): string 
    {
        $response = array();

        $city_main = $city->getMain();
        $compare_main = $compare->getMain();
        $compare_sys = $compare->getSys();

        $response['criteria']['naming'] = $this->namingOddValidation($city->getName());
        $response['criteria']['daytemp'] = $this->dayTemperatureValidation($city_main['temp'], $city->getDt(), $compare_sys['sunrise'], $compare_sys['sunset']);
        $response['criteria']['rival'] = $this->rivalTemperatureCompare($city_main['temp'], $compare_main['temp']);

        if($response['criteria']['naming'] && $response['criteria']['daytemp'] && $response['criteria']['rival']){
            $response['check'] = true;
        }else{
            $response['check'] = false;
        }

        return json_encode($response, true);
    }

    private function kelvinToCelsius(float $temp): float
    {
        return $temp - 273.15;
    }

    private function namingOddValidation(string $city): bool
    {
        return mb_strlen($city)%2 !== 0 ? true : false;
    }

    private function rivalTemperatureCompare(int $city_temp, int $compare_temp): bool
    {
        return $this->kelvinToCelsius($city_temp) > $this->kelvinToCelsius($compare_temp) ? true : false;
    }

    private function dayTemperatureValidation(int $city_temp, int $city_dt, int $sunrise, int $sunset): bool
    {
        $celsius_temp = $this->kelvinToCelsius($city_temp);
        if( $city_dt >= $sunset && $city_dt <= $sunrise){
            if($celsius_temp >= 10 && $celsius_temp <=15){
                return true;
            }
        }
        if( $city_dt >= $sunrise && $city_dt <= $sunset){
            if($celsius_temp >= 17 && $celsius_temp <=25){
                return true;
            }
        }

        return false;
    }

}