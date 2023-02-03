<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetCaisse extends JsonResource
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
        'admin' => $this->admin,
        'compta' => $this->compta,
        'stock' => $this->stock,
        'com' => $this->com,
        'paie' => $this->paie,
        'paie' => $this->paie,
        'immos' => $this->immos,
        'budget' => $this->budget,
        'rap' => $this->rap,
        'user_id' => $this->user->id,
        'userEmail' => $this->user->email,
        'caisses' => $this->caisse->raison_social,
      ];
    }
}
