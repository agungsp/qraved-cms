<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () use ($request) {
            Setting::updateOrCreate(
                ['key' => 'qr_url'],
                ['value' => 'localhost']
            );
            Setting::updateOrCreate(
                ['key' => 'qr_length'],
                ['value' => 15]
            );
            Setting::updateOrCreate(
                ['key' => 'qr_prefix'],
                ['value' => null]
            );
        });
    }
}
