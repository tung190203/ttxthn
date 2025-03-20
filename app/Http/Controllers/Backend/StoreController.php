<?php

namespace App\Http\Controllers\Backend;

use App\Libs\DataGrid;
use App\Libs\Util;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    private Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
        $this->selectedMainMenu = 'store';

        parent::__construct();

        if (!Gate::allows('store')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    public function index(Request $request)
    {
        $language = App::getLocale();
        $this->selectedSubMenu('store');
        $category = new Category();
        $category->getParentArray();

        $filter['name'] = $request->get('name', '');
        $filter['cat_id'] = $request->get('cat_id', 0);
        $filter['status'] = $request->get('status', -1);
        $query = $this->store->with(['category', 'user'])
            ->where('language', $language)
            ->orderBy('id', 'desc');
        if ($filter['name'] !== '') {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }
        if ($filter['cat_id'] > 0) {
            $all_cat = $category->getAllCatStr($filter['cat_id']);
            $all_cat[] = (int)$filter['cat_id'];
            $query->whereIn('cat_id', $all_cat);
        }
        if ($filter['status'] > -1) {
            if ($filter['status'] == 2) {
                $query->onlyTrashed();
            } else {
                $query->where('status', $filter['status']);
            }
        }

        $stores = $query->paginate(20);
        $options['categories'] = Category::makeListCategory(0, Category::CATEGORY_TYPE_COUPON, $filter['cat_id']);
        $options['status'] = Util::makeHTMLOptions(Store::STATUS_ARRAY, $filter['status']);
        $option_categories = Category::makeArrayListCategory(0, Category::CATEGORY_TYPE_COUPON);

        $paginate = 20;
        $route_name = 'backend_store_edit';
        $option_column_button = Store::makeOptionColumnButton();

        $clsDataGrid = new DataGrid();
        $clsDataGrid->setLinkEdit($route_name);
        $clsDataGrid->addColumnLabel("name", "Name", "width='20%' nowrap");
        $clsDataGrid->addColumnImage("image", "Image", "", "width='10%' nowrap");
        $clsDataGrid->addColumnSelect("status", "Hiển thị", "width='15%'", ["Không", "Có"]);
        $clsDataGrid->addColumnText("priority", "STT", "width='10%'");
        $clsDataGrid->addColumnDate("created_at", "Ngày tạo", "width='15%' nowrap ", 'd-m-Y');
        $clsDataGrid->addColumnButton('id', '&nbsp', $option_column_button, "width='5%' nowrap ");

        $dataGrid = $clsDataGrid->showDataGrid($stores, $paginate, $stores->total());

        return view('backend.store.index', compact('stores', 'filter', 'options', 'dataGrid'));
    }

    public function saveDataIndex(Request $request)
    {
        if (!Gate::allows('store/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $update = $request->get('update', []);
        foreach ($update as $key => $value) {
            Store::where('id', $key)->update($value);
        }
        return redirect()->route('backend_store')->with('success', 'Cập nhật thông tin thành công');
    }

    public function edit(Store $store)
    {
        if (!Gate::allows('store/' . ($store->exists ? 'edit' : 'add'))) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $option_categories = Category::makeListCategory(0, Category::CATEGORY_TYPE_COUPON, $store->cat_id);
        return view('backend.store.create', compact('store', 'option_categories'));
    }

    public function save(Store $store, Request $request)
    {
        if (!Gate::allows('store/' . ($store->exists ? 'edit' : 'add'))) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate([
            'name' => 'required|string',
            'slug' => 'nullable|alpha_dash|unique:stores,slug,' . $store->id,
            'content' => 'required|string',
        ]);

        $language = App::getLocale();
        $name = strip_tags($request->get('name'));
        $slug = strip_tags($request->get('slug'));
        $store->name = $name;
        $store->slug = $slug ?: Str::slug($name);
//        $store->description = $request->get('description');
        $store->content = $request->get('content');
        $store->image = strip_tags($request->get('image'));
        $store->cat_id = intval($request->get('cat_id'));
        $store->status = intval($request->get('status'));
        $store->is_hot = intval($request->get('is_hot'));

        $store->meta_title = $request->get('meta_title');
        $store->meta_keywords = $request->get('meta_keywords');
        $store->meta_description = $request->get('meta_description');

        if (!$store->exists) {
            $store->language = $language;
        }

        try {
            $store->save();
        } catch (\Exception $ex) {
            return back()->withInput()->withErrors(['slug' => $ex->getMessage()]);
        }

        return redirect()->route('backend_store_edit', $store)->with('success', 'Cập nhật thành công');
    }

    public function clone(Store $store)
    {
        if (!Gate::allows('store/clone')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $new_id = data_get($store, 'id', 0);
        $store = Store::find($new_id);
        if ($store) {
            $new_store = $store->replicate();
            $new_store->name = $store->name . ' copy';
            $new_store->slug = $store->slug . '-' . strtolower(Str::random(5));
            if ($new_store->save()) {
                return back()->with('success', 'Sao chép thành công');
            }
        }
        return back()->with('error', 'Sao chép không thành công');
    }

    public function delete(Request $request, $id)
    {
        if (!Gate::allows('store/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $this->store->destroy($id);
        return redirect()->to(route('backend_store'))->with('success', 'Xóa thành công');
    }

    public function bulkDelete(Request $request)
    {
        if (!Gate::allows('store/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate(['ids' => 'required|array']);

        $ids = $request->get('ids');
        if (empty($ids)) {
            return $this->responseJsonBadRequest();
        }

        $this->store->destroy($ids);
        return $this->responseJsonOk();
    }

    public function restore(Request $request, $id)
    {
        if (!Gate::allows('store/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $store = Store::withTrashed()->findOrFail($id);
        $store->restore();
        return redirect()->route('backend_store')->with('success', 'Khôi phục store thành công');
    }

    public function forceDelete(Request $request, $id)
    {
        if (!Gate::allows('store/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $store = Store::withTrashed()->findOrFail($id);
        $store->forceDelete();
        return redirect()->route('backend_store', 'status=2')->with('success', 'Xóa store thành công');
    }
}

