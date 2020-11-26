<?php

namespace App\Http\Controllers;


class ExceptionController extends Controller
{
    public function index()
    {
        // something went wrong and you want to throw CustomException
        throw new \App\Exceptions\CustomException();
    }
}
