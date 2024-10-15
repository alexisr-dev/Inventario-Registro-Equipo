<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Actualizar o crear el primer usuario
        $user = User::where('email', 'test@example.com')->first();
        if ($user) {
            $user->update([
                'password' => Hash::make('admin10'),
            ]);
        } else {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('admin10'),
            ]);
        }

        // Crear tres nuevos usuarios
        User::factory()->create([
            'name' => 'Juan Castro',
            'email' => 'juancastro@gmail.com',
            'password' => Hash::make('castro123'),
        ]);

        User::factory()->create([
            'name' => 'Victor Zevallos',
            'email' => 'victorzevallos@gmail.com',
            'password' => Hash::make('cojo123'),
        ]);

        User::factory()->create([
            'name' => 'Jorge Mendieta',
            'email' => 'mendieta86@gmail.com',
            'password' => Hash::make('mendieta123'),
        ]);
    }
}
