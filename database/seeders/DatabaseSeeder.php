<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Restaurant;
use App\Models\QrCode;
use App\Helpers\SettingHelper;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            User::create([
                'name' => 'admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('admin')
            ]);
            $this->command->alert('User Admin');
            $this->command->info("email\t: admin@mail.com");
            $this->command->info("password: admin");

            Setting::updateOrCreate(
                ['key' => 'qr_url'],
                ['value' => url('/resto')]
            );
            Setting::updateOrCreate(
                ['key' => 'qr_length'],
                ['value' => 20]
            );
            Setting::updateOrCreate(
                ['key' => 'qr_prefix'],
                ['value' => '?qr=']
            );
            $this->command->alert('Settings');
            $this->command->info("qr_url\t: " . SettingHelper::getAll()['qr_url']);
            $this->command->info("qr_length\t: " . SettingHelper::getAll()['qr_length']);
            $this->command->info("qr_prefix\t: " . SettingHelper::getAll()['qr_prefix']);

            $qr_length = SettingHelper::getAll()['qr_length'];
            $qr_prefix = SettingHelper::getAll()['qr_prefix'];
            for ($i = 0; $i < 10; $i++) {
                QrCode::create([
                    'code' => SettingHelper::qrCodeBuilder($qr_prefix . Str::lower(Str::random($qr_length - strlen($qr_prefix)))),
                    'created_by' => 1,
                    'updated_by' => 1
                ]);
            }
            $this->command->alert('10 QR Codes has been created');

            for ($i = 1; $i <= 10; $i++) {
                Restaurant::create([
                    'qraved_resto_mapping_id' => 0,
                    'name' => 'Resto ' . $i,
                    'alias' => 'resto' . $i,
                    'address' => 'Address ' . $i,
                    'contact' => '12345',
                    'qr_id' => $i,
                    'created_by' => 1,
                    'updated_by' => 1
                ]);
            }
            $this->command->alert('10 restaurants has been created');
        });
    }
}
