<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\EventRegistration;

class EventRegistrationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventRegistration::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' =>  $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'avatar' => null,
            'session' => null,
        ];
    }
}
