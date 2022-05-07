<?php

namespace Tests\Unit;

use App\Models\Equipment;
use App\Models\RentingValue;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    public function test_get_renting_value()
    {
        $equipment = new Equipment();

        $equipment->values->push(
            new RentingValue(['period_id' => 1, 'value' => 0.5]),
            new RentingValue(['period_id' => 2, 'value' => 1.5]),
        );

        $this->assertEquals(0.5, $equipment->getRentingValue(1));
        $this->assertEquals(1.5, $equipment->getRentingValue(2));
        $this->assertEquals(null, $equipment->getRentingValue(3));
    }
}
