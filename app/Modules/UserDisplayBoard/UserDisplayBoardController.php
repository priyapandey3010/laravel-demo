<?php declare(strict_types=1);

namespace App\Modules\UserDisplayBoard;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\UserDisplayBoard\UserDisplayBoardService as Service;
use Illuminate\Http\Request;
use App\Modules\Status\Status;
use Illuminate\Support\Facades\Validator;

final class UserDisplayBoardController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('user-display-board.index')
        ->with('statuses', Status::findAll())
        ->with('cases', $this->service->getCases());

    }

    public function getList()
    {
        $response['cases'] = $this->service->getCases();
        return $this->success($response);
    }

    public function setBench(Request $request)
    {
        try 
        {
            $validator = Validator::make(
                $request->all(), [
                    'bench_id' => ['bail','required','integer','exists:bench,id'],
                ]
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $validated = $validator->validated();
            session(['bench_id' => $validated['bench_id'] ]);

            return $this->success();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function details($id)
    {
        return view('user-display-board.court-cases')
        ->with('cases', $this->service->getCourtCases($id))
        ->with('bench', $this->service->getBenchName($id))
        ->with('courtId', $id);
    }

    public function getCourtCases($id)
    {
        $response['cases'] = $this->service->getCourtCases($id);
        return $this->success($response);
    }

}