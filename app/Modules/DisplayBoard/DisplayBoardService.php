<?php declare(strict_types=1);

namespace App\Modules\DisplayBoard;

use App\Core\BaseService;
use App\Modules\Cases\Cases as Model;
use App\Modules\ManageCaseMaster\ManageCaseMaster;
use App\Modules\Status\Status;
use App\Modules\Cases\CaseResource as Resource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Modules\AuditTrail\AuditTrail;   

class DisplayBoardService extends BaseService 
{
    protected static $model     = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        2 => 'item_number',
        3 => 'case_type',
        4 => 'category',
        5 => 'case_title',
        6 => 'case_number',
        7 => 'bench_id',
        8 => 'court_id',
        9 => 'status_id'
    ];

    public function getDataTableList()
    {
        [
            'search' => $search,
            'order' => $order,
            'limit' => $limit,
            'dir' => $dir,
            'page' => $page,
            'filters' => $filters
        ] = $this->getDataTableParams();
        
        $query = Model::with(['status','case_type', 'category']);

        if (isset($search) && !empty($search)) 
        {
            $query->where(function ($query) use ($search) {
                $query
                    ->likeCaseNumber($search)
                    ->orLikeStatus($search)
                    ->orLikeItemNumber($search)
                    ->orLikeCaseTitle($search)
                    ->orLikeBenchName($search)
                    ->orLikeCourtName($search)
                    ->orLikeCategoryName($search);
            });
        }

      
        if (auth()->user()->role->slug === 'court-user') {
            $query->where(function($query) {
                $query->where('court_id', auth()->user()->court_id);
                $query->orWhere('created_by', auth()->user()->id);
            });
        }
        else if (auth()->user()->role->slug === 'bench-admin') {
            $query->where(function($query) {
                $query->where('bench_id', auth()->user()->bench_id);
                $query->orWhere('created_by', auth()->user()->id);
            });
        }
        else {
            $query->where('created_by', auth()->user()->id);
        }
        
        // if (isset($filters['case_type_id']) && $filters['case_type_id']) {
        //     $query->where('case_type_id', $filters['case_type_id']);
        // }

        // if (isset($filters['category_id']) && $filters['category_id']) {
        //     $query->where('category_id', $filters['category_id']);
        // }

        // if (isset($filters['case_numbers']) && $filters['case_numbers']) {
        //     $query->whereIn('case_number', $filters['case_numbers']);
        // }

        if (isset($filters['calendar']) && $filters['calendar']) {
            
            $date = Carbon::createFromFormat('d/m/Y', trim($filters['calendar']));
            $query->whereDate('created_date', $date);
        }
        else 
        {
            $query->whereDate('created_date', \Carbon\Carbon::today());
        }
       
        if ($order == 'item_number') {
            $query->orderBy('item_number', 'ASC');
        }
        else {
            $query->orderBy('sort_order', 'ASC');
        }

        if (isset($page) && !empty($page)) {
            return $this->getDataTableResult(
                Resource::collection(
                    $query->paginate($limit)
                )
            );
        }

        return Resource::collection($query->get());
    }

    public function nextSortOrder()
    {
        $model = Model::latestSortOrder();    
        return $model ? ((int) $model['sort_order']) + 1 : 1;
    }

    public function reorder($payload)
    {
        $items = $payload['reorder'];
        $dir = $payload['dir'];

        session(['is_reordered' => true]);

        return DB::transaction(function () use ($items, $dir) {
            $itemsCount = count($items);
            $iterationLength = $itemsCount - 2;
            
            if ($dir === 'down') 
            {
                $firstItemSortOrder = $items[0]['sort_order'];

                for ($index =0; $index < $iterationLength; $index++) 
                {
                    $items[$index]['sort_order'] += 1;
                }

                $items[$itemsCount-2]['sort_order'] = (int) $firstItemSortOrder;
            }

            if ($dir === 'up')
            {
                $lastItemSortOrder = $items[$itemsCount-1]['sort_order'];

                for ($index = 2; $index < $itemsCount; $index++) 
                {
                    $items[$index]['sort_order'] -= 1;
                }

                $items[1]['sort_order'] = $lastItemSortOrder;
            }

            foreach ($items as $item)
            {
                Model::id($item['id'])->update([
                    'sort_order' => $item['sort_order']
                ]);
            }
        });
    }

    public function updateCaseStatus($payload)
    {   
         $user = auth()->user();
        //  $activity='';
        //  $caseId=implode(',', $payload['cases']);
        // if($payload['status_id']==2)
        // {
        // $activity='In Session';
        // }
        // if($payload['status_id']==4)
        // {
        // $activity='Not In Session';
        // } if($payload['status_id']==5)
        // {
        // $activity='Pass Over';
        // } if($payload['status_id']==6)
        // {
        // $activity='Completed';
        // } if($payload['status_id']==7)
        // {
        // $activity='Yet to be Taken';
        // } 
        //  AuditTrail::create([
        //     'module_name' => 'DisplayBoard',
        //     'activity_type' => $activity.'-CaseId: '.$caseId,
        //     'user_name' => $user->username,
        //     'email_id' => $user->email,
        //     'role_id' => $user->role_id,
        //     'last_access' => date('Y-m-d'),
        //     'last_login' => date('Y-m-d'),
        //     'activity_datetime' => date('Y-m-d'), 
        //     'logout_time' => date('Y-m-d'), 
        //     'ip_address' => $_SERVER['REMOTE_ADDR']
        // ]); 

        $statusNotInSessionID = status_id('Not In Session');

        $statusInSessionID = status_id('In Session');

        if (intval($payload['status_id']) === intval($statusNotInSessionID)) 
        {
            if (is_role($user->role_id, config('role.bench-admin')))
            {
                $cases = $payload['cases'];

                $models = Model::whereIn('id', $cases)->get();

                foreach ($models as $model) 
                {
                    if (intval($model->status_id) !== intval($statusInSessionID)) 
                    {
                        throw new \Exception('Error: Cases status must be In Session. Please check!');
                    }
                }

                return $models->each(function($model) use ($statusNotInSessionID) {
                    $model->update(['status_id' => $statusNotInSessionID]);
                });
            }
            
            if (is_role($user->role_id, config('role.court-user'))) 
            {
                return Model::courtId($user->court_id)->createdDate(Carbon::today())->statusId($statusInSessionID)->update([
                    'status_id' => $statusNotInSessionID
                ]);
            }
        }

        return Model::whereIn('id', $payload['cases'])->update([
            'status_id' => $payload['status_id']
        ]);
    }

    public function updateDisplay()
    {    
        $user = auth()->user(); 
        $activity='';
        // if($payload['is_display']==1)
        // {
        // $activity='Start Display';
        // }
        // if($payload['is_display']==0)
        // {
        // $activity='Stop Display';
        // } 
        // AuditTrail::create([
        //     'module_name' => 'DisplayBoard',
        //     'activity_type' => $activity,
        //     'user_name' => $user->username,
        //     'email_id' => $user->email,
        //     'role_id' => $user->role_id,
        //     'last_access' => date('Y-m-d'),
        //     'last_login' => date('Y-m-d'),
        //     'activity_datetime' => date('Y-m-d'), 
        //     'logout_time' => date('Y-m-d'), 
        //     'ip_address' => $_SERVER['REMOTE_ADDR']
        // ]); 
        // return Model::whereIn('id', $payload['cases'])->update([
        //     'is_display' => $payload['is_display']
        // ]);

        $query = Model::query();

        if (is_role($user->role_id, config('role.bench-admin'))) 
            $query->benchId($user->bench_id);

        if (is_role($user->role_id, config('role.court-user'))) 
            $query->courtId($user->court_id);

        $model = $query->createdDate(Carbon::today())->oldestSortOrder()->statusIsNull()->first();

        return $model->update([
            'status_id' => status_id('In Session'),
            'is_started' => true
        ]);
    }

    public function stopDisplay($payload)
    {
        $statusNotInSessionID = status_id('Not In Session');

        $statusInSessionID = status_id('In Session');

        $cases = $payload['cases'];

        $models = Model::whereIn('id', $cases)->get();

        return $models->each(function($model) use ($statusNotInSessionID) {
            $model->update(['status_id' => $statusNotInSessionID]);
        });
            
    }

    public function isDisplayStarted()
    {
        $user = auth()->user(); 

        $query = Model::query();

        if (is_role($user->role_id, config('role.bench-admin'))) 
            $query->benchId($user->bench_id);

        if (is_role($user->role_id, config('role.court-user'))) 
            $query->courtId($user->court_id);

        $count = $query->createdDate(Carbon::today())->isStarted(true)->count();

        return $count > 0;
    }

    public function getOldestCasesByItemNumber()
    {
        $user = auth()->user();

        $query = Model::query();

        if (is_role($user->role_id, config('role.bench-admin'))) 
            $query->benchId($user->bench_id);

        if (is_role($user->role_id, config('role.court-user'))) 
            $query->courtId($user->court_id);
        
        return $query->oldestItemNumber()->get();
    }

    public function sortByItemNumber()
    {
        $models = $this->getOldestCasesByItemNumber();

        if ($models) 
        {
            $incrementor = 1;
            return $models->each(function ($model) use (&$incrementor) {
                $model->update(['sort_order' => $incrementor]);
                $incrementor++;
            });
        }
    }

    public function restartSession()
    {   
        $user = auth()->user();         
        
        $this->audit('Restart Session');

        $query = Model::query();

        if (is_role($user->role_id, config('role.bench-admin'))) 
            $query->benchId($user->bench_id);

        if (is_role($user->role_id, config('role.court-user'))) 
            $query->courtId($user->court_id);

        if (is_role($user->role_id, config('role.bench-admin'))) 
        {
            return $query
                ->createdDate(Carbon::today())
                ->statusId(status_id('Not In Session'))
                ->update([
                    'status_id' => status_id('In Session')
                ]);
        }

        if (is_role($user->role_id, config('role.court-user'))) 
        {
            $model =  $query
                ->createdDate(Carbon::today())
                ->statusId(status_id('Not In Session'))
                ->latest()
                ->first();

            return $model->update([
                'status_id' => status_id('In Session')
            ]);
                
        }
        
    }

    public function audit($activityType)
    {
        $user = auth()->user(); 
        
        AuditTrail::create([
            'module_name' => 'DisplayBoard',
            'activity_type' => $activityType,
            'user_name' => $user->username,
            'email_id' => $user->email,
            'role_id' => $user->role_id,
            'last_access' => date('Y-m-d'),
            'last_login' => date('Y-m-d'),
            'activity_datetime' => date('Y-m-d'), 
            'logout_time' => date('Y-m-d'), 
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ]); 
    }

    public function startSession($id)
    {   
        $model = Model::findOrFail($id);

        $count = Model::isStarted(true)
            ->createdDate($model->created_date)
            ->courtId($model->court_id)
            ->benchId($model->bench_id)
            ->statusId(status_id('In Session'))
            ->notId($id)
            ->count();

        if ($count) 
            throw new \Exception("Error: Could not start more than one session of same court.");

        $previousModel = Model::createdDate($model->created_date)
            ->courtId($model->court_id)
            ->benchId($model->bench_id)
            ->statusIsNull()
            ->notId($id)
            ->oldestSortOrder()
            ->first();

        $result = null;

        if ($previousModel && abs($model->sort_order - $previousModel->sort_order) > 0) 
        {
            Model::createdDate($model->created_date)
            ->courtId($model->court_id)
            ->benchId($model->bench_id)
            ->notId($id)
            ->statusIsNull()
            ->belowSortOrder($model->sort_order)
            ->update([
                'status_id' => status_id('Yet to be Taken'),
                'is_started' => true,
                'is_display' => true
            ]);

            $result = $model->update([
                'status_id' => status_id('In Session'), 
                'is_pass_over' => true,
                'is_display' => true, 
                'is_started' => true
            ]); 

            session(['is_reordered' => true]);

        }
        else 
        {
            $result = $model->update([
                'status_id' => status_id('In Session'), 
                'is_display' => true, 
                'is_started' => true
            ]);  
        }

        if ($result) 
            $this->audit('Start Session');
        
        return $result;             
    }

    public function stopSession($id)
    {   
        $result = Model::id($id)->update([
            'status_id' => status_id('Completed'),
        ]);

        if ($result) 
            $this->audit('Stop Session');

        return $result;
    }

    public function clearList($payload)
    {
        $user = auth()->user();         
        AuditTrail::create([
            'module_name' => 'DisplayBoard',
            'activity_type' => 'Clear List',
            'user_name' => $user->username,
            'email_id' => $user->email,
            'role_id' => $user->role_id,
            'last_access' => date('Y-m-d'),
            'last_login' => date('Y-m-d'),
            'activity_datetime' => date('Y-m-d'), 
            'logout_time' => date('Y-m-d'), 
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ]); 
        return Model::whereIn('id', $payload['cases'])->delete();
    }

    public function getCaseNumbers($payload)
    {
        $caseTypeId = $payload['case_type_id'];
        $model = ManageCaseMaster::select('case_number')
            ->where('case_type_id', $caseTypeId)
            ->get()
            ->toArray();
        $caseNumbers = array_column($model, 'case_number');
        return ['caseNumbers' => $caseNumbers];
    }

    public function getCaseTitle($payload)
    {
        $caseNumber = $payload['case_number'];
        $model = ManageCaseMaster::select('case_title')
            ->where('case_number', $caseNumber)
            ->first();
        return ['caseTitle' => $model->case_title];
    }

    public function create($payload)
    {
        $user = auth()->user();
        
        if ($user->role_id == config('role.bench-admin')) {
            $payload['bench_id'] = $user->bench_id; 
        }

        if ($user->role_id == config('role.court-user')) {
            $payload['bench_id'] = $user->bench_id;
            $payload['court_id'] = $user->court_id;
        }

        $payload['case_type'] = get_case_type_by_id($payload['case_type_id']);
        $payload['category'] = get_category_by_id($payload['category_id']);
        $payload['is_active'] = true;
        $payload['sort_order'] = $this->nextSortOrder();
        $payload['created_date'] = date('Y-m-d');
        $payload['created_by'] = $user->id;

        unset($payload['case_type_id'], $payload['category_id']);
        return Model::create($payload);
    }

    public function getNextUnstartedCase($createdDate, $statusId)
    {
        $user = auth()->user();  
        
        $query = Model::query();

        if (is_role($user->role_id, config('role.bench-admin'))) 
            $query->benchId($user->bench_id);

        if (is_role($user->role_id, config('role.court-user'))) 
            $query->courtId($user->court_id);

        return $query->where(function ($query) use ($statusId) {
            $query->statusIsNull();
        })
        ->createdDate($createdDate)
        ->oldestSortOrder()
        ->first();
    }

    public function startNext($payload)
    {
        $createdDate = db_date($payload['calendar'], 'd/m/Y');
        $statusInSessionID = status_id('In Session');
        $statusCompletedID = status_id('Completed');
        $model = $this->getNextUnstartedCase($createdDate, $statusInSessionID);
        
        if ($model) 
        {
            $model->update([
                'status_id' => $statusInSessionID,
                'is_display' => true,
                'is_started' => true
            ]);

            return Model::belowSortOrder($model->sort_order)
                ->statusId(status_id('In Session'))
                ->update(['status_id' => $statusCompletedID]);
        }
    }

    public function countInSessionItems()
    {
        return Model::createdDate(Carbon::today())
            ->statusId(status_id('In Session'))
            ->isStarted(true)
            ->count();
    }

    public function isMultipleInSession()
    {
        return $this->countInSessionItems() > 1;
    }

    public function isInSession()
    {
        return $this->countInSessionItems() > 0;
    }
}
