<?php declare(strict_types=1);

namespace App\Modules\CaseJudgeApis;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\CaseJudgeApis\CaseJudgeApisService as Service;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;

final class CaseJudgeApisController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {  
        return view('case-judge-api.cases-filters')
         ->with('title', __('Case Status'))
        ->with('FormData', $this->service->getFilters());

    }
 
    public function cases_details(Request $request){
        return view('case-judge-api.cases-details')
        ->with('title', __('Case Details'))
        ->with('postData', __($request->post('location')))
        ->with('Response', $this->service->getDetails($request->all()));
    }

    public function case_view_details(Request $request){ 
         return with('ViewRes', $this->service->getViewDetails($request->all()));
    }

     public function judge_filters()
    {  
        return view('case-judge-api.judge-filters')
        ->with('title', __('Judgments/Daily Orders'))
        ->with('FormData', $this->service->getFilters());

    }
}