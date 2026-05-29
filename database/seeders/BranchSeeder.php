<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * 12 Kecamatan dalam satu Kabupaten
     */
    public function run(): void
    {
        $branches = [
            ['code' => 'BPR-KEC-01', 'name' => 'Kecamatan Pusat', 'address' => 'Jl. Raya Pusat No. 1'],
            ['code' => 'BPR-KEC-02', 'name' => 'Kecamatan Utara', 'address' => 'Jl. Raya Utara No. 2'],
            ['code' => 'BPR-KEC-03', 'name' => 'Kecamatan Selatan', 'address' => 'Jl. Raya Selatan No. 3'],
            ['code' => 'BPR-KEC-04', 'name' => 'Kecamatan Timur', 'address' => 'Jl. Raya Timur No. 4'],
            ['code' => 'BPR-KEC-05', 'name' => 'Kecamatan Barat', 'address' => 'Jl. Raya Barat No. 5'],
            ['code' => 'BPR-KEC-06', 'name' => 'Kecamatan Tengah', 'address' => 'Jl. Raya Tengah No. 6'],
            ['code' => 'BPR-KEC-07', 'name' => 'Kecamatan Makmur', 'address' => 'Jl. Raya Makmur No. 7'],
            ['code' => 'BPR-KEC-08', 'name' => 'Kecamatan Sejahtera', 'address' => 'Jl. Raya Sejahtera No. 8'],
            ['code' => 'BPR-KEC-09', 'name' => 'Kecamatan Maju', 'address' => 'Jl. Raya Maju No. 9'],
            ['code' => 'BPR-KEC-10', 'name' => 'Kecamatan Berkah', 'address' => 'Jl. Raya Berkah No. 10'],
            ['code' => 'BPR-KEC-11', 'name' => 'Kecamatan Harapan', 'address' => 'Jl. Raya Harapan No. 11'],
            ['code' => 'BPR-KEC-12', 'name' => 'Kecamatan Sentosa', 'address' => 'Jl. Raya Sentosa No. 12'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
