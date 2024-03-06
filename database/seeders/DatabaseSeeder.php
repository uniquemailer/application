<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Service;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables([
            'templates',
            'services',
            'contact_groups',
            'contacts',
            'email_audits',
            'api_audits',
            'users',
        ]);

        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        Template::factory(5)->create();
        ContactGroup::factory(10)->create();
        Contact::factory(100)->create();

        User::factory(3)->user()->create();
        User::factory()->admin()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        Service::factory(10)
            ->hasAttached(ContactGroup::factory(3)->create())
            ->create();


    }

    private function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        //Eloquent::unguard();
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
