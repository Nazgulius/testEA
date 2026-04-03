<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Income;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DataController extends Controller
{
    protected $baseUrl = 'http://109.73.206.144:6969';
    protected $token = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function fetchAllData()
    {
        try {
            $this->fetchSales();
            $this->fetchOrders();
            $this->fetchStocks();
            $this->fetchIncomes();

            return response()->json([
                'success' => true,
                'message' => 'Все данные успешно загружены в БД'
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка при загрузке данных: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при загрузке данных'
            ], 500);
        }
    }

    private function fetchSales()
    {
        $response = Http::get($this->baseUrl . '/api/sales', [
            'dateFrom' => '2026-03-04',
            'dateTo' => '2026-04-03',
            'page' => 1,
            'limit' => 500,
            'key' => $this->token
        ]);

        Log::info('Ответ API продаж:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            $sales = $response->json()['data'] ?? [];
            $processedCount = 0;
            $skippedCount = 0;

            foreach ($sales as $saleData) {
                if (!isset($saleData['g_number'])) {
                    Log::warning('Пропущена запись продаж — отсутствует g_number', [
                        'sale_id' => $saleData['sale_id'] ?? 'unknown'
                    ]);
                    $skippedCount++;
                    continue;
                }

                Sale::updateOrCreate(
                    ['g_number' => $saleData['g_number']],
                    $saleData
                );
                $processedCount++;
            }

            Log::info('Продажи загружены: ' . $processedCount . ' записей обработано, ' . $skippedCount . ' пропущено');
        } else {
            Log::error('Ошибка загрузки продаж: ' . $response->status() . ', ответ: ' . $response->body());
        }
    }

    private function fetchOrders()
    {
        $response = Http::get($this->baseUrl . '/api/orders', [
            'dateFrom' => '2026-03-04',
            'dateTo' => '2026-04-03',
            'page' => 1,
            'limit' => 500,
            'key' => $this->token
        ]);

        Log::info('Ответ API заказов:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            $orders = $response->json()['data'] ?? [];
            $processedCount = 0;
            $skippedCount = 0;

            foreach ($orders as $orderData) {
                if (!isset($orderData['g_number'])) {
                    Log::warning('Пропущена запись заказов — отсутствует g_number', [
                        'order_id' => $orderData['order_id'] ?? 'unknown'
                    ]);
                    $skippedCount++;
                    continue;
                }

                // Фильтруем поля: оставляем только те, что есть в API
                $filteredData = array_filter($orderData, function ($key) {
                    return in_array($key, [
                        'g_number', 'date', 'last_change_date', 'supplier_article',
                'tech_size', 'barcode', 'total_price', 'discount_percent',
                'warehouse_name', 'oblast', 'income_id', 'odid', 'nm_id',
                'subject', 'category', 'brand', 'is_cancel', 'cancel_dt'
                    ]);
                }, ARRAY_FILTER_USE_KEY);

                Order::updateOrCreate(
                    ['g_number' => $orderData['g_number']],
                    $filteredData
                );
                $processedCount++;
            }

            Log::info('Заказы загружены: ' . $processedCount . ' записей обработано, ' . $skippedCount . ' пропущено');
        } else {
            Log::error('Ошибка загрузки заказов: ' . $response->status() . ', ответ: ' . $response->body());
        }
    }

    private function fetchStocks()
{
    $response = Http::get($this->baseUrl . '/api/stocks', [
        'dateFrom' => now()->format('Y-m-d'), 
        'page' => 1,
        'limit' => 500,
        'key' => $this->token
    ]);

    Log::info('Ответ API складов:', [
        'status' => $response->status(),
        'body' => $response->body()
    ]);

    if ($response->successful()) {
        $stocks = $response->json()['data'] ?? [];
        $processedCount = 0;
        $skippedCount = 0;

        foreach ($stocks as $stockData) {
            // Проверяем обязательные поля для идентификации записи
            if (!isset($stockData['barcode']) || !isset($stockData['warehouse_name'])) {
                Log::warning('Пропущена запись складов — отсутствует barcode или warehouse_name', [
                    'barcode' => $stockData['barcode'] ?? 'unknown',
            'warehouse_name' => $stockData['warehouse_name'] ?? 'unknown'
                ]);
                $skippedCount++;
                continue;
            }

            // Фильтруем данные: оставляем только поля, определённые в модели Stock
            $filteredData = array_filter($stockData, function ($key) {
                return in_array($key, [
                    'date',
                    'last_change_date',
                    'supplier_article',
                    'tech_size',
                    'barcode',
                    'quantity',
                    'is_supply',
                    'is_realization',
                    'quantity_full',
                    'warehouse_name',
                    'in_way_to_client',
                    'in_way_from_client',
                    'nm_id',
                    'subject',
                    'category',
                    'brand',
                    'sc_code',
                    'price',
                    'discount'
                ]);
            }, ARRAY_FILTER_USE_KEY);

            Stock::updateOrCreate(
                [
                    'barcode' => $stockData['barcode'],
            'warehouse_name' => $stockData['warehouse_name']
                ],
                $filteredData
            );
            $processedCount++;
        }

        Log::info('Склады загружены: ' . $processedCount . ' записей обработано, ' . $skippedCount . ' пропущено');
    } else {
        Log::error('Ошибка загрузки складов: ' . $response->status() . ', ответ: ' . $response->body());
    }
}


    private function fetchIncomes()
    {
        $response = Http::get($this->baseUrl . '/api/incomes', [
            'dateFrom' => '2026-03-04',
            'dateTo' => '2026-04-03',
            'page' => 1,
            'limit' => 500,
            'key' => $this->token
        ]);

        Log::info('Ответ API доходов:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            $incomes = $response->json()['data'] ?? [];
            $processedCount = 0;
            $skippedCount = 0;

            foreach ($incomes as $incomeData) {
                if (!isset($incomeData['income_id'])) {
                    Log::warning('Пропущена запись доходов — отсутствует income_id', [
                        'income_id' => $incomeData['income_id'] ?? 'unknown'
                    ]);
                    $skippedCount++;
                    continue;
                }

                Income::updateOrCreate(
                    ['income_id' => $incomeData['income_id']],
                    $incomeData
                );
                $processedCount++;
            }

            Log::info('Доходы загружены: ' . $processedCount . ' записей обработано, ' . $skippedCount . ' пропущено');
        } else {
            Log::error('Ошибка загрузки доходов: ' . $response->status() . ', ответ: ' . $response->body());
        }
    }

}
