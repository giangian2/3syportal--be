<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TalentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'surname' => $this->surname,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'mediaKit_src' => $this->mediaKit_src,
            'verticalities' => $this->verticalities,
            'social_infos' => $this->social_infos,
        ];
    }
}
