<?php declare(strict_types=1);

namespace App\Modules\PermissionGroup;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\PermissionGroup\PermissionGroupService as Service;
use App\Modules\PermissionGroup\PermissionGroupValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class PermissionGroupController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function show($id)
    {
        guard('permission-update');
        try 
        {
            $response['permissions'] = $this->service->findById($id);
            return $this->success($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }
    
    public function store(Request $request) 
    {
        guard('permission-update');
        try 
        {
            $validator = Validator::make(
                $request->all(), 
                Validation::getRules()
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $response = $this->service->create($validator->validated());
            return $this->created($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function destroy($id)
    {
        guard('permission-update');
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