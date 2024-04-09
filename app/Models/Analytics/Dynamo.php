<?php

namespace App\Models\Analytics;

use App\Models\User\User;
use Aws\DynamoDb\Marshaler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;


class Dynamo extends Model
{
    use HasFactory;

    public function logVisit($data)
    {

        $date = \DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $data['timestamp']);
        $epochTime = $date->format('U');
        $ttlValue = Config::get('aws.dynamo.visits.ttl');
        $expiresAt = $date->add(new \DateInterval('PT'.$ttlValue.'S'));
        $expiresAtEpoch = $expiresAt->format('U');

        $client = \AWS::createClient('DynamoDb');


        $result = $client->putItem([
            'TableName' => Config::get('aws.dynamo.visits.name'),
            'Item' => [
                'shop_id' => ['N' => (string) $data['shop_id']],
                'created_at' => ['N' => (string) $epochTime],
                'expires_at' => ['N' => (string) $expiresAtEpoch],
                'audience_size_id' => ['N' => strval(isset($data['audience_size']) ? $data['audience_size'] : '10')],
                'path' => ['S' => (string) $data['path']],
                'variant_id' => ['S' => (empty($data['variantId']) ? '' : (string) $data['variantId'])],
                'session_id' => ['S' => (string) $data['sessionId']],
                'browser_ip' => ['S' => (string) $data['ip']],
                'type' => ['S' => (string) $data['type']]
            ]
        ]);
        
        return $result;

    }

    public function getVisitByShop(int $shopId, int $createdAt)
    {
        $client = \AWS::createClient('DynamoDb');
        $marshaler = new Marshaler();
        $visitor = [];

        $eav = $marshaler->marshalJson('
        {
            ":shopId":'.$shopId.',
            ":createdAt":'.$createdAt.'
        }');

        $tableName = Config::get('aws.dynamo.visits.name');

        $params = [
            'TableName' => $tableName,
            'KeyConditionExpression' => 'shop_id = :shopId and created_at >= :createdAt',
            'ExpressionAttributeValues'=> $eav
        ];

        $response = $client->query($params);

        foreach ($response['Items'] as $v) {
            $visitor[] = [
                'ip' => !empty($v['browser_ip']) ? $marshaler->unmarshalValue($v['browser_ip']) : null,
                'audience_id' => !empty($v['audience_size_id']) ? $marshaler->unmarshalValue($v['audience_size_id']) : null
            ];
        }

        return $visitor;

    }

    public function getVisitors(int $shopId, int $startDate, int $endDate,string $type, array $browserIps)
    {
        $client = \AWS::createClient('DynamoDb');
        $marshaler = new Marshaler();
        $visitor = [];

        $eav = $marshaler->marshalJson('
        {
            ":shopId":'.$shopId.',
            ":startDate":'.$startDate.',
            ":endDate":'.$endDate.',
            ":vType":"'.$type.'"
        }');

        // dd($eav);

        $tableName = Config::get('aws.dynamo.visits.name');
        
        $params = [
            'TableName' => $tableName,
            'FilterExpression' => "#dynobase_type = :vType",
            'KeyConditionExpression' => 'shop_id = :shopId AND created_at BETWEEN :startDate AND :endDate',
            'ExpressionAttributeValues'=> $eav,
            "ExpressionAttributeNames"  => [
                "#dynobase_type"        => "type"
            ]
        ];

        $response = $client->query($params);
        $count = 0;
        foreach ($response['Items'] as $v) {
            $browser_ip = !empty($v['browser_ip']) ? $marshaler->unmarshalValue($v['browser_ip']) : null;
            if(in_array($browser_ip,$browserIps)){
                $created_at = !empty($v['created_at']) ? $marshaler->unmarshalValue($v['created_at']) : null;
                if($created_at){
                    $created_at = date('Y-m-d', $created_at);
                    
                    $visitor[$created_at] =  isset($visitor[$created_at]) ? $visitor[$created_at]+1 : 1;
                    $count+=1;
                }
            }
        }
        $visitor = array_values($visitor);

        return [
            'count'     =>  $count,
            'daywise'   =>  $visitor
        ];

    }
}
