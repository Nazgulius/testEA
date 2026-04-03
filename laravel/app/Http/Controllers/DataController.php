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

        Log::info('Ответ API ордер:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            $orders = $response->json()['data'] ?? [];
            foreach ($orders as $orderData) {
                Order::updateOrCreate(
                    ['id' => $orderData['id']],
                    $orderData
                );
            }
            Log::info('Заказы загружены: ' . count($orders) . ' записей');
        } else {
            Log::error('Ошибка загрузки заказов: ' . $response->status() . ', ответ: ' . $response->body());
        }
    }

    private function fetchStocks()
    {
        $response = Http::get($this->baseUrl . '/api/stocks', [
            'dateFrom' => '2026-04-03', // текущий день в 2026 году
            'page' => 1,
            'limit' => 500,
            'key' => $this->token
        ]);

        Log::info('Ответ API сток:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            $stocks = $response->json()['data'] ?? [];
            foreach ($stocks as $stockData) {
                Stock::updateOrCreate(
                    ['id' => $stockData['id']],
                    $stockData
                );
            }
            Log::info('Склады загружены: ' . count($stocks) . ' записей');
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

        Log::info('Ответ API инком:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
            $incomes = $response->json()['data'] ?? [];
            foreach ($incomes as $incomeData) {
                Income::updateOrCreate(
                    ['id' => $incomeData['id']],
                    $incomeData
                );
            }
            Log::info('Доходы загружены: ' . count($incomes) . ' записей');
        } else {
            Log::error('Ошибка загрузки доходов: ' . $response->status() . ', ответ: ' . $response->body());
        }
    }
}
