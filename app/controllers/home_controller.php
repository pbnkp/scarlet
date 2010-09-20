<?php
namespace App\Controllers;
class HomeController extends ApplicationController
{
    
    public function index()
    {
        $this->data['name'] = 'World';
    }
    
}
