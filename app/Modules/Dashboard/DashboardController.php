<?php declare(strict_types=1);

namespace App\Modules\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\DashboardService as Service;
use Illuminate\Http\Request;

final class DashboardController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {   
        $response = $this->service->getTotalList(); 
        return view('dashboard.index')
            ->with('title', __('Dashboard'))
            ->with('total', $response);
    }

     

}
