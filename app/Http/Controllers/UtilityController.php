<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class UtilityController extends Controller
{
    /**
     * Clear application cache
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        return '<h1>Cache Cleared Successfully</h1>';
    }

    /**
     * Optimize application
     */
    public function optimize()
    {
        Artisan::call('optimize');
        return '<h1>Reoptimized class loader</h1>';
    }

    /**
     * Cache routes
     */
    public function routeCache()
    {
        Artisan::call('route:cache');
        return '<h1>Routes cached</h1>';
    }

    /**
     * Clear route cache
     */
    public function routeClear()
    {
        Artisan::call('route:clear');
        return '<h1>Route cache cleared</h1>';
    }

    /**
     * Clear view cache
     */
    public function viewClear()
    {
        Artisan::call('view:clear');
        return '<h1>View cache cleared</h1>';
    }

    /**
     * Clear config cache
     */
    public function configClear()
    {
        Artisan::call('config:clear');
        return '<h1>Config cache cleared</h1>';
    }

    /**
     * Cache config
     */
    public function configCache()
    {
        Artisan::call('config:cache');
        return '<h1>Config cached successfully</h1>';
    }
}
