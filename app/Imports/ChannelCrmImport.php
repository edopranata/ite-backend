<?php

namespace App\Imports;

use App\Models\Asset\Channel;
use App\Models\Office\Branch;
use App\Models\Office\Unit;
use App\Models\Vendor\Vendor;
use App\Models\Vendor\VendorBranch;
use App\Models\Vendor\VendorProcessing;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChannelCrmImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        try {

            foreach ($collection as $item) {

                $branch = Branch::query()->where('id', $item['kc_spv_kode'])->first();

                $unit = Unit::query()->where('id', $item['pengelola_kode'])->first();

                $manage_by = $item['pengelola_jenis'];

                $manage_id = $unit->id;
                $manage_type = get_class(new Unit());

                if ($manage_by === 'CRO') {
                    $vendor = Vendor::query()
                        ->firstOrCreate([
                            'user_id' => auth()->id(),
                            'name' => $item['pengelola_vendor']
                        ]);
                    $vendor_branch = VendorBranch::query()
                        ->firstOrCreate([
                            'user_id' => auth()->id(),
                            'vendor_id' => $vendor->id,
                            'name' => $item['pengelola']
                        ]);

                    $vendor_processing = VendorProcessing::query()
                        ->updateOrCreate([
                            'user_id' => auth()->id(),
                            'vendor_id' => $vendor->id,
                            'vendor_branch_id' => $vendor_branch->id,
                            'name' => $item['pengelola_cpc']
                        ]);

                    $manage_id = $vendor_processing->id;
                    $manage_type = get_class(new VendorProcessing());
                }


                $channel = Channel::query()
                    ->updateOrCreate([
                        'id' => $item['tid'],
                        'user_id' => auth()->id(),
                        'area_id' => $branch->area_id,
                        'branch_id' => $branch->id,
                        'manage_id' => $manage_id,
                        'manage_type' => $manage_type,
                        'manage_by' => $manage_by,
                        'type' => 'CRM',
                        'brand' => $item['merk_atm'],
                        'brand_vendor' => $item['vendor'],
                        'sn' => $item['sn'],
                        'location' => $item['lokasi'],
                        'location_category' => $item['lokasi_kategori'],
                        'location_category_group' => $item['lokasi_kategori_group'],
                        'site' => str($item['lokasi_jenis'])->trim()->upper(),
                        'live_date' => str($item['live_date'])->trim() ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item['live_date']) : null,
                        'warranty' => $item['garansi']
                    ]);
            }
        } catch (\Exception $exception){

        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
