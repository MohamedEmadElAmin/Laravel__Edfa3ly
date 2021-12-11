<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command creates a new database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $databaseName = env('DB_DATABASE', false);
        if (!$databaseName)
        {
            $this->info('Skipping creation of database as env(DB_DATABASE) is empty');
            return;
        }
        try
        {
            $schemaName = $databaseName;
            $charset = config("database.connections.mysql.charset",'utf8');
            $collation = config("database.connections.mysql.collation",'utf8_unicode_ci');
            config(["database.connections.mysql.database" => null]);
            $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";
            DB::statement($query);
            config(["database.connections.mysql.database" => $schemaName]);
            $this->info(sprintf('Successfully created %s database', $databaseName));
        }
        catch (\Exception $exception)
        {
            $this->error(sprintf('Failed to create %s database, %s', $databaseName, $exception->getMessage()));
        }

    }

    /**
     * @param  string $host
     * @param  integer $port
     * @param  string $username
     * @param  string $password
     * @return \PDO
     */
    private function getPDOConnection($host, $port, $username, $password)
    {
        return new \PDO(sprintf('mysql:host=%s;port=%d;', $host, $port), $username, $password);
    }

}
