<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\ProductsImport;
use App\Models\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImportProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-product {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a Product CSV file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        // Log the import results.
        $deleteLog = Log::where('import_type', 'Import_Product')->delete();

        // Import the CSV file.
        Excel::import(new ProductsImport, $file);

        // Count the number of successful records
        $successfulRecords = 0;

        // Count the number of failed records
        $failedRecords = 0;

        // Log the import results.
        $importLog = Log::where('import_type', 'Import_Product')->get();

        // Count the total number of records
        $totalRecords = $importLog->count();

        $successfulRecords = $importLog->where('successful_records',1)->count();

        // Calculate the number of failed records
        $failedRecords = $totalRecords - $successfulRecords;

        $this->info('CSV import results:');
        $this->info('Total records: ' . $totalRecords);
        $this->info('Successful records: ' . $successfulRecords);
        $this->info('Failed records: ' . $failedRecords);

        if ($failedRecords > 0) {
            $this->error('Failed Reasons:');
            foreach ($importLog->where('failed_records',1) as $failureReason) {
                $this->error($failureReason->failure_reason);
            }
        }
    }
}
