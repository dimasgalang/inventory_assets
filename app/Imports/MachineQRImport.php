<?php

namespace App\Imports;

use App\Models\MachineQR;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MachineQRImport implements ToModel, WithStartRow
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
        return new MachineQR([
            'customs_code' => $row[1],
            'machine_code' => $row[2],
            'brand' => $row[3],
            'type' => $row[4],
            'machine_name' => $row[5],
            'serial_number' => $row[6],
            'void' => 'false'
        ]);
    }
}
