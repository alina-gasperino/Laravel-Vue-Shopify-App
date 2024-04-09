<?php

namespace App\Http\Controllers\Analytics;

use Illuminate\Routing\Controller;
// use App\Http\Controllers\Controller;
use App\Models\Analytics\Dynamo;
use App\Models\Shop;
use App\Models\Visitor;
use App\Models\VisitorIp;
use App\Models\User\SocialProviders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Frontend\LogVisitRequest;

class IndexController extends Controller
{
    public function logVisit(LogVisitRequest $request) {

        $rules = [
            'shopName' => 'required',
            'path' => 'required',
            'variantId' => '',
            'sessionId' => 'required',
            'timestamp' => 'required|date',
            'ip' => 'required|ip',
            'audience_size_id' => 'int',
            'type' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages=$validator->messages();
            $errors=$messages->all();
            return response()->json(['Status' => 'Error', 'Message' => $errors], 400);
        }

        $validated = $validator->validated();
        $shop = Shop::where(['shop_name' => $validated['shopName']])->first();
        $validated['shop_id'] = $shop->id;

        $logVisit = (new Dynamo())->logVisit($validated);

        return response()->json([
            'Status' => 'SUCCESS'
        ]);

    }
}
