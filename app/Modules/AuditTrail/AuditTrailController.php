<?php declare(strict_types=1);

namespace App\Modules\AuditTrail;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\AuditTrail\AuditTrailService as Service;
use App\Modules\AuditTrail\AuditTrailValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

final class AuditTrailController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    { 
        //guard('audit-trail-view');die('test');
        return view('audit-trail.index')
            ->with('title', __('Audit Trail'));
    }

    public function datalist()
    {
        //guard('audit-trail-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        
    }

    public function store(Request $request) 
    {
         
    }

    public function edit($id)
    {
         
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id) 
    {
         
    }

     
    
    
    public function destroy($id)
    {
        guard('audit-trail-delete');
        try 
        {
            $this->service->destroy($id);
            return $this->deleted();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }
}