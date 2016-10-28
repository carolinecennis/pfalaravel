<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        App\Models\User::unguard();

        // $this->call(UsersTableSeeder::class);

        $this->call(BibelotSeeder::class);

    }
}

class BibelotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //  factory(App\Models\User::class, 3)->create();
        factory(App\Models\UserBio::class, 20)->create();
        factory(App\Models\Listing::class, 20)->create();
        factory(App\Models\Image::class, 20)->create();

    }

}
