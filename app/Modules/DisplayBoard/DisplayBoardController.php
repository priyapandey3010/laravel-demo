<?php declare(strict_types=1);

namespace App\Modules\DisplayBoard;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\DisplayBoard\DisplayBoardService as Service;
use App\Modules\DisplayBoard\DisplayBoardValidation as Validation;
use App\Modules\Status\Status;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class DisplayBoardController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('display-board-view');
        return view('display-board.index')
            ->with('title', __('Manage Display Board'))
            ->with('statuses', Status::findAll());
    }

    public function datalist()
    {
        guard('display-board-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function store(Request $request)
    {
        guard('display-board-manage');
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

    public function caseNumbers(Request $request)
    {
        guard('display-board-view');
        try 
        {
            $validator = Validator::make(
                $request->all(), [
                    'calendar' => ['required'],
                    'case_type_id' => ['bail','required','integer','exists:case_types,id'],
                ]
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $response = $this->service->getCaseNumbers($validator->validated());
            return $this->success($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function caseTitle(Request $request)
    {
        guard('display-board-view');
        try 
        {
            $validator = Validator::make(
                $request->all(), [
                    'case_number' => ['bail','required','integer','exists:manage_case_master,case_number'],
                ]
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $response = $this->service->getCaseTitle($validator->validated());
            return $this->success($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function reorder(Request $request) 
    {
        guard('display-board-manage');
        try 
        {
            $validator = Validator::make(
                $request->all(), [
                    'dir' => ['required'],
                    'reorder' => ['bail','required','array','min:1'],
                    'reorder.*.id' => ['bail','required','integer','exists:display_board,id'],
                    'reorder.*.sort_order' => ['bail','required', 'integer']
                ]
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->reorder($validator->validated());
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function updateCaseStatus(Request $request)
    {
        guard('display-board-manage');
        try 
        {
            $validator = Validator::make(
                $request->all(), [
                    'status_id' => ['bail','required','integer','exists:status,id'],
                    'cases.*' => ['bail','required','array','min:1'],
                    'cases.*' => ['bail','required', 'integer', 'exists:display_board,id']
                ]
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->updateCaseStatus($validator->validated());
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function updateDisplay(Request $request)
    {
        guard('display-board-manage');
        try 
        {
            // $validator = Validator::make(
            //     $request->all(), [
            //         'is_display' => ['bail','required','boolean'],
            //         'cases.*' => ['bail','required','array','min:1'],
            //         'cases.*' => ['bail','required', 'integer', 'exists:display_board,id']
            //     ]
            // );

            // if ($validator->fails()) 
            //     return $this->error($validator->errors());

            $this->service->updateDisplay();
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function stopDisplay(Request $request)
    {
        guard('display-board-manage');

        try
        {
            $validator = Validator::make(
                $request->all(), [
                    'cases' => ['bail','required','array','min:1'],
                    'cases.*' => ['bail','required', 'integer', 'exists:display_board,id']
                ]
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->stopDisplay($validator->validated());
            return $this->success();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function isDisplayStarted()
    {
        guard('display-board-manage');

        try
        {
            $response['is_display_started'] = $this->service->isDisplayStarted();
            return $this->success($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function sortByItemNumber(Request $request)
    {
        guard('display-board-manage');

        try 
        {
            $request->session()->forget('is_reordered');

            $this->service->sortByItemNumber();
            
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function restartSession(Request $request)
    {
        guard('display-board-manage');
        try 
        {
            $this->service->restartSession();
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function startSession(Request $request)
    {
        guard('display-board-manage');
        try 
        {
            $validator = Validator::make(
                $request->all(), [
                    'id' => ['bail','required','exists:display_board,id']
                ]
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $id = $validator->validated()['id'];

            $this->service->startSession($id);

            $response['is_reordered'] = session('is_reordered') ?? false;
            
            return $this->success($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function stopSession(Request $request)
    {
        guard('display-board-manage');
        try 
        {
            $validator = Validator::make(
                $request->all(), [
                    'id' => ['bail','required','exists:display_board,id']
                ]
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $id = $validator->validated()['id'];

            $this->service->stopSession($id);
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function startNext(Request $request)
    {
        guard('display-board-manage');
        
        try 
        {
            $validator = Validator::make(
                $request->all(), 
                Validation::getRules(null, ['calendar'])
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->startNext($validator->validated());
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function isMultipleInSession()
    {
        guard('display-board-manage');

        try 
        {
            $response['is_multiple_insession'] = $this->service->isMultipleInSession();
            return $this->success($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function isInSession()
    {
        guard('display-board-manage');

        try 
        {
            $response['is_insession'] = $this->service->isInSession();
            return $this->success($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function clearList(Request $request)
    {
        guard('display-board-manage');
        try 
        {
            $validator = Validator::make(
                $request->all(), [
                    'cases.*' => ['bail','required','array','min:1'],
                    'cases.*' => ['bail','required', 'integer', 'exists:display_board,id']
                ]
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->clearList($validator->validated());
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }
}