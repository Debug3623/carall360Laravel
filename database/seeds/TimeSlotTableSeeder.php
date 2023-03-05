<?php

use App\TimeSlot;
use Illuminate\Database\Seeder;

class TimeSlotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sStartTime = "00:00";
        $sEndTime = "23:59";
        for($i = strtotime($sStartTime); $i<= strtotime($sEndTime); $i = $i + 15 * 60) {
            $tSlots = date("H:i", $i);  
            TimeSlot::create([
                'slot' => $tSlots,
            ]);
		}
    }
}
