<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function PHPUnit\Framework\assertEquals;
use Tests\TestCase;


class IngredientModelTest extends TestCase
{
    public function testGetIngredientAvailableAmountInKilosWhenCallingAvailableAmountInKilsMethod()
    {
        $ingredient = Ingredient::create([
            "name" => "test_ingredient",
            "available_amount_in_grams" => 1000
        ]);

        assertEquals($ingredient->availableAmountInKilos(), 1);
    }
}
