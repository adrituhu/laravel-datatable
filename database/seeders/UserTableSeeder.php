<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Post::truncate();

        User::factory()->count(100)->create()->each(function(User $user){
            $user->posts()->saveMany(Post::factory()->count(2)->make());
        });
        
        Schema::enableForeignKeyConstraints();
        
    }
}
