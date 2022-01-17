<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GetDiscs;
use App\Models\Disc\Presenter;
use App\Models\Disc\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
