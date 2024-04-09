<?php

namespace App\Http\Controllers\Shopify;

use App\Http\Controllers\Controller;
use App\Mail\GdrpRequest;
use App\Models\Shopify\Shopify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GdprController extends Controller
{
    //

    public function customerDataRequest(Request $request)
    {
        $shopifyModel = new Shopify();
        $fromShopify = $shopifyModel->validateRequestor($request);
        if (!($fromShopify)) {
            return response()->json([
                'status' => 'error',
                'messages' => 'unauthorized'
            ], 401);
        }

        Mail::send(new GdrpRequest($request->getContent()));

        return response()->json([
            'status' => 'success',
            'messages' => 'accepted'
        ]);
    }

//    public function customerRedact(Request $request)
//    {
//
//    }
//
//    public function shopRedact(Request $request)
//    {
//
//    }
}
