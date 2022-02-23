<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\GenderDatabaseSeeder;
use Database\Seeders\OrderStatusDatabaseSeeder;
use Database\Seeders\RoleDatabaseSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GenderDatabaseSeeder::class,
            RoleDatabaseSeeder::class,
            OrderStatusDatabaseSeeder::class,
        ]);
    }
}
