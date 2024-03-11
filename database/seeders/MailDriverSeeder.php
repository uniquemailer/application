<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MailDriverSeeder extends Seeder
{   public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('mail_drivers')->truncate();
        DB::table('mail_drivers')->insert(
            [
                ['name' => 'SMTP', 'slug' => 'smtp']
            ],
            [
                ['name' => 'LOG', 'slug' => 'log']
            ]            
        );
        Schema::enableForeignKeyConstraints();
    }
}

