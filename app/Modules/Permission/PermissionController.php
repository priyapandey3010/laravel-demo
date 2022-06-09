<?php declare(strict_types=1);

namespace App\Modules\Permission;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\Permission\PermissionService as Service;
use App\Modules\Permission\PermissionValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class PermissionController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('permission-view');
        return view('permission.index')
            ->with('title', __('message.permission_list'));
    }

    public function datalist()
    {
        guard('permission-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        guard('permission-create');
        return view('permission.edit')
            ->with('title', __('message.new_permission'));
    }

    public function store(Request $request) 
    {
        guard('permission-create');
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

    public function edit($id)
    {
        guard('permission-update');
        $row = $this->service->findById($id);
        return view('permission.edit', compact('id', 'row'))
            ->with('title', __('message.edit_permission'));
    }

    public function show($id)
    {
        guard('permission-update');
        try 
        {
            $response = $this->service->findById($id);
            return $this->success($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function update(Request $request, $id) 
    {
        guard('permission-update');
        try 
        {
            $validator = Validator::make(
                $request->all(), 
                Validation::getRules((int) $id)
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->update($validator->validated(), $id);
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function destroy($id)
    {
        guard('permission-delete');
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