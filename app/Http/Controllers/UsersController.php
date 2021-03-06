<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\RegisterUser;
use App\Http\Requests\Admin\UpdateUser;
use App\Models\User\Presenter;
use App\Models\User\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    /**
     * @var Repository
     */
    private $users;

    /**
     * @var Presenter
     */
    private $presenter;

    public function __construct(Repository $users, Presenter $presenter)
    {
        $this->users = $users;
        $this->presenter = $presenter;
    }

    public function register(RegisterUser $request): JsonResponse
    {
        if (!$user = $this->users->create($request->validated())) {
            return response()->json([
                'error' => 'Error when registering a new User.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json(
            $this->presenter->presentSingleUser($user)
        );
    }

    public function update(string $id, UpdateUser $request): JsonResponse
    {
        if (!$user = $this->users->findById($id)) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        if (!$attributes = $request->validated()) {
            return response()->json([
                'error' => 'Nothing to update',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->users->update($user, $attributes);

        return response()->json(
            $this->presenter->presentSingleUser($user)
        );
    }

    public function cancel(string $id): JsonResponse
    {
        if (!$user = $this->users->findById($id)) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        if (!$this->users->destroy($user)) {
            return response()->json([
                'error' => 'Error when registering a new User.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
