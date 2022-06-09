<?php declare(strict_types=1);

namespace App\Modules\CaseType;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\CaseType\CaseTypeService as Service;
use App\Modules\CaseType\CaseTypeValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class CaseTypeController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('case-master-view');
        return view('case-types.index')
            ->with('title', __('message.case_type_list'));
    }

    public function datalist()
    {
        guard('case-master-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        guard('case-master-create');
        return view('case-types.edit')
            ->with('title', __('message.new_case_type'));
    }

    public function store(Request $request) 
    {
        guard('case-master-create');
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
        guard('case-master-update');
        $row = $this->service->findById($id);
        return view('case-types.edit', compact('id', 'row'))
            ->with('title', __('message.edit_case_type'));
    }

    public function show($id)
    {
        guard('case-master-update');
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
        guard('case-master-update');
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
        guard('case-master-delete');
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