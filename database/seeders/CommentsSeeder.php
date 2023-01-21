<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommentsSeeder extends Seeder
{

    private $fields = ['general', 'name', 'phone', 'information', 'director', 'inn', 'address'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 50; $i++) { 
            DB::table('comments')->insert($this->generateComment());
        }
    }

    public function generateComment()
    {
        return [
            'user_id' => rand(1, 3),
            'company_id' => rand(1, 7),
            'field' => $this->fields[rand(0, 6)],
            'text' => Str::random(rand(10, 120)),
            'created_at' => date("Y-m-d H:i:s", (time() - rand(10000, 25000))),
            'updated_at' => date("Y-m-d H:i:s", (time() - rand(1000, 10000))),
        ];
    }
}
