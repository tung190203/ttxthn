<?php

namespace App\Http\Controllers;

use App\Models\InvestmentGuide;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class InvestMentGuideController extends Controller
{
    public function detail(Request $request, $slug, $id)
    {
        $investment_guide = InvestmentGuide::
            where('published_at' , '<=', Carbon::now())
            ->where('language', App::getLocale())
            ->where('id', $id)->firstOrFail();

        $category = Category::where('id', data_get($investment_guide, 'cat_id'))->first();
        $investment_guide->increment('view_num');

        //SEO MOZ
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = 'cam-nang-dau-tu';
        $setting['meta_title'] = ($investment_guide->meta_title) ?: $investment_guide->name;
        $setting['meta_keywords'] = ($investment_guide->meta_keywords) ?: $setting['meta_keywords'];
        $setting['meta_description'] = ($investment_guide->meta_description) ?: $setting['meta_description'];
        $setting['og_image'] = ($investment_guide->image) ?: ($setting['og_image'] ?? '');
        $list_investment_guide_popular = InvestmentGuide::with('interests')->where('status', InvestmentGuide::STATUS_ACTIVE)
            ->whereNull('parent_id')
            ->where('published_at', '<=', Carbon::now())
            ->where('language', App::getLocale())
            ->where('id', '<>', $investment_guide->id)
            ->orderBy('published_at', 'desc')
            ->take(InvestmentGuide::INVESTMENT_TAKE)
            ->get()
            ->transform(function ($item) {
                $item->is_interested = $item->interests()
                    ->where('guest_id', Auth::guard('guest')->id())
                    ->exists();
                return $item;
            });
        $backUrl = url()->previous();
        $backLabel = $request->get('ref');

        return view('frontend.home.investment_guide_detail',
            compact(
                'setting',
                'investment_guide',
                'category',
                'list_investment_guide_popular'
                ,'backUrl'
                ,'backLabel'
            )
        );
    }
}
