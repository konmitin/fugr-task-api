<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedOptions();

        Task::factory()->count(120)->create();
    }

    public function seedOptions()
    {
        DB::table('status')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'title' => 'Не выполнена',
            'position' => 100,
            'type' => 'start',
        ]);

        DB::table('status')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'title' => 'Выполнена',
            'position' => 100,
            'type' => 'success',
        ]);

        DB::table('priority')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'title' => 'Низкий',
            'position' => 100,
        ]);

        DB::table('priority')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'title' => 'Средний',
            'position' => 200,
        ]);

        DB::table('priority')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'title' => 'Высокий',
            'position' => 300,
        ]);

        DB::table('category')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'title' => 'Работа',
        ]);

        DB::table('category')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'title' => 'Дом',
        ]);

        DB::table('category')->insert([
            'created_at' => now(),
            'updated_at' => now(),
            'title' => 'Личное',
        ]);
    }
}
