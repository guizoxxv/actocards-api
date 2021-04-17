<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Player::create([
            'name' => 'player1',
            'games' => 10,
            'wins' => 8,
            'losses' => 2,
            'ties' => 0,
        ]);

        Player::create([
            'name' => 'player2',
            'games' => 5,
            'wins' => 3,
            'losses' => 1,
            'ties' => 1,
        ]);

        Player::create([
            'name' => 'player3',
            'games' => 10,
            'wins' => 3,
            'losses' => 5,
            'ties' => 2,
        ]);
    }
}
