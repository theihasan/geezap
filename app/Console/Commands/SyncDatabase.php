<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncDatabase extends Command
{
    protected $signature = 'db:sync';
    protected $description = 'Synchronize SQLite database with backup database';

    public function handle()
    {
        $tables = Schema::connection('sqlite')->getAllTables();

        foreach ($tables as $table) {
            $tableName = $table->name;
            
            $data = DB::connection('sqlite')
                ->table($tableName)
                ->get()
                ->toArray();

            DB::connection('backup')->beginTransaction();

            try {
                DB::connection('backup')
                    ->table($tableName)
                    ->delete();

                foreach (array_chunk($data, 1000) as $chunk) {
                    DB::connection('backup')
                        ->table($tableName)
                        ->insert($chunk);
                }

                DB::connection('backup')->commit();
                $this->info("Synchronized table: {$tableName}");
            } catch (\Exception $e) {
                DB::connection('backup')->rollBack();
                $this->error("Failed to sync table {$tableName}: {$e->getMessage()}");
            }
        }
    }
}