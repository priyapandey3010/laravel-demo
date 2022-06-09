<?php declare(strict_types=1);

namespace App\Modules\Role;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\Role\RoleService as Service;
use App\Modules\Role\RoleValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class RoleController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('role-view');
        return view('role.index')
            ->with('title', __('message.role_list'));
    }

    public function datalist()
    {
        guard('role-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        guard('role-create');
        return view('role.edit')
            ->with('title', __('message.new_role'));
    }

    public function store(Request $request) 
    {
        guard('role-create');
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
        guard('role-update');
        $row = $this->service->findById($id);
        return view('role.edit', compact('id', 'row'))
            ->with('title', __('message.edit_role'));
    }

    public function show($id)
    {
        guard('role-update');
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
        guard('role-update');
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
        guard('role-delete');
        try 
        {
            $this->service->destroy($id);
            return $this->deleted();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($exception);
        }
    }
}