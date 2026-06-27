<?php

namespace App\Http\Resources;

use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CostControlingResource extends JsonResource
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
        $detailcostestimation=false;
        // dd($this);
        if($this->customer){
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
        }
        if($this->costestimation){
            if($this->costestimation->detail){
                $detailcostestimation=$this->costestimation->detail
                        ->groupBy('kategori')
                        ->values()
                        ->map(function ($items) {
                            return [
                                'name' => $items->first()->kategori,
                                'item' => $items->values()
                            ];
                        })
                        ->keyBy(function ($item, $index) {
                            return $index + 1; 
                        });
            }
        };
        return [
            'id'=>$this->id,
            'customer_id'=>$this->customer_id,
            'order_ticket'=>$this->order_ticket,
            'estimate_price'=>self::numberformat($this->estimate_price),
            'delivery_date'=>Carbon::parse($this->delivery_date)->format('Y-m-d H:i'),
            'event_date'=>Carbon::parse($this->event_date)->format('Y-m-d H:i'),
            'total_guest'=>self::numberformat($this->total_guest),
            'total_invite'=>self::numberformat($this->total_invite),
            'desc'=>$this->desc,
            'event_type'=>$this->event_type?explode(',',$this->event_type):null,
            'package_type'=>$this->package_type?explode(',',$this->package_type):null,
            'venue'=>$this->venue,
            'dp'=>$this->dp,
            'status'=>$this->status,
            'rincianbiaya'=>$this->rincianbiaya,
            'customer'=>$this->customer?[
                'id'       => $this->customer->id,
                'name'     => $this->customer->name,
                'address'  => $this->customer->address,
                'phone'    => $this->customer->phone,
                'vilage_id' => $vilage,
                'city_id' => $city
            ]:null,
            'items' => $this->refItem->map(function ($item) {
                $ingredients=[];
                foreach($item->menu->menuingredients as $itemIngredients){
                    $ingredient=null;
                    if($itemIngredients->ingredient){
                        $ingredient=[
                            'id'=>$itemIngredients->ingredient->id,
                            'name'=>$itemIngredients->ingredient->name,
                            'unit'=>self::numberformat($itemIngredients->ingredient->unit),
                            'satuan'=>$itemIngredients->ingredient->satuan,
                            'default_price'=>self::numberformat($itemIngredients->ingredient->default_price),
                        ];
                    }
                    $ingredients[]=[
                        'id'=>$itemIngredients->id,
                        'menus_catering_id'=>$itemIngredients->menus_catering_id,
                        'ingredient_id'=>$itemIngredients->ingredient_id,
                        'ingredient_label'=>$itemIngredients->ingredient_label,
                        'quantity'=>self::parseQuantity($itemIngredients->quantity,2),
                        'ingredient'=>$ingredient
                    ];
                }
                return [
                    'id'       => $item->menu->id,
                    'name'     => $item->menu->name ?? $item->custom_menu,
                    'icon'     => self::getCategoryIcon($item->menu->category->name??null),
                    'porsi_request' => self::numberformat($item->quantity),
                    'porsi_standard' => self::numberformat($item->menu->porsi_standard),
                    'selling_price_ori'    => $item->menu->selling_price,
                    'selling_price'    =>(string) round($item->menu->selling_price/$item->menu->porsi_standard*$item->quantity,2),
                    'notes'     => $item->notes,
                    'ingredients'=>$ingredients
                ];
            }),
            'cost_estimation'=>$this->costestimation?[
                'id'=>$this->costestimation->id,
                'order_id'=>$this->costestimation->order_id,
                'cost_structure_id'=>$this->costestimation->cost_structure_id,
                'estimated_cost'=>self::numberformat($this->costestimation->estimated_cost),
                'estimated_selling_price'=>$this->costestimation->estimated_selling_price,
                'estimated_margin'=>self::numberformat($this->costestimation->estimated_margin),
                'detail'=>$detailcostestimation
            ]:null
        ];
    }
}
