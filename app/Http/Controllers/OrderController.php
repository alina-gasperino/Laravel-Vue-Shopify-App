<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Campaign\Campaigns;
use App\Models\User\SocialProviders;
use Illuminate\Support\Facades\Auth;
use App\Models\Shopify\Shopify;
use App\Models\Shop;

class OrderController extends Controller
{
    

    /**
     * Show the all orders.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $shops = Shop::all();
        return view('orders.index', compact('shops'));
    }

    public function data(Request $request,Shop $shop)
    {
        $limit = 200;
        $shopify = new Shopify();
        $shopifyUser = (new SocialProviders)->getShopifyById($shop->user_id);
        $list = $shopify->getOrdersFormBegin($shopifyUser, $request->token, $limit);
        $nextId = '';
        if(count($list)>0){
            $first = $list[0];
            $prevId = $first->id;
            if(count($list) == $limit){
                $last = $list[count($list)-1];
                $nextId = $last->id;    
            }
        }

        return view('orders.list', [
            'list'      => $list,
            'nextId'    => $nextId,
            'prevId'    => $request->page != 1 ? $request->last : '',
            'page'      =>  $request->page ? $request->page : 1,
        ]);
    }
}
