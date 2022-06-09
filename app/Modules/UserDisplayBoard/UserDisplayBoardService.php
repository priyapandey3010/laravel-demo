<?php declare (strict_types = 1);

namespace App\Modules\UserDisplayBoard;

use App\Core\BaseService;
use App\Modules\Cases\Cases as Model;
use App\Modules\Court\Court;
use App\Modules\Cases\CaseResource as Resource;
use App\Modules\Cases\ViewDetailResource;
use Carbon\Carbon;

class UserDisplayBoardService extends BaseService 
{
    public function getCases()
    {
        $benchId = session('bench_id');

        $query = Model::query();

        if ($benchId) 
            $query->benchId($benchId);

        $model = $query
            ->statusIdIn([status_id('In Session'), status_id('Not In Session')])
            ->oldestSortOrder()
            ->get();

        return Resource::collection($model)->resolve();
    }

    public function getCourtCases($courtId)
    { 
        $model = Model::createdDate(Carbon::today())
            ->courtId($courtId)
            ->oldestSortOrder()
            ->get();
        return ViewDetailResource::collection($model)->resolve();
    }

    public function getBenchName($id)
    {
        $court = Court::findOrFail($id);
        return $court->bench->bench_name;
    }


    public function getCasesList()
    {
      $url = 'http://164.100.59.182/nclat/restapi/services/case_details.php';
        $ch = curl_init($url); 
        $payload =  [
             'search_type' => 'initial',
             'page' => 'case_status'
          ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['token:RJGk1ZXc6nDkrQxn0klRXWNTCSqXcjk3']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch); 
        $data=json_decode($result);
        return $data;
        /*echo "<pre>";
        print_r($data); die();*/
    }

    
}