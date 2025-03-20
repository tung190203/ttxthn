<?php

namespace App\Http\Controllers\Backend;

use App\Libs\DataGrid;
use App\Libs\Util;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Category;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    private Coupon $coupon;

    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
        $this->selectedMainMenu = 'coupon';

        parent::__construct();

        if (!Gate::allows('coupon')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    public function index(Request $request)
    {
        $language = App::getLocale();
        $this->selectedSubMenu('coupon');
        $category = new Category();
        $category->getParentArray();

        $filter['name'] = $request->get('name', '');
        $filter['store_id'] = $request->get('store_id', 0);
        $filter['status'] = $request->get('status', -1);
        $query = $this->coupon->with(['category', 'user'])
            ->where('language', $language)
            ->orderBy('id', 'desc');
        if ($filter['name'] !== '') {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }
        if ($filter['store_id'] > 0) {
            $query->where('store_id', $filter['store_id']);
        }
        if ($filter['status'] > -1) {
            if ($filter['status'] == 2) {
                $query->onlyTrashed();
            } else {
                $query->where('status', $filter['status']);
            }
        }

        $coupons = $query->paginate(20);
        $options['stores'] = Store::makeListStore($filter['store_id']);
        $options['status'] = Util::makeHTMLOptions(Coupon::STATUS_ARRAY, $filter['status']);

        $paginate = 20;
        $route_name = 'backend_coupon_edit';
        $option_column_button = Coupon::makeOptionColumnButton();

        $clsDataGrid = new DataGrid();
        $clsDataGrid->setLinkEdit($route_name);
        $clsDataGrid->addColumnLabel("name", "Name", "width='20%' nowrap");
        $clsDataGrid->addColumnLabel("value", "Value", "width='20%' nowrap");
        $clsDataGrid->addColumnSelect("status", "Hiển thị", "width='15%'", ["Không", "Có"]);
        $clsDataGrid->addColumnText("priority", "STT", "width='10%'");
        $clsDataGrid->addColumnDate("created_at", "Ngày tạo", "width='15%' nowrap ", 'd-m-Y');
        $clsDataGrid->addColumnButton('id', '&nbsp', $option_column_button, "width='5%' nowrap ");

        $dataGrid = $clsDataGrid->showDataGrid($coupons, $paginate, $coupons->total());

        return view('backend.coupon.index', compact('coupons', 'filter', 'options', 'dataGrid'));
    }

    public function saveDataIndex(Request $request)
    {
        if (!Gate::allows('coupon/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $update = $request->get('update', []);
        foreach ($update as $key => $value) {
            Coupon::where('id', $key)->update($value);
        }
        return redirect()->route('backend_coupon')->with('success', 'Cập nhật thông tin thành công');
    }

    public function edit(Coupon $coupon)
    {
        if (!Gate::allows('coupon/' . ($coupon->exists ? 'edit' : 'add'))) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $option_stores = Store::makeListStore($coupon->store_id);
        $option_types = Coupon::makeListType($coupon->type);
        return view('backend.coupon.create', compact('coupon', 'option_stores', 'option_types'));
    }

    public function save(Coupon $coupon, Request $request)
    {
        if (!Gate::allows('coupon/' . ($coupon->exists ? 'edit' : 'add'))) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate([
            'name' => 'required|string',
            'value' => 'required|string',
            'description' => 'required|string',
        ]);

        $language = App::getLocale();
        $name = strip_tags($request->get('name'));
        $slug = strip_tags($request->get('slug'));
        $coupon->name = $name;
        $coupon->slug = $slug ?: Str::slug($name);
        $coupon->store_id = intval($request->get('store_id'));
        $coupon->value = $request->get('value');
        $coupon->type = $request->get('type');
        $coupon->image = strip_tags($request->get('image'));
        $coupon->code = strip_tags($request->get('code'));
        $coupon->description = $request->get('description');
        $coupon->status = intval($request->get('status'));
        $coupon->is_verified = intval($request->get('is_verified'));
        $coupon->is_exclusive = intval($request->get('is_exclusive'));
        $coupon->is_exclusive = intval($request->get('is_exclusive'));
        $coupon->is_featured = intval($request->get('is_featured'));

        if (!$coupon->exists) {
            $coupon->language = $language;
        }

        try {
            $coupon->save();
        } catch (\Exception $ex) {
            return back()->withInput()->withErrors(['slug' => $ex->getMessage()]);
        }

        return redirect()->route('backend_coupon_edit', $coupon)->with('success', 'Cập nhật thành công');
    }

    public function clone(Coupon $coupon)
    {
        if (!Gate::allows('coupon/clone')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $new_id = data_get($coupon, 'id', 0);
        $coupon = Coupon::find($new_id);
        if ($coupon) {
            $new_coupon = $coupon->replicate();
            $new_coupon->name = $coupon->name . ' copy';
            $new_coupon->slug = $coupon->slug . '-' . strtolower(Str::random(5));
            if ($new_coupon->save()) {
                return back()->with('success', 'Sao chép thành công');
            }
        }
        return back()->with('error', 'Sao chép không thành công');
    }

    public function delete(Request $request, $id)
    {
        if (!Gate::allows('coupon/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $this->coupon->destroy($id);
        return redirect()->to(route('backend_coupon'))->with('success', 'Xóa thành công');
    }

    public function bulkDelete(Request $request)
    {
        if (!Gate::allows('coupon/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate(['ids' => 'required|array']);

        $ids = $request->get('ids');
        if (empty($ids)) {
            return $this->responseJsonBadRequest();
        }

        $this->coupon->destroy($ids);
        return $this->responseJsonOk();
    }

    public function restore(Request $request, $id)
    {
        if (!Gate::allows('coupon/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->restore();
        return redirect()->route('backend_coupon')->with('success', 'Khôi phục bài viết thành công');
    }

    public function forceDelete(Request $request, $id)
    {
        if (!Gate::allows('coupon/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->forceDelete();
        return redirect()->route('backend_coupon', 'status=2')->with('success', 'Xóa bài viết thành công');
    }
}

