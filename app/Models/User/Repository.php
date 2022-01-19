<?php

namespace App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Repository
{
    public function create(array $attributes): ?User
    {
        // As the user is being created by API only.
        // We need to give him a temp password.
        $password = Str::random();
        $attributes = array_merge($attributes, compact('password'));

        $user = $this->getModel();
        $user->fill($attributes);

        return $user->save() ? $user : null;
    }

    public function findById(string $id): ?User
    {
        return $this->getModel()->find($id);
    }

    public function update(User $user, $attributes): User
    {
        $user->fill($attributes);
        $user->save();

        return $user;
    }

    public function destroy(User $user): bool
    {
        return $user->delete();
    }

    private function getModel(): User
    {
        return app(User::class);
    }
}
