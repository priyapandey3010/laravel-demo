<?php

namespace App\Imports;

use App\Modules\Cases\Cases;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ManageCasesImport implements ToModel,WithHeadingRow,WithValidation
{
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Cases([
            'case_type' => $row['case_type'],
            'item_number' => $row['item_no'],
            'case_title' => $row['case_title'],
            'case_number' => $row['case_no'],
            'court_id' => get_court_id($row['court_no']),
            'bench_id' => get_bench_id($row['bench_name']),
            'status_id' => get_status_id($row['status']),
            'category' => $row['category'],
            'sort_order' => $row['sn'],
            'is_active' => true,
            'is_display' => false,
            'created_date' => date('Y-m-d', strtotime($this->payload['upload_date'])),
            'date_of_hearing' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row['date_of_hearing']))->format('Y-m-d H:i:s'),
            'created_by' => auth()->user()->id
        ]);
    }

    public function rules(): array
    {
        return [
            'case_type' => [
                'required',
                'string',
                //'exists:case_types,case_type'
            ],
            'item_no' => [
                'required',
                'alpha_dash',
                'max:100',
                'unique:cases,item_number'
            ],
            'case_title' => [
                'required',
                'string',
                'max:100'
            ],
            'status' => [
                'required',
                'string',
                'exists:status,status'
            ],
            'court_no' => [
                'required',
                //'exists:court,court_number'
            ],
            'court_name' => [
                'required',
                'string',
                'exists:court,court_name'
            ],
            'bench_name' => [
                'required',
                'string',
                'exists:bench,bench_name'
            ],
            'status' => [
                'nullable',
                'string',
                'exists:status,status'
            ],
            'sn' => [
                'required',
                'integer'
            ],
            'date_of_hearing' => [
                'required',
            ],
            'category' => [
                'required',
                'string',
                //'exists:categories,name'
            ]
        ];
    }
}
