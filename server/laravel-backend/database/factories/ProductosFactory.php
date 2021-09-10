<?php

namespace Database\Factories;

use App\Models\Productos;
use App\Models\Categorias;

use Illuminate\Database\Eloquent\Factories\Factory; 

class ProductosFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Productos::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre'      => $this->faker->name,
            'descripcion' => $this->faker->text,
            'cantidad'    => $this->faker->numberBetween($min = 1, $max = 200),
            'precio'      => $this->faker->randomNumber($nbDigits = NULL, $strict = false),
            'categoria'   => Categorias::all()->random()->id            
        ];
    }
}
