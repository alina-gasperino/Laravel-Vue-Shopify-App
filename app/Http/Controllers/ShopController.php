<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    protected $userId;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->userId = Auth::user()->id;
            return $next($request);
        });
    }

    public function redirectToPortal (Request $request) {

        $shop = Shop::where([
            'id' => $request->route('shop_id'),
            'user_id' => $this->userId
        ])->first();

        return $shop->redirectToBillingPortal();
    }

    public function startSubscription(Request $request)
    {
        $rules = [
            'fullname' => ['required', 'string'],
            'number' => 'required|int',
            'expiration' => ['required', 'regex:/^([0-9]{2})\s?\/\s?([0-9]{2})$/'],
            'cvv' => 'required|int',
            'shop_id' => 'required|int',
            'address1' => 'required|string',
            'address2' => 'string|nullable',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => ['required', 'alpha', 'min:2', 'max:2'],
            'zip' => ['required', 'string'],
            'offer_code' => 'string',
        ];

        $validator = Validator::make($request->all(), $rules, $messages = [
            'expiration.regex' => 'Expiration date must be 2 digit month and 2 digit year',
        ]);

        if ($validator->errors()->count() > 0) {
            return redirect()->route('subscriptionForm',
                ['shop_id' => $request->post('shop_id')])->withErrors($validator);
        }

        $data = $validator->validate();

        $shop = Shop::where([
            'id' => $data['shop_id'],
            'user_id' => $this->userId
        ])->first();

        $planId = config('cashier.subscription.plan');
        $pricingId = config('cashier.subscription.pricing');
        $subbed = $shop->subscribed($planId);

        if ($subbed) {
            return true;
        }

        try {
            $customer = $shop->createOrGetStripeCustomer([
                'name' => $data['fullname'],
                'email' => $request->user()->email,
                'address' => [
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'line1' => $data['address1'],
                    'line2' => $data['address2'],
                    'postal_code' => $data['zip'],
                    'state' => $data['state'],
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['customer' => $e->getMessage()], 400);
        }

        try {
            $expiration = explode('/', $data['expiration']);
            $dt = \DateTime::createFromFormat('y', $expiration[1]);
            $year = $dt->format('Y');

            // Add Payment Method
            $stripe = new \Stripe\StripeClient(
                config('cashier.secret')
            );
            $payment = $stripe->paymentMethods->create([
                'type' => 'card',
                'card' => [
                    'number' => $data['number'],
                    'exp_month' =>  $expiration[0],
                    'exp_year' =>  $year,
                    'cvc' => $data['cvv'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['payment' => $e->getMessage()], 400);
        }

        try {
            $shop->newSubscription($planId)
                ->meteredPrice($pricingId)
                ->create($payment->id);
        } catch (\Exception $e) {
            return response()->json(['subscription' => $e->getMessage()], 400);
        }

        return true;
    }

    public function viewSubscriptionForm(Request $request)
    {
        $shopId = $request->route('shop_id');
        $shop = Shop::where([
            'id' => $shopId,
            'user_id' => $this->userId
        ])->first();

        return view('form.payment', [
            'shop' => $shop,
            'intent' => $shop->createSetupIntent()
        ]);
    }
}
