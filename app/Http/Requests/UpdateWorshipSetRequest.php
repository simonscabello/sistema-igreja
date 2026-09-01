<?php

namespace App\Http\Requests;

class UpdateWorshipSetRequest extends WorshipSetRequest
{
    public function rules(): array
    {
        return $this->worshipSetRules();
    }
}
