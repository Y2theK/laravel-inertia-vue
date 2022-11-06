<?php

namespace Database\Seeders;

use App\Models\Photo;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0;$i < 10;$i++) {
            $photo = new Photo();
            $photo->path = $faker->imageUrl();
            $photo->description = $faker->paragraph(2, true);
            $photo->save();
        }
    }
}
