<?php

namespace App\Http\Resources;

use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuCateringDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    use IconComponent,FormatParse;
    public function toArray(Request $request): array
    {
        $ingredients=[];
        if($this->menuingredients){
            foreach($this->menuingredients as $item){
                $ingredients[]=[
                    "id"=>$item->id,
                    "refid"=>$item->ingredient_id,
                    "name"=>$item->ingredient_id?$item->ingredient->name:$item->ingredient_label,
                    "type"=> $item->ingredient_id?'item':'label',
                    "quantity"=>self::parseQuantity($item->quantity,2),
                ];
                // {id:crypto.randomUUID(),refid:selectIngredientsUpdate.id,name:selectIngredientsUpdate.text,type:'item',quantity:0};
                // {id:crypto.randomUUID(),refid:null,name:e.val(),type:'label',quantity:0};
            }
        }
        $packet_id = collect($this->packet)->map(function ($item) {
            return [
                'id' => $item['packet_catering_id'] ?? null,
                'name' => $item['packet_name']['name'] ?? null,
            ];
        })->values()->toArray();
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "desc"=> $this->desc,
            "selling_price"=>self::numberformat($this->selling_price),
            "category_id"=>[
                'id'=>$this->category->id,
                'name'=>$this->category->name
            ],
            'packet_id'=>$packet_id,
            "porsi_standard"=>$this->porsi_standard,
            "menuingredients"=> $ingredients,
            "is_active"=> $this->is_active,
        ];
    }
}
