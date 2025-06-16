<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Penting untuk $this->authorize()
use Illuminate\Foundation\Validation\ValidatesRequests;  // Penting untuk $this->validate()
use Illuminate\Routing\Controller as BaseController;     // Ini adalah base controller Laravel

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}