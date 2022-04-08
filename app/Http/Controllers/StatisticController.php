<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Statistic;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Order\OrderRepositoryInterface;

class StatisticController extends Controller
{
    protected $orderRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepo
    ) {
        $this->orderRepo = $orderRepo;
    }

    public function statisticByOrder()
    {
        return view('admin.statistic.statistic_order');
    }

    public function statisticByRevenue()
    {
        return view('admin.statistic.statistic_revenue');
    }

    public function selectYearRevenue(Request $request)
    {
        if ($request->year) {
            $chart_data = [];
            //lấy kết quả trả về một mảng
            $get = $this->orderRepo->getRevenueMonth($request->year);
            $month = "";
            $revenue = "";
            $n = array_key_last($get);
            //kiểm tra tháng nào còn thiếu
            for ($i = 1; $i <= $n; $i++) {
                if (!array_key_exists($i, $get)) {
                    $get[$i] = 0;
                }
            }
            ksort($get);
            //Chuyển từ số sang tháng và nối chuỗi
            foreach ($get as $key => $val) {
                $monthName = date('F', mktime(0, 0, 0, $key, 10));
                $month .= '' . $monthName . ',';
                $revenue .= '' . $val . ',';
            }
            //Bỏ đi dấu phẩy ở cuối chuỗi
            $month = substr($month, 0, -1);
            $revenue = substr($revenue, 0, -1);
            $chart_data['month'] = $month;
            $chart_data['revenue'] = $revenue;

            return $chart_data;
        }
    }

    public function selectMonthOrder(Request $request)
    {
        $data = [];
        $totalOrder = "";
        $year = $request->year;
        $numberMonth = $request->month;
        $month = date("F", mktime(0, 0, 0, $numberMonth, 1));
        $nextNumberMonth = $request->month + 1;
        $nextMonth = date("F", mktime(0, 0, 0, $nextNumberMonth, 1));
        //Lấy ra các ngày thứ 2 trong tháng
        $firstDayOfWeek = new \DatePeriod(
            Carbon::parse("first monday of .$month. .$year."),
            CarbonInterval::week(),
            Carbon::parse("first monday of .$nextMonth. .$year.")
        );
        foreach ($firstDayOfWeek as $key => $val) {
            $data[$key] = $val . '';
        }
        for ($i = 0; $i < count($data); $i++) {
            if ($i == count($data) - 1) {
                $date = $data[$i];
                $date = strtotime($date);
                $date = strtotime("+7 day", $date);
                $date = date('Y-m-d 00:00:00', $date);
                $query = $this->orderRepo->getTotalOrdersWeekForMonth($data[$i], $date);
            } else {
                $query = $this->orderRepo->getTotalOrdersWeekForMonth($data[$i], $data[$i + 1]);
            }
            if (count($query) > 0) {
                $totalOrder .= '' . $query[0] . ',';
            } else {
                $totalOrder .= '' . count($query) . ',';
            }
        }
        //Bỏ đi dấu phẩy ở cuối chuỗi
        $totalOrder = substr($totalOrder, 0, -1);

        return $totalOrder;
    }
}
