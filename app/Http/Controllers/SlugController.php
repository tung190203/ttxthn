<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Slug;
use Illuminate\Http\Request;

class SlugController extends Controller
{
    public function index(Request $request, $slug)
    {
        $locale = app()->getLocale();
        $availableLocales = config('app.available_locales', ['vi', 'en']);
    
        $category = Category::where('status', 1)
            ->where(function ($query) use ($slug, $locale, $availableLocales) {
                $query->where("slug->{$locale}", $slug);
                foreach ($availableLocales as $loc) {
                    if ($loc !== $locale) {
                        $query->orWhere("slug->{$loc}", $slug);
                    }
                }
            })
            ->firstOrFail();

        $type = $category->type;

        switch ($type) {
            case Category::CATEGORY_TYPE_POST:
                $controller = new PostController();
                return $controller->index($request, $category);
            case Category::CATEGORY_TYPE_INVESTMENT_HANDBOOK:
                $controller = new HomeController();
                return $controller->introducePotential($request);
    
            default:
                return view('errors.404');
        }
    }    

    public function slug(Request $request, $slug)
    {
        $slug_model = Slug::where('slug', $slug)->firstOrFail();

        switch ($slug_model->module) {
            case Slug::MODULE['CATEGORY']:
                $category = Category::where('status', 1)->where('slug', $slug)->firstOrFail();

                $type = $category->type;

                switch ($type) {
                    case Category::CATEGORY_TYPE_POST:
                        $controller = new PostController();
                        return $controller->index($request, $category);
                    default :
                        return view('errors.404');
                }
            case Slug::MODULE['POST']:
                $controller = new PostController();
                return $controller->detail($request, $slug_model->slug, $slug_model->module_id);
            case Slug::MODULE['PAGE']:
                $controller = new HomeController();
                return $controller->page($request, $slug_model->slug);
            default :
                return view('errors.404');
        }
    }
}
