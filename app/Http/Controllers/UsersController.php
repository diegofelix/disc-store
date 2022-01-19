<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\RegisterUser;
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
}
