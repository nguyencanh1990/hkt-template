<?php

namespace App\Http\Requests;

class UserRequest extends BaseRequest
{
    /**
     * Define rules for store function
     *
     * @return array
     */
    public function rulesPost()
    {
        return [
            'name' => 'required',
            'email' => 'email|required|unique:users,email',
        ];
    }

    /**
     * Define rules for update function
     *
     * @return array
     */
    public function rulesPut()
    {
        return [
            'name' => 'required',
            'email' => 'required',
        ];
    }
}
