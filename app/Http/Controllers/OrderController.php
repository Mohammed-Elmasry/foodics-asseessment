<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreationRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;

class OrderController extends Controller
{

    public function store(OrderCreationRequest $request)
    {
        return response()->json(["message" => "Order Created"], Response::HTTP_CREATED);
    }
}
