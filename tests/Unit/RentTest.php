<?php

namespace Tests\Unit;

use App\Models\Equipment;
use App\Models\Rent;
use App\Models\RentingValue;
use App\Models\RentItem;
use Tests\TestCase;

class RentTest extends TestCase
{
    public function test_total_weight()
    {
        $rent = new Rent();

        $item = new RentItem(['qty' => 5]);
        $item->equipment = new Equipment(['weight' => 2]);

        $rent->items->push($item);

        $item = new RentItem(['qty' => 5]);
        $item->equipment = new Equipment(['weight' => 5]);

        $rent->items->push($item);

        $this->assertEquals(35, $rent->total_weight);
    }

    public function test_total_pieces()
    {
        $rent = new Rent();

        $rent->items->push(new RentItem(['qty' => 5]));
        $rent->items->push(new RentItem(['qty' => 10]));

        $this->assertEquals(15, $rent->total_pieces);
    }

    public function test_total_unit_value()
    {
        $rent = new Rent();

        $item = new RentItem(['qty' => 5]);
        $item->equipment = new Equipment(['unit_value' => 1000]);

        $rent->items->push($item);

        $item = new RentItem(['qty' => 2]);
        $item->equipment = new Equipment(['unit_value' => 2000]);

        $rent->items->push($item);

        $this->assertEquals(9000, $rent->total_unit_value);
    }

    public function test_total_rent_value()
    {
        $rent = new Rent(['period_id' => '1']);

        $item = new RentItem(['qty' => 5]);
        $item->equipment = new Equipment();
        $item->equipment->values->push(
            new RentingValue(['period_id' => 1, 'value' => 1.2])
        );
        $rent->items->push($item);
        $item->rent = $rent;

        $item = new RentItem(['qty' => 5]);
        $item->equipment = new Equipment();
        $item->equipment->values->push(
            new RentingValue(['period_id' => 1, 'value' => 0.2])
        );
        $rent->items->push($item);
        $item->rent = $rent;

        $this->assertEquals(7, $rent->total_rent_value);
    }

    public function test_total()
    {
        $rent = new Rent([
            'period_id' => '1',
            'discount' => 5,
            'delivery_value' => 50
        ]);

        $item = new RentItem(['qty' => 5]);
        $item->equipment = new Equipment();
        $item->equipment->values->push(
            new RentingValue(['period_id' => 1, 'value' => 1.2])
        );

        $item->rent = $rent;
        $rent->items->push($item);

        $item = new RentItem(['qty' => 5]);
        $item->equipment = new Equipment();
        $item->equipment->values->push(
            new RentingValue(['period_id' => 1, 'value' => 0.2])
        );

        $item->rent = $rent;
        $rent->items->push($item);

        $this->assertEquals(52, $rent->total);
    }

    public function test_change()
    {
        $rent = new Rent(['paid_value' => 10, 'bill' => 50]);
        $this->assertEquals(40, $rent->change);
    }

    public function test_remaining()
    {
        $rent = new Rent(['period_id' => '1', 'paid_value' => 10]);

        $item = new RentItem(['qty' => 5]);
        $item->equipment = new Equipment();
        $item->equipment->values->push(
            new RentingValue(['period_id' => 1, 'value' => 12])
        );

        $item->rent = $rent;
        $rent->items->push($item);

        $item = new RentItem(['qty' => 5]);
        $item->equipment = new Equipment();
        $item->equipment->values->push(
            new RentingValue(['period_id' => 1, 'value' => 2])
        );

        $item->rent = $rent;
        $rent->items->push($item);

        $this->assertEquals(60, $rent->remaining);
    }
}
