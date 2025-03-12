<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $statuses = ['pending', 'in_progress', 'completed'];
    $priorities = ['low', 'medium', 'high'];

    return [
      'user_id' => User::factory(),
      'title' => fake()->sentence(),
      'description' => fake()->paragraph(),
      'status' => fake()->randomElement($statuses),
      'due_date' => fake()->dateTimeBetween('now', '+30 days'),
      'priority' => fake()->randomElement($priorities),
    ];
  }

  /**
   * Indicate that the task is pending.
   */
  public function pending(): static
  {
    return $this->state(fn(array $attributes) => [
      'status' => 'pending',
    ]);
  }

  /**
   * Indicate that the task is in progress.
   */
  public function inProgress(): static
  {
    return $this->state(fn(array $attributes) => [
      'status' => 'in_progress',
    ]);
  }

  /**
   * Indicate that the task is completed.
   */
  public function completed(): static
  {
    return $this->state(fn(array $attributes) => [
      'status' => 'completed',
    ]);
  }

  /**
   * Indicate that the task is high priority.
   */
  public function highPriority(): static
  {
    return $this->state(fn(array $attributes) => [
      'priority' => 'high',
    ]);
  }

  /**
   * Indicate that the task is due today.
   */
  public function dueToday(): static
  {
    return $this->state(fn(array $attributes) => [
      'due_date' => now()->toDateString(),
    ]);
  }

  /**
   * Indicate that the task is overdue.
   */
  public function overdue(): static
  {
    return $this->state(fn(array $attributes) => [
      'due_date' => fake()->dateTimeBetween('-30 days', '-1 day'),
      'status' => fake()->randomElement(['pending', 'in_progress']),
    ]);
  }
}
