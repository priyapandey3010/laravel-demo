<?php declare(strict_types=1);

namespace App\Modules\ManageCaseMaster;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\ManageCaseMaster\ManageCaseMasterService as Service;
use App\Modules\ManageCaseMaster\ManageCaseMasterValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class ManageCaseMasterController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('manage-cases-view');
        return view('cases.index')
            ->with('title', __('message.manage_case_list'));
    }

    public function datalist()
    {
        guard('manage-cases-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        guard('manage-cases-create');
        return view('cases.edit')
            ->with('title', __('message.new_manage_case'));
    }

    public function store(Request $request) 
    {
        guard('manage-cases-create');
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
        guard('manage-cases-update');
        $row = $this->service->findById($id);
        return view('cases.edit', compact('id', 'row'))
            ->with('title', __('message.edit_case'));
    }

    public function show($id)
    {
        guard('manage-cases-update');
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
        guard('manage-cases-update');
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
        guard('manage-cases-delete');
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