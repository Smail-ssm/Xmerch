<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\Language;
use Illuminate\
    {
        Support\Facades\DB,
        Support\Collection,
        Support\ServiceProvider,
        Pagination\LengthAwarePaginator
    };

use App\Models\Font;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    protected function loadGeneralSettings()
    {
        $gs = DB::table('generalsettings')->first();

        if (!$gs) {
            $gs = (object) [];
        }

        if (!property_exists($gs, 'pod_designer_mode')) {
            $gs->pod_designer_mode = 0;
        }

        return $gs;
    }

    public function boot()
    {
        Paginator::useBootstrap();


        view()->composer('*',function($settings){

            $settings->with('gs', cache()->remember('generalsettings', now()->addDay(), function () {
                return $this->loadGeneralSettings();
            }));

            $settings->with('ps', cache()->remember('pagesettings', now()->addDay(), function () {
                return DB::table('pagesettings')->first();
            }));

            $settings->with('seo', cache()->remember('seotools', now()->addDay(), function () {
                return DB::table('seotools')->first();
            }));
            $settings->with('socialsetting', cache()->remember('socialsettings', now()->addDay(), function () {
                return DB::table('socialsettings')->first();
            }));

            $settings->with('default_font', cache()->remember('default_font', now()->addDay(), function () {
                return Font::whereIsDefault(1)->first();
            }));

            if (Session::has('currency'))
            {
                $currencyId = (int) Session::get('currency');
                $settings->with('curr',  cache()->remember('session_currency_'.$currencyId, now()->addDay(), function () use ($currencyId) {
                    return Currency::find($currencyId) ?: Currency::where('is_default','=',1)->first();
                }));
            }
            else
            {
                $settings->with('curr', cache()->remember('default_currency', now()->addDay(), function () {
                    return Currency::where('is_default','=',1)->first();
                }));
            }

             if (Session::has('language'))
            {
                $languageId = (int) Session::get('language');
                $settings->with('langg',  cache()->remember('session_language_'.$languageId, now()->addDay(), function () use ($languageId) {
                    return Language::find($languageId) ?: Language::where('is_default','=',1)->first();
                }));
            }
            else
            {
                $settings->with('langg', cache()->remember('default_language', now()->addDay(), function () {
                    return Language::where('is_default','=',1)->first();
                }));
            }
        });
    }

    public function register()
    {
        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
