<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $city=false;
        $vilage=false;
        if($this->vilage){
            $vilage=[
                'id'=>$this->vilage->id,
                'name'=>$this->vilage->name,
            ];
            if($this->vilage->district){
                $vilage['district_id']=[
                    'id'=>$this->vilage->district->id,
                    'name'=>$this->vilage->district->name,
                ];
                if($this->vilage->district->city){
                    $city=[
                        'id'=>$this->vilage->district->city->id,
                        'name'=>$this->vilage->district->city->name,
                    ];
                    if($this->vilage->district->city->province){
                        $city['province_id']=[
                            'id'=>$this->vilage->district->city->province->id,
                            'name'=>$this->vilage->district->city->province->name,
                        ];
                    }
                }
            }
        }
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'address'  => $this->address,
            'phone'    => $this->phone,
            'vilage_id' => $vilage,
            'city_id' => $city
        ];
    }
}
