<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestigatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'orcid' => $this->orcid,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'principal_email' => $this->principal_email,
            'keywords' => KeywordResource::collection($this->keywords),
        ];
    }
}
