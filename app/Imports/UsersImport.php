<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        
        // Count the total number of records
        $totalRecords = $rows->count();
         
        foreach ($rows as $row) {

            try {
                // Import the user record.
                $user = new User([
                    'first_name' => $row['firstname'],
                    'last_name' => $row['lastname'],
                    'phone_number' => $row['phonenumber'],
                    'email' => $row['email'],
                ]);
                $user->save();

                 // Log the successful records.
                 Log::create([
                    'import_type' => 'Import_User',
                    'total_records' => $totalRecords,
                    'successful_records' => 1,
                    'failed_records' => 0,
                    'failure_reason' => '',
                ]);
            } catch (\Exception $e) {

                // Log the failure reason.
                Log::create([
                    'import_type' => 'Import_User',
                    'total_records' => $totalRecords,
                    'successful_records' => 0,
                    'failed_records' => 1,
                    'failure_reason' => $e->getMessage(),
                ]);
            }
            
        }

    }
}