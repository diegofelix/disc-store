<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CreateOrder;
use App\Http\Requests\Admin\GetOrders;
use App\Jobs\ProcessOrder;
use App\Models\Order\Presenter;
use App\Models\Order\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrdersController extends Controller
{
    /**
     * @var Repository
     */
    private $orders;

    /**
     * @var Presenter
     */
    private $presenter;

    public function __construct(Repository $orders, Presenter $presenter)
    {
        $this->orders = $orders;
        $this->presenter = $presenter;
    }

    public function index(GetOrders $request): JsonResponse
    {
        $filter = $request->validated();
        $orders = $this->orders->list($filter['filter'] ?? []);
        $data = $this->presenter->present($orders);

        return response()->json(compact('data'));
    }

    public function store(CreateOrder $request): JsonResponse
    {
        if (!$order = $this->orders->create($request->validated())) {
            return response()->json([
                'error' => 'Error when registering a new Order.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        ProcessOrder::dispatch($order);

        return response()->json(
            $this->presenter->presentSingleOrder($order)
        );
    }
}
