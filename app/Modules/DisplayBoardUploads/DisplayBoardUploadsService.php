<?php declare (strict_types = 1);

namespace App\Modules\DisplayBoardUploads;

use App\Core\BaseService;
use App\Modules\DisplayBoardUploads\DisplayBoardUploads as Model;
use App\Modules\DisplayBoardUploads\DisplayBoardUploadsResource as Resource;
use App\Imports\ManageCasesImport;
use Excel;

class DisplayBoardUploadsService extends BaseService 
{
    protected static $model     = Model::class;
    protected static $resource  = Resource::class;

    protected $columns = [
        1 => 'original_file_name',
        2 => 'upload_date'
    ];

    public function getDataTableList()
    {
        [
            'search' => $search,
            'order' => $order,
            'limit' => $limit,
            'dir' => $dir,
            'page' => $page
        ] = $this->getDataTableParams();

        $query = Model::query();

        if (isset($search) && !empty($search)) 
        {
            $query->where(function ($query) use ($search) {
                $query->likeOriginalFileName($search);
                $query->orLikeUploadDate($search);
            });
        }

        if (auth()->user()->role->slug !== 'super-admin') {
            $query->where('created_by', auth()->user()->id);
        }

        $query
            ->submitted()
            ->orderBy($order, $dir);

        if (isset($page) && !empty($page)) {
            return $this->getDataTableResult(
                Resource::collection(
                    $query->paginate($limit)
                )
            );
        }

        return Resource::collection($query->get());
    }

    public function upload($payload)
    {
        if (request()->file()) 
        {
            $originalFileName = request()->file->getClientOriginalName();
            $sysFileName = time() . '_' . $originalFileName;
            $filePath = request()->file('file')->storeAs(
                "uploads/display-boards", $sysFileName    
            );

            return Model::create([
                'file_name' => $sysFileName,
                'original_file_name' => $originalFileName,
                'upload_date' => $payload['upload_date'],
                'is_submitted' => true,
                'created_by' => auth()->user()->id
            ]);
        }
    }

    public function import($payload)
    {
        if (Excel::import(new ManageCasesImport($payload), request()->file)) {
            return $this->upload($payload);
        }
    }

    public function destroy($id)
    {
        $file = Model::findOrFail($id);
        unlink(storage_path('app/uploads/display-boards/'. $file->file_name));
        return Model::where('id', $id)->delete();
    }
}