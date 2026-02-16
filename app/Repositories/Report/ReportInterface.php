<?php

namespace App\Repositories\Report;

interface ReportInterface
{
    public function highest_sold_products(array $option = []);

    public function never_sold_products(array $option = []);

    public function monthly_sales(array $option = []);

}