<?php

namespace App\Http\Resources;

use App\Traits\FormatParse;
use App\Traits\IconComponent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuCateringSelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    use IconComponent,FormatParse;
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'category_name'  => $this->category_name,
            'icon'    => self::getCategoryIcon($this->category_name??null),
            'porsi_standard'=>self::numberformat($this->porsi_standard),
            'selling_price'=>self::numberformat($this->selling_price),
        ];
    }
}
