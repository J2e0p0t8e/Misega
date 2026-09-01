<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Convert volumes table prices from EUR to FCFA (x656 rounded to nearest 5 FCFA)
        $volumes = DB::table('volumes')->get();
        foreach ($volumes as $volume) {
            $oldPrice = (float) $volume->price;
            // If the price was in EUR (< 100), convert it to FCFA
            if ($oldPrice < 100) {
                $newPrice = round(($oldPrice * 656) / 5) * 5;
                DB::table('volumes')->where('id', $volume->id)->update([
                    'price' => $newPrice,
                ]);
            }
        }

        // 2. Convert orders table total_amount from EUR to FCFA
        $orders = DB::table('orders')->get();
        foreach ($orders as $order) {
            $oldTotal = (float) $order->total_amount;
            if ($oldTotal < 500) {
                $newTotal = round(($oldTotal * 656) / 5) * 5;
                DB::table('orders')->where('id', $order->id)->update([
                    'total_amount' => $newTotal,
                ]);
            }
        }

        // 3. Convert order_items table unit_price from EUR to FCFA
        $orderItems = DB::table('order_items')->get();
        foreach ($orderItems as $item) {
            $oldUnitPrice = (float) $item->unit_price;
            if ($oldUnitPrice < 100) {
                $newUnitPrice = round(($oldUnitPrice * 656) / 5) * 5;
                DB::table('order_items')->where('id', $item->id)->update([
                    'unit_price' => $newUnitPrice,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back from FCFA to EUR
        $volumes = DB::table('volumes')->get();
        foreach ($volumes as $volume) {
            $fcfaPrice = (float) $volume->price;
            if ($fcfaPrice >= 100) {
                DB::table('volumes')->where('id', $volume->id)->update([
                    'price' => round($fcfaPrice / 656, 2),
                ]);
            }
        }

        $orders = DB::table('orders')->get();
        foreach ($orders as $order) {
            $fcfaTotal = (float) $order->total_amount;
            if ($fcfaTotal >= 500) {
                DB::table('orders')->where('id', $order->id)->update([
                    'total_amount' => round($fcfaTotal / 656, 2),
                ]);
            }
        }

        $orderItems = DB::table('order_items')->get();
        foreach ($orderItems as $item) {
            $fcfaPrice = (float) $item->unit_price;
            if ($fcfaPrice >= 100) {
                DB::table('order_items')->where('id', $item->id)->update([
                    'unit_price' => round($fcfaPrice / 656, 2),
                ]);
            }
        }
    }
};
