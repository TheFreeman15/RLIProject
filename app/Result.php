<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    public function storeResult() {
        $result = Result::where([
            ['race_id', '=', $this->race_id],
            ['driver_id', '=', $this->driver_id]
        ])->first();

        if($result)
            return $result;
        else {
            $this->save();
            return $this;
        }
    }

    protected $fillable = [
        'constructor_id', 'driver_id', 'race_id', 'grid', 'points', 'fastestlaptime', 'position', 'tyres', 'stops', 'time'
    ];
}
