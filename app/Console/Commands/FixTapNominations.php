<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixTapNominations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:tap-nominations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix tap nominations table and test functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing tap nominations functionality...');

        try {
            // Check if table exists
            $tables = DB::select("SHOW TABLES LIKE 'tap_nominations'");
            
            if (empty($tables)) {
                $this->info('Table does not exist. Creating it...');
                
                Schema::create('tap_nominations', function ($table) {
                    $table->id('nominationId');
                    $table->foreignId('FK1_taskId')->constrained('tasks', 'taskId')->onDelete('cascade');
                    $table->foreignId('FK2_nominatorId')->constrained('users', 'userId')->onDelete('cascade');
                    $table->foreignId('FK3_nomineeId')->constrained('users', 'userId')->onDelete('cascade');
                    $table->dateTime('nomination_date');
                    $table->string('status', 30)->default('pending');
                    $table->timestamps();
                });
                
                $this->info('Table created successfully!');
            } else {
                $this->info('Table already exists.');
            }
            
            // Check if there are any records
            $count = DB::table('tap_nominations')->count();
            $this->info("Number of records in tap_nominations: $count");
            
            if ($count > 0) {
                $records = DB::table('tap_nominations')->get();
                $this->info('Records:');
                foreach ($records as $record) {
                    $this->line("ID: {$record->nominationId}, Task: {$record->FK1_taskId}, Nominator: {$record->FK2_nominatorId}, Nominee: {$record->FK3_nomineeId}, Status: {$record->status}");
                }
            }
            
            $this->info('Tap nominations functionality fixed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
