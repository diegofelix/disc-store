<?php

namespace App\Models\User;

class Presenter
{
    public function presentSingleUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'fiscal_id' => $user->fiscal_id,
            'birthdate' => $user->birthdate->format('Y-m-d'),
            'phone' => $user->phone,
        ];
    }
}
