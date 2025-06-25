<?php

namespace App\Imports;

use App\Models\ITControl;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ITControlImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        return new ITControl([
            'user_id' => $row[1],
            'assets_number' => $row[2],
            'device_name' => $row[3],
            'windows_license' => $row[4],
            'windows_password' => $row[5],
            'office_license' => $row[6],
            'office_email' => $row[7],
            'office_password' => $row[8],
            'void' => 'false'
        ]);
    }
}
