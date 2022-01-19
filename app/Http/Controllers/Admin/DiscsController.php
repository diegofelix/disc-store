<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateDisc;
use App\Http\Requests\Admin\GetDiscs;
use App\Models\Disc\Presenter;
use App\Models\Disc\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class DiscsController extends Controller
{
    /**
     * @var Repository
     */
    private $discs;

    /**
     * @var Presenter
     */
    private $presenter;

    public function __construct(Repository $discs, Presenter $presenter)
    {
        $this->discs = $discs;
        $this->presenter = $presenter;
    }

    public function index(GetDiscs $request): JsonResponse
    {
        $filter = $request->validated();
        $discs = $this->discs->list($filter['filter'] ?? []);
        $data = $this->presenter->present($discs);

        return response()->json(compact('data'));
    }

    public function show(string $id): JsonResponse
    {
        if (!$disc = $this->discs->findById($id)) {
            return abort(Response::HTTP_NOT_FOUND);
        }

        return response()->json(
            $this->presenter->presentSingleDisc($disc)
        );
    }

    public function store(CreateDisc $request): JsonResponse
    {
        if (!$disc = $this->discs->create($request->validated())) {
            return response()->json([
                'error' => 'Error when creating a new Disc.',
            ]);
        }

        return response()->json(
            $this->presenter->presentSingleDisc($disc)
        );
    }
}
