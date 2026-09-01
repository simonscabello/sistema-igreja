<?php

namespace App\Http\Requests;

class StoreWorshipSetRequest extends WorshipSetRequest
{
    public function rules(): array
    {
        return $this->worshipSetRules();
    }
}
