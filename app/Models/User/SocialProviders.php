<?php

namespace App\Models\User;

use App\Models\Campaign\DesignHuddle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;


class SocialProviders extends Model
{
    use HasFactory;

    protected $table = 'users_social_providers';

    protected $fillable = [
        'avatar', 'nickname', 'provider_id', 'provider_user_id', 'refresh_token', 'access_token', 'token_expiration',
        'user_id'
    ];


    // Support for Composite Key
    protected $primaryKey = ['provider_id', 'provider_user_id'];
    public $incrementing = false;

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param  mixed  $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    public function getShopifyById($userId)
    {
        return $this::where(['provider_id' => 1, 'user_id' => $userId])->first();
    }

    static public function getDesignHuddleAppToken()
    {
        $socialProvider = SocialProviders::where(['provider_user_id' => 0, 'provider_id' => 2])->get();
        if (!isset($socialProvider->token_expiration))
            $socialProvider = (new DesignHuddle())->refreshAppToken();
        $expireAt = new \DateTime($socialProvider->token_expiration);
        $now = new \DateTime();
        //Refresh that Token!
        if ($expireAt < $now) {
            $socialProvider = (new DesignHuddle())->refreshAppToken();
        }
        return $socialProvider;
    }


    public function getExpiredTokens()
    {

        $expirations = $this->whereRaw('token_expiration <=  NOW() + INTERVAL 1 HOUR')->get();
        $DesignHuddle = new DesignHuddle();
        foreach ($expirations as $expired) {
            if ($expired->provider_id == 2 && $expired->provider_user_id) {
                $DesignHuddle->refreshAppToken();
            } elseif ($expired->provider_id == 2) {
                $res = $DesignHuddle->getRefreshToken($expired->user_id);
                $DesignHuddle->updateUser($expired, $res);
            } else {
                Log::error('Unhandled Token Expiration');
            }
        }
        return $expirations;

    }
}
