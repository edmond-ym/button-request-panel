<?php
namespace App\Library\Interface;

interface APIQueryServiceInterface{
    public function toDBUpdateArray($queryParamValuePairs);
}