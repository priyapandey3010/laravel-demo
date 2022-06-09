<?php declare (strict_types = 1);

namespace App\Modules\Dashboard;

use App\Core\BaseService;
use App\Modules\Court\Court;
use App\Modules\Bench\Bench;
use App\Modules\Cases\Cases;
//use App\Modules\Dashboard\DashboardResource as Resource;
use Illuminate\Support\Facades\DB;

class DashboardService extends BaseService 
{ 
    protected static $resource  = Resource::class; 
   
    public function getTotalList()
    { 
        $arrTotal = array(
            'court' => Court::count(), 
            'bench' => Bench::count(), 
            'cases' => Cases::count(), 
        );

        return $arrTotal;
    }
}