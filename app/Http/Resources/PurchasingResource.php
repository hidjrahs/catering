<?php

namespace App\Http\Resources;

use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchasingResource extends JsonResource
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
            'estimate_price'=>$this->estimate_price,
            'delivery_date'=>Carbon::parse($this->delivery_date)->format('Y-m-d H:i'),
            'event_date'=>Carbon::parse($this->event_date)->format('Y-m-d H:i'),
            'total_guest'=>self::numberformat($this->total_guest),
            'desc'=>$this->desc,
            'event_type'=>explode(',',$this->event_type),
            'package_type'=>explode(',',$this->package_type),
            'venue'=>$this->venue,
            'status'=>$this->status,
            'customer'=>[
                'id'       => $this->customer->id,
                'name'     => $this->customer->name,
                'address'  => $this->customer->address,
                'phone'    => $this->customer->phone,
                'vilage_id' => $vilage,
                'city_id' => $city
            ],
            'items' => $this->refItem->map(function ($item) {
                $ingredients=[];
                foreach($item->menu->menuingredients as $itemIngredients){
                    $ingredient=null;
                    if($itemIngredients->ingredient){
                        $mainSupplier=$itemIngredients->ingredient->ref_supplier->first();
                        if($mainSupplier){
                            $mainSupplier=$mainSupplier->supplier;
                        }
                        $ingredient=[
                            'id'=>$itemIngredients->ingredient->id,
                            'name'=>$itemIngredients->ingredient->name,
                            'unit'=>$itemIngredients->ingredient->unit,
                            'satuan'=>$itemIngredients->ingredient->satuan,
                            'default_price'=>$itemIngredients->ingredient->default_price,
                            'main_supplier'=>$mainSupplier,
                        ];
                    }
                    $ingredients[]=[
                        'id'=>$itemIngredients->id,
                        'menus_catering_id'=>$itemIngredients->menus_catering_id,
                        'ingredient_id'=>$itemIngredients->ingredient_id,
                        'ingredient_label'=>$itemIngredients->ingredient_label,
                        'quantity'=>$itemIngredients->quantity,
                        'ingredient'=>$ingredient
                    ];
                }
                return [
                    'id'       => $item->menu->id,
                    'name'     => $item->menu->name ?? $item->custom_menu,
                    'icon'     => self::getCategoryIcon($item->menu->category->name??null),
                    'porsi_request' => $item->quantity,
                    'porsi_standard' => $item->menu->porsi_standard,
                    'selling_price'    => $item->menu->selling_price,
                    'notes'    => $item->notes,
                    'ingredients'=>$ingredients
                ];
            }),
            'purchases'=>$this->purchases
        ];
    }
}
