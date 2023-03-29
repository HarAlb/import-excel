<?php

namespace App\Http\Api\Base\Controller;

use App\Http\Api\Base\Traits\ResponseJsonTrait;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    use ResponseJsonTrait;
}
