<?php

namespace Tests\Unit;

use App\Models\Equipment;
use App\Models\Rent;
use App\Models\RentingValue;
use App\Models\RentItem;
use Tests\TestCase;

class RentItemTest extends TestCase
{
    public function test_rent_value_subtotal()
    {
        $item = new RentItem(['qty' => '10']);
        $item->rent = new Rent(['period_id' => '1']);

        $item->equipment = new Equipment();
        $item->equipment->values[] = new RentingValue([
            'period_id' => '1',
            'value' => '0.4',
        ]);

        $this->assertEquals(4, $item->subtotal_rent_value);
    }

    public function test_unit_value_subtotal()
    {
        $item = new RentItem(['qty' => '10']);
        $item->equipment = new Equipment(['unit_value' => '1000.5']);

        $this->assertEquals(10005, $item->subtotal_unit_value);
    }
}
