<?php

namespace App\Models\Mongo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\Campaign\CampaignTargetHistory;

class OrdersDiscountCodes extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'shop_order_history_discount_codes';

    // protected $table = 'shop_order_history_discount_codes';

    protected $guarded = ['id'];

    public function orders()
    {
        return $this->belongsTo(Orders::class);
    }
    
}
