<?php

namespace App\Http\Resources;

use App\Traits\FormatParse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    use FormatParse;
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'unit'  => self::numberformat($this->unit),
            'default_price'    => self::numberformat($this->default_price),
            'satuan' => $this->satuan,
        ];
    }
}
