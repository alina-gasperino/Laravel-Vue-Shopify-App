<?php

namespace App\Services;
use App\Models\Campaign\Campaigns;

class HelperService {

    public static function getPageInfoToken($headers, $type = 'previous')
    {
        if (isset($headers['Link'])) {
            $str = $headers['Link'][0];

            $tmp = explode(', ', $str);

            foreach($tmp as $_tm) {
                $str = explode(';', $_tm);
                if (preg_match('/'.$type.'/', $str[1])) {
                    $str = $str[0];
                    $str = str_replace(['<', '>'], '', $str);
                    $queryStr = parse_url($str, PHP_URL_QUERY);
                    parse_str($queryStr, $queryStringArray);
                    return $queryStringArray['page_info'];
                }
            }
            return false;
        } else {
            return false;
        }
    }

    public static function postcardAudienceOptions(){
        return [ 
            Campaigns::Prior_Customers,
            Campaigns::Purchase_More_Then,
            Campaigns::purchase_More_Then_Times,
            Campaigns::Top_Customer_By_Spend,
        ];
    }

}