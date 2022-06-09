<?php declare(strict_types=1);

namespace App\Modules\Department;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\Department\DepartmentService as Service;
use App\Modules\Department\DepartmentValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class DepartmentController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('department-view');
        return view('department.index')
            ->with('title', __('message.department_list'));
    }

    public function datalist()
    {
        guard('department-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        guard('department-create');
        return view('department.edit')
            ->with('title', __('message.new_department'));
    }

    public function store(Request $request) 
    {
        guard('department-create');
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
        guard('department-update');
        $row = $this->service->findById($id);
        return view('department.edit', compact('id', 'row'))
            ->with('title', __('message.edit_department'));
    }

    public function show($id)
    {
        guard('department-update');
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
        guard('department-update');
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
        guard('department-destroy');
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