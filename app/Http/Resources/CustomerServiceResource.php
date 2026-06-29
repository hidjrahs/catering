<?php

namespace App\Http\Resources;

use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    use IconComponent,FormatParse;
    public function toArray(Request $request): array
    {
        $city=false;
        $vilage=false;
        // dd($this);
        if($this->customer->vilage){
            $vilage=[
                'id'=>$this->customer->vilage->id,
                'name'=>$this->customer->vilage->name,
            ];
            if($this->customer->vilage->district){
                $vilage['district_id']=[
                    'id'=>$this->customer->vilage->district->id,
                    'name'=>$this->customer->vilage->district->name,
                ];
                if($this->customer->vilage->district->city){
                    $city=[
                        'id'=>$this->customer->vilage->district->city->id,
                        'name'=>$this->customer->vilage->district->city->name,
                    ];
                    if($this->customer->vilage->district->city->province){
                        $city['province_id']=[
                            'id'=>$this->customer->vilage->district->city->province->id,
                            'name'=>$this->customer->vilage->district->city->province->name,
                        ];
                    }
                }
            }
        }
        return [
            'id'=>$this->id,
            'customer_id'=>$this->customer_id,
            'order_ticket'=>$this->order_ticket,
            'estimate_price'=>self::numberformat($this->estimate_price),
            'delivery_date'=>Carbon::parse($this->delivery_date)->format('Y-m-d H:i'),
            'event_date'=>Carbon::parse($this->event_date)->format('Y-m-d H:i'),
            'event_time'=>Carbon::parse($this->event_time)->format('H:i'),
            'total_guest'=>self::numberformat($this->total_guest),
            'total_invite'=>self::numberformat($this->total_invite),
            'desc'=>$this->desc,
            'desc_extra'=>$this->desc_extra,
            'event_type'=>explode(',',$this->event_type),
            'package_type'=>explode(',',$this->package_type),
            'venue'=>$this->venue,
            'dp'=>$this->dp,
            'customer'=>[
                'id'       => $this->customer->id,
                'name'     => $this->customer->name,
                'address'  => $this->customer->address,
                'phone'    => $this->customer->phone,
                'vilage_id' => $vilage,
                'city_id' => $city
            ],
            'items' => $this->refItem->map(function ($item) {
                $categoryName = $item->menu && $item->menu->category ? $item->menu->category->name : null;
                $isQuantity = $item->menu && $item->menu->category ? $item->menu->category->is_quantity : null;
                return [
                    'id'       => $item->menu->id ?? null,
                    'name'     => $item->menu->name ?? $item->custom_menu,
                    'icon'     => self::getCategoryIcon($categoryName),
                    'porsi_request' => self::numberformat($item->quantity),
                    'porsi_standard' => self::numberformat($item->menu->porsi_standard ?? null),
                    'selling_price'    => self::numberformat($item->menu->selling_price ?? null),
                    'is_quantity' => $isQuantity
                ];
            }),
            'rincianbiaya'=>$this->rincianbiaya
        ];
    }
}
