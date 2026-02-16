<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Report\ReportInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmartReport extends Controller
{

    /**
     * @var ReportInterface
     */
    private $report;

    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
    }

    public function most_selling_products()
    {
        $most_sold = $this->report->highest_sold_products();
        return view('order.most_sold')->with(['most_sold' => $most_sold]);
    }

    public function never_sold_products()
    {
        $never_sold = $this->report->never_sold_products();
        //dd($never_sold);
        return view('order.never_sold')->with(['never_sold' => $never_sold]);
    }
}
