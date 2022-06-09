<?php 

namespace App\Traits;

trait DataTable 
{
    public function getDataTableParams($prefix = null)
    {
        $request = request()->all();

        return [
            
            'limit' => isset($request['length']) ? $request['length'] : 10,
            
            'order' => (
                isset($request['order']) && 
                isset($request['order'][0]) && 
                isset($request['order'][0]['column']) &&
                isset($this->columns[$request['order'][0]['column']])
            ) 
                ? $this->columns[$request['order'][0]['column']] 
                : ($prefix ? "{$prefix}.id" : 'id'),
            
            'dir' => (
                isset($request['order']) && 
                isset($request['order'][0]) && 
                isset($request['order'][0]['dir'])
            ) 
                ? $request['order'][0]['dir'] 
                : 'DESC',
            
            'search' => (
                isset($request['search']) && 
                isset($request['search']['value']) && 
                !empty($request['search']['value'])
            )
                ? $request['search']['value']
                : null,
            
            'page' => (isset($request['page']) && !empty($request['page'])),
            'filters' => (isset($request['filters']) && !empty($request['filters'])) 
                ? $request['filters']
                : null,
        ];
    }

    public function getDataTableResult($result)
    {
        $draw = request()->input('draw');

        return [
            "draw"            => intval($draw),  
            "recordsTotal"    => $result->total(),  
            "recordsFiltered" => $result->total(), 
            "data"            => $result,
            'current_page'    => $result->currentPage(),
            'next'            => $result->nextPageUrl(),
            'previous'        => $result->previousPageUrl(),
            'per_page'        => $result->perPage(),   
        ];   
    }
}