<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Enums\UserType;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => UserType::toString($this->type),
            'lastName' => $this->lastName,
            'birthDate' => $this->birthDate,
            'birthPlace' => $this->birthPlace,
            'telephoneNumber' => $this->telephoneNumber,
            'fiscalCode' => $this->fiscalCode,
            'userRole' => $this->userRole,
            'ibanCode'=> $this->ibanCode,
            'bank' => $this->bank,
            'contractType' => $this->contractType,
	        'partitaIva' => $this->partitaIva,
            'profileImage' => isset($this->profileImage) ? Storage::disk('s3')->temporaryUrl($this->profileImage, now()->addMinutes(60)) : null,
            'email_verified_at' => $this->email_verified_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
