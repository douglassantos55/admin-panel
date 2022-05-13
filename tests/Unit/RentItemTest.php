<?php

namespace Tests\Unit;

use App\Models\Equipment;
use App\Models\PaymentCondition;
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

    public function test_multipliers()
    {
        $rent = new Rent(['period_id' => '1']);
        $rent->paymentCondition = new PaymentCondition(['increment' => 10]);

        $item = new RentItem(['qty' => '10']);
        $item->rent = $rent;

        $item->equipment = new Equipment();
        $item->equipment->values->push(new RentingValue([
            'period_id' => '1',
            'value' => '1.0',
        ]));

        $this->assertEquals(1.1, $item->rent_value);
        $this->assertEquals(11, $item->subtotal_rent_value);
    }
}
