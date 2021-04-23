<?php


namespace App\Traits;


trait InfoTrait
{

    public function getUser()
    {
        return auth('sanctum')->user();
    }



}
