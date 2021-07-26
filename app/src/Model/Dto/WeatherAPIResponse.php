<?php

namespace App\Model\Dto;

class WeatherAPIResponse 
{
    private string $name;
    private int $dt;
    private array $main;
    private array $sys;

    public function __construct( string $name, int $dt, array $main, array $sys)
    {
        $this->name = $name;
        $this->dt = $dt;
        $this->main = $main;
        $this->sys = $sys;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getDt(): int
    {
        return $this->dt;
    }


    public function getMain(): array
    {
        return $this->main;
    }

    public function getSys(): array
    {
        return $this->sys;
    }
}