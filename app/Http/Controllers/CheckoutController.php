<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Volume;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Checkout');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.volume_id' => 'required|exists:volumes,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:card,paypal,simulated',
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($validated['items'] as $item) {
                $volume = Volume::lockForUpdate()->findOrFail($item['volume_id']);
                
                if ($volume->stock < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour le Tome {$volume->volume_number}.");
                }

                $unitPrice = $volume->price;
                $lineTotal = $unitPrice * $item['quantity'];
                $totalAmount += $lineTotal;

                // Decrement stock
                $volume->decrement('stock', $item['quantity']);

                $itemsToCreate[] = [
                    'volume_id' => $volume->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                ];
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'shipping_name' => $validated['shipping_name'],
                'shipping_email' => $validated['shipping_email'],
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
            ]);

            foreach ($itemsToCreate as $itemData) {
                $order->items()->create($itemData);
            }

            return $order;
        });

        return redirect()->route('orders.success', $order->id)
            ->with('success', 'Commande validée avec succès !');
    }

    public function success(int $id): Response
    {
        $order = Order::with(['items.volume.manga'])->findOrFail($id);

        return Inertia::render('OrderSuccess', [
            'order' => $order,
        ]);
    }
}
