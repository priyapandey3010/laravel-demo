<?php declare (strict_types = 1);

namespace App\Modules\CaseJudgeApis;

use App\Core\BaseService; 
use GuzzleHttp;
use Illuminate\Support\Facades\Http;

class CaseJudgeApisService extends BaseService 
{
    
    public function getFilters()
    {
        $data =[
                'search_type' => 'initial',
                'page' => 'case_status'
            ];
        $url = config('nclat.api_url'); 
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
          'headers' => ['token' =>  config('nclat.api_key')],
          'form_params'    => $data
        ]);
        $result = $response->getBody()->getContents();   
        return json_decode($result);
      
    }

    public function getDetails($post)
    {
        $data = [
                'search_type' => 'get_cases',
                'page' => 'case_status',
                'bench_name' => $post['location'],
                'search_by' => $post['search_by'],
                'case_type' => $post['case_type'],
                'case_no' => $post['case_number'],
                'case_year' => $post['case_year'],
                'filing_no' => $post['diary_no'],
            ];
        $url = config('nclat.api_url');
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
          'headers' => ['token' => config('nclat.api_key')],
          'form_params'    => $data
        ]);
        $result = $response->getBody()->getContents();   
        return json_decode($result);      
    }

    public function getViewDetails($post)
    {
        $data = [
                'search_type' => $post['search_type'],
                'bench_name' => $post['bench_name'],
                'filing_no' => $post['filing_no'],
            ];
        $url = config('nclat.api_url');
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
          'headers' => ['token' => config('nclat.api_key')],
          'form_params'    => $data
        ]);
        $result = $response->getBody()->getContents();   
        print_r($result); die();
        return json_decode($result);      
    }
}