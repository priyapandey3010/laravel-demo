<?php declare(strict_types=1);

namespace App\Modules\DisplayBoardUploads;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\DisplayBoardUploads\DisplayBoardUploadsService as Service;
use App\Modules\DisplayBoardUploads\DisplayBoardUploadsValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class DisplayBoardUploadsController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('display-board-upload-view');
        return view('display-board-uploads.index')
            ->with('title', __('message.display_board_uploads_list'));
    }

    public function datalist()
    {
        guard('display-board-upload-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        guard('display-board-upload-create');
        return view('display-board-uploads.edit')
            ->with('title', __('message.new_display_board_uploads'));
    }

    public function upload(Request $request)
    {
        guard('display-board-upload-create');
        try 
        {
            $validator = Validator::make(
                $request->all(),
                Validation::getRules(null, ['file'])
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $response = $this->service->upload();
            return $this->success($response);
        }
        catch (Throwable $exception)
        {
            return $this->handler($exception);
        }
    }

    public function store(Request $request)
    {
        guard('display-board-upload-create');
        try
        {
            $validator = Validator::make(
                $request->all(),
                Validation::getRules()
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $response = $this->service->import($validator->validated());
            
            return $this->created($response);
        }
        catch (Throwable $exception)
        {
            return $this->handler($exception);
        }
    }

    public function destroy($id)
    {
        guard('display-board-upload-delete');
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