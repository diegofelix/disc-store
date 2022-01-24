<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CreateOrder;
use App\Http\Requests\Admin\GetOrders;
use App\Jobs\ProcessOrder;
use App\Models\Order\InvalidCustomerException;
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
        try {
            $order = $this->orders->create($request->validated());
        } catch (InvalidCustomerException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        ProcessOrder::dispatch($order);

        return response()->json(
            $this->presenter->presentSingleOrder($order)
        );
    }
}
