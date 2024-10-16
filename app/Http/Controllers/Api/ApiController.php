<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Helpers\Email;
use App\Contracts\Receipt;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendEmailRequest;
use App\Services\EmailService;
use App\Services\LogService;

class ApiController extends Controller
{
    public function notfound()
    {
        return response(null, 404)->header('Content-Type', 'application/json');
    }

    public function include(string $relationship) : bool {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }
 
}
