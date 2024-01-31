<?php

namespace App\Imports;

use App\Models\Office\Area;
use App\Models\Office\Branch;
use App\Models\Office\Unit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BranchUnitImport implements ToCollection, WithHeadingRow, WithChunkReading
{

    public function collection(Collection $collection): void
    {

        foreach ($collection as $item) {
            $area = Area::query()
                ->firstOrCreate([
                    'id' => $item['area_id'],
                    'code' => $item['area_code'],
                    'name' => $item['area_name'],
                    'user_id' => 1
                ]);

            $branch = Branch::query()
                ->firstOrCreate([
                    'id' => $item['branch_id'],
                    'name' => $item['branch_name'],
                    'area_id' => $item['area_id'],
                    'user_id' => 1
                ]);

            Unit::query()
                ->firstOrCreate([
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'type' => $item['type'],
                    'branch_id' => $item['branch_id'],
                    'area_id' => $item['area_id'],
                    'user_id' => 1
                ]);
        }
    }
    public function chunkSize(): int
    {
        return 100;
    }
}
