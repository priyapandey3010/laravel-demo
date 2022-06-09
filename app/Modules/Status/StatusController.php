<?php declare(strict_types=1);

namespace App\Modules\Status;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\Status\StatusService as Service;
use App\Modules\Status\StatusValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class StatusController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('status-view');
        return view('status.index')
            ->with('title', __('message.status_list'));
    }

    public function datalist()
    {
        guard('status-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        guard('status-create');
        return view('status.edit')
            ->with('title', __('message.new_status'));
    }

    public function store(Request $request) 
    {
        guard('status-create');
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
        guard('status-update');
        $row = $this->service->findById($id);
        return view('status.edit', compact('id', 'row'))
            ->with('title', __('message.edit_status'));
    }

    public function show($id)
    {
        guard('status-update');
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
        guard('status-update');
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
        guard('status-delete');
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