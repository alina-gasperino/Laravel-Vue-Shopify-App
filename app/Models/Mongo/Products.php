<?php

namespace App\Models\Mongo;

use App\Models\Campaign\CampaignHistory;
use App\Models\Campaign\Campaigns;
use App\Models\Campaign\CampaignTargetHistory;
use App\Models\Shop;
use App\Models\User\SocialProviders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use App\Models\Shopify\Shopify;

class Products extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'shop_products';

    protected $primaryKey = 'id';
    
    protected $guarded = [];

    
}
