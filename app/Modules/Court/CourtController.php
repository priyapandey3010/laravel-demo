<?php declare(strict_types=1);

namespace App\Modules\Court;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\Court\CourtService as Service;
use App\Modules\Court\CourtValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class CourtController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('court-view');
        return view('court.index')
            ->with('title', __('message.court_list'));
    }

    public function datalist()
    {
        guard('court-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        guard('court-create');
        return view('court.edit')
            ->with('title', __('message.new_court'));
    }

    public function store(Request $request) 
    {
        guard('court-create');
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
        guard('court-update');
        $row = $this->service->findById($id);
        return view('court.edit', compact('id', 'row'));
    }

    public function show($id)
    {
        guard('court-update');
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
        guard('court-update');
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
        guard('court-delete');
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