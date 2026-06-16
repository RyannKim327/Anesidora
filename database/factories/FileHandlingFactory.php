<?php

namespace Database\Factories;

use App\Models\FileHandling;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<FileHandling>
 */
class FileHandlingFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'file' => $this->faker->word() . '.pdf',
      'user_id' => User::factory(),
      'private_url' => Str::random(40),
      'public_url' => Str::random(10),
      'description' => null,
      'password' => null,
      'expiration' => now()->addDays(7),
    ];
  }
}
