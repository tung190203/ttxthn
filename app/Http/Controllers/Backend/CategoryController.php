<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Libs\DataGrid;
use App\Libs\Util;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Group;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private Category $category;
    public array $category_types = Category::OPTIONS_CATEGORY;

    public function __construct(Category $category)
    {
        $this->category = $category;
        $this->selectedMainMenu = 'category';

        parent::__construct();

        if (!Gate::allows('category')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    public function index(Request $request)
    {
        $this->selectedSubMenu('category');

        $category_type = $request->get('type', '');
        if ($category_type !== '') {
            session(['category_type' => $category_type]);
        }
        $type = session('category_type', Category::CATEGORY_TYPE_POST);
        $filter['name'] = $request->get('name', '');
        $query = $this->category
        ->where('language', App::getLocale())
        ->visibleFor(auth('web')->user())
        ->orderBy('name');    
        if (!empty($filter['name'])) {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }

        $query->where('type', $type);

        $user = auth('web')->user();

        $scope = $user->getScope('category');
        if (!empty($scope)) {
            $query->whereIn('id', $scope);
        }

        $categories = $this->category->showCategories($query->get());
        $option_category_types = Util::makeHTMLOptions($this->category_types, $type);

        $option_column_button = Category::makeOptionColumnButton();

        $clsDataGrid = new DataGrid();
        $clsDataGrid->setLinkEdit('backend_category_edit');
        $clsDataGrid->addColumnLabel("name", "Name", "width='10%' nowrap", 1, '', function ($col, $val, $id, $row) {
            $html = $row->name;
    
            // Hiển thị nhãn trạng thái
            if ($row->is_draft) {
                $html .= " <span class='badge bg-warning'>Bản chỉnh sửa</span>";
            }
    
            // Hiển thị trạng thái duyệt
            if ($row->status_approve === 'pending') {
                if ($row->approval_level == 0) $html .= " <span class='badge bg-secondary'>Chờ duyệt cấp 1</span>";
                elseif ($row->approval_level == 1) $html .= " <span class='badge bg-primary'>Chờ duyệt cấp 2</span>";
            } elseif ($row->status_approve === 'approved') {
                $html .= " <span class='badge bg-success'>Đã duyệt</span>";
            } elseif ($row->status_approve === 'rejected') {
                $html .= " <span class='badge bg-danger'>Từ chối</span>";
            }
    
            return $html;
        });
        $clsDataGrid->addColumnSelect("status", "Hiển thị", "width='5%'", ["Không", "Có"]);
        $clsDataGrid->addColumnSelect("at_home", "Hiển thị trang chủ", "width='10%'", ["Không", "Có"]);
        $clsDataGrid->addColumnText("priority", "STT", "width='5%'");
        $clsDataGrid->addColumnDate("created_at", "Ngày tạo", "width='5%' nowrap ", 'd-m-Y');
        $clsDataGrid->addColumnButton('id', '&nbsp', $option_column_button, "width='5%' nowrap ");

        $dataGrid = $clsDataGrid->showDataGrid($categories);

        return view('backend.category.index',
            compact(
                'categories',
                'option_category_types',
                'filter',
                'dataGrid'
            )
        );
    }

    public function saveDataIndex(Request $request)
    {
        // foreach($request->ids as $id) {
        //     $p = Category::find($id);
        //     if(!Gate::allows('category/edit', $p)) {
        //         abort(403);
        //     }
        // }

        $update = $request->get('update', []);
        foreach ($update as $key => $value) {
            Category::where('id', $key)->update($value);
        }
        return redirect()->route('backend_category')->with('success', 'Cập nhật thông tin thành công');
    }

    public function edit(Request $request, Category $category)
    {
        if($category->exists && !Gate::allows('category/edit', $category)) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        if(!$category->exists && !Gate::allows('category/add')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $type = session('category_type', Category::CATEGORY_TYPE_POST);
        $list_category = Category::makeListCategory(0, $type, $category->parent_id, true);
        return view('backend.category.create', compact('category', 'list_category', 'type'));
    }

    public function save(Category $category, Request $request)
    {
        $user = auth('web')->user();
        if($category->exists && !Gate::allows('category/edit', $category)) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        if(!$category->exists && !Gate::allows('category/add')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|alpha_dash|unique:categories,slug,' . $category->id,
            'priority' => 'integer',
        ]);
        try {
            // 🟩 Tạo mới (bản chính)
            if (!$category->exists) {
                $category->fill($validated);
                $category->approval_level = $user->is_super_admin ? 2 : ($user->is_approve ? 1 : 0);
                $category->max_approval = 2;
                $category->is_draft = false;
                $category->status_approve = $user->is_super_admin ? 'approved' : ($user->is_approve ? 'pending' : 'pending');
                $category->status = $user->is_super_admin ? Category::STATUS_ACTIVE : Category::STATUS_INACTIVE;
                $category->language = App::getLocale();
                $parent_id = intval($request->get('parent_id', 0));
                if ($category->exists && $category->id == $parent_id) {
                    return back()->withInput()->withErrors(['parent_id' => 'Danh mục cha không thể là chính nó']);
                }
                $category->description = $request->get('description');
                $category->content = $request->get('content');
                $category->image = $request->get('image');
                $category->parent_id = $parent_id;
                $category->priority = intval($request->get('priority', 0));
                $category->status = intval($request->get('status', 0));
                $category->at_home = intval($request->get('at_home', 0));

                $category->meta_title = strip_tags($request->get('meta_title'));
                $category->meta_keywords = strip_tags($request->get('meta_keywords'));
                $category->meta_description = strip_tags($request->get('meta_description'));

                // Sinh slug unique
                $slug = Str::slug($category->slug ?: $category->name);
                $originalSlug = $slug;
                $counter = 1;
                while (Category::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }
                $category->slug = $slug;
                $category->type = session('category_type', Category::CATEGORY_TYPE_POST);;

                $category->save();

                if (Gate::allows('category/add')) {
                    $this->addCategoryToScope($user, $category->id);
                }
            } else {
                // 🟦 Super admin merge
                if ($user->is_super_admin) {
                    $mainCategory = $category->main_id ? Category::find($category->main_id) : $category;

                    $mainCategory->fill($validated);
                    $mainCategory->approval_level = $mainCategory->max_approval;
                    $mainCategory->status_approve = 'approved';
                    $mainCategory->is_draft = false;
                    $mainCategory->main_id = null;
                    $parent_id = intval($request->get('parent_id', 0));
                    if ($category->exists && $category->id == $parent_id) {
                        return back()->withInput()->withErrors(['parent_id' => 'Danh mục cha không thể là chính nó']);
                    }
                    $mainCategory->description = $request->get('description');
                    $mainCategory->content = $request->get('content');
                    $mainCategory->image = $request->get('image');
                    $mainCategory->parent_id = $parent_id;
                    $mainCategory->priority = intval($request->get('priority', 0));
                    $mainCategory->status = intval($request->get('status', 0));
                    $mainCategory->at_home = intval($request->get('at_home', 0));

                    $mainCategory->meta_title = strip_tags($request->get('meta_title'));
                    $mainCategory->meta_keywords = strip_tags($request->get('meta_keywords'));
                    $mainCategory->meta_description = strip_tags($request->get('meta_description'));

                    // Slug unique (remove -draft)
                    $slug = preg_replace('/-draft$/', '', Str::slug($mainCategory->slug ?: $mainCategory->name));
                    $originalSlug = $slug;
                    $counter = 1;
                    while (Category::where('slug', $slug)->where('id', '<>', $mainCategory->id)->exists()) {
                        $slug = $originalSlug . '-' . $counter++;
                    }
                    $mainCategory->slug = $slug;
                    $mainCategory->save();

                    // Xoá nháp
                    $drafts = Category::where('main_id', $mainCategory->id)->get();
                    foreach ($drafts as $draft) {
                        $this->removeCategoryFromScope($draft->id);
                        $draft->delete();
                    }

                    $category = $mainCategory;
                } else {
                    // 🟨 Người dùng thường
                    if ($category->status_approve === 'approved' && !$category->is_draft) {
                        // Bản chính đã duyệt → tạo bản nháp
                        $draft = $category->replicate();
                        $draft->fill($validated);
                        $draft->is_draft = true;
                        $draft->status_approve = 'pending';
                        $draft->approval_level = $user->is_approve ? 1 : 0;
                        $draft->main_id = $category->id;
                        $draft->status = Category::STATUS_INACTIVE;
                        $draft->slug = Str::slug($draft->slug ?: $draft->name) . '-draft';
                        $draft->save();

                        if (Gate::allows('category/add')) {
                            $this->addCategoryToScope($user, $draft->id);
                        }

                        $category = $draft;
                    } else {
                        // Cập nhật bản hiện tại (chưa duyệt hoặc nháp)
                        $category->fill($validated);
                        $category->status_approve = 'pending';
                        $category->approval_level = $user->is_approve ? 1 : 0;
                        $category->save();
                    }
                }
            }

            return redirect()
                ->route('backend_category_edit', $category)
                ->with('success', 'Lưu dữ liệu thành công ' . (
                    $user->is_super_admin ? '(Đã duyệt)' : ($user->is_approve ? '(Chờ duyệt cấp 2)' : '')
                ));
        } catch (\Exception $ex) {
            return back()->withInput()->withErrors(['slug' => $ex->getMessage()]);
        }
    }

    public function approve(Category $category)
    {
        $user = auth('web')->user();

        if (!($user->is_super_admin || $user->is_approve)) {
            abort(403, 'Bạn không có quyền duyệt dự án.');
        }
    
        if ($user->is_super_admin) {
            $category->approval_level = $category->max_approval;
            $category->status_approve = 'approved';
            $category->is_draft = false;
    
            if ($category->main_id) {
                $parent = Category::find($category->main_id);
                if ($parent) {
                    $draftData = $category->toArray();
                    $this->removeCategoryFromScope($category->id);
                    $category->delete();

                    $parent->fill($draftData);

                    $parent->main_id = null;
                    $parent->is_draft = false;
                    $parent->status_approve = 'approved';
                    $parent->approval_level = $parent->max_approval;

                    $slug = Str::slug($parent->name);
                    $originalSlug = $slug;
                    $counter = 1;
                    while (Category::where('slug', $slug)->where('id', '<>', $parent->id)->exists()) {
                        $slug = $originalSlug . '-' . $counter;
                        $counter++;
                    }
                    $parent->slug = $slug;

                    $parent->save();

                    $category = $parent;
                }
            }
        } elseif ($user->is_approve) {
            if ($category->approval_level < 1) {
                $category->approval_level = 1;
                $category->status_approve = 'pending';
            }
        }
    
        $category->save();

        return redirect()
            ->route('backend_category_edit', ['category' => $category->id])
            ->with('success', 'Duyệt danh mục thành công ' . ($user->is_super_admin ? '(Đã duyệt)' : '(Chờ duyệt cấp 2)'));
    }

    public function reject(Category $category)
    {
        $user = auth('web')->user();

        if (!($user->is_super_admin || $user->is_approve)) {
            abort(403, 'Bạn không có quyền từ chối duyệt danh mục.');
        }

        // $category->delete();
        $category->status_approve = 'rejected';
        $category->save();

        return redirect()
            ->route('backend_category')
            ->with('success', 'Từ chối duyệt danh mục thành công');
    }

    public function delete(Request $request, $id)
    {
        if (!Gate::allows('category/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $this->category->destroy($id);
        return redirect()->route('backend_category')->with('success', 'Xóa danh mục thành công');
    }

    public function bulkDelete(Request $request)
    {
        if (!Gate::allows('category/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate(['ids' => 'required|array']);

        $ids = $request->get('ids');
        if (empty($ids)) {
            return $this->responseJsonBadRequest();
        }

        $this->category->destroy($ids);
        return $this->responseJsonOk();
    }

    protected function addCategoryToScope($user, $categoryId)
    {
        $group = Group::find($user->group_id);
        if (!$group) return;

        $scopeData = $group->scope_data ?? [];
        $resource = 'category';

        if (empty($scopeData[$resource])) {
            return;
        }

        if (!in_array((string)$categoryId, $scopeData[$resource])) {
            $scopeData[$resource][] = (string)$categoryId;
            $group->scope_data = $scopeData;
            $group->save();
        }
    }

    protected function removeCategoryFromScope($categoryId)
    {
        $groups = Group::whereJsonContains('scope_data->category', (string)$categoryId)->get();

        foreach ($groups as $group) {
            $scopeData = $group->scope_data ?? [];
    
            if (!isset($scopeData['category']) || !is_array($scopeData['category'])) {
                continue;
            }

            if (empty($scopeData['category'])) {
                continue;
            }

            $scopeData['category'] = array_values(array_filter(
                $scopeData['category'],
                fn($id) => (string)$id !== (string)$categoryId
            ));
    
            $group->scope_data = $scopeData;
            $group->save();
        }
    }
}
