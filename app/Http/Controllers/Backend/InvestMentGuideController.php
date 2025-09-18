<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Libs\DataGrid;
use App\Libs\Util;
use App\Models\Category;
use App\Models\InvestmentGuide;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use andreskrey\Readability\Readability;
use andreskrey\Readability\Configuration;
use \GuzzleHttp\Psr7\Uri;
use \GuzzleHttp\Psr7\UriResolver;
use Illuminate\Support\Facades\Log;

class InvestMentGuideController extends Controller
{
    private $investment_guide;
    public function __construct(InvestmentGuide $investment_guide)
    {
        $this->investment_guide = $investment_guide;
        $this->selectedMainMenu = 'investment_guide';
        parent::__construct();
        if (!Gate::allows('investment_guide')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    public function index(Request $request)
    {
        $language = App::getLocale();
        $this->selectedSubMenu('investment_guide');
        $category = new Category();
        $category->getParentArray();

        $filter['name'] = $request->get('name', '');
        $filter['cat_id'] = $request->get('cat_id', 0);
        $filter['status'] = $request->get('status', -1);
        $query = $this->investment_guide->with(['category', 'user'])
            ->where('language', $language)
            ->orderBy('id', 'desc');
        if ($filter['name'] !== '') {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }
        if ($filter['cat_id'] > 0) {
            $all_cat = $category->getAllCatStr($filter['cat_id']);
            $all_cat[] = (int) $filter['cat_id'];
            $query->whereIn('cat_id', $all_cat);
        }
        if ($filter['status'] > -1) {
            if ($filter['status'] == 2) {
                $query->onlyTrashed();
            } else {
                $query->where('status', $filter['status']);
            }
        }

        $investment_guides = $query->paginate(20);
        $options['categories'] = Category::makeListCategoryForInvestMent(0, '', $filter['cat_id']);
        $options['status'] = Util::makeHTMLOptions(InvestmentGuide::STATUS_ARRAY, $filter['status']);
        $option_categories = Category::makeArrayListCategory(0, Category::CATEGORY_TYPE_POST);

        $paginate = 20;
        $route_name = 'backend_investment_guide_edit';
        $option_column_button = InvestmentGuide::makeOptionColumnButton();

        $clsDataGrid = new DataGrid();
        $clsDataGrid->setLinkEdit($route_name);
        $clsDataGrid->addColumnLabel("name", "Name", "width='20%' nowrap");
        $clsDataGrid->addColumnImage("image", "Image", "", "width='10%' nowrap");
        $clsDataGrid->addColumnSelect("status", "Hiển thị", "width='15%'", ["Không", "Có"]);
        $clsDataGrid->addColumnText("priority", "STT", "width='10%'");
        $clsDataGrid->addColumnDate("created_at", "Ngày tạo", "width='15%' nowrap ", 'd-m-Y');
        $clsDataGrid->addColumnButton('id', '&nbsp', $option_column_button, "width='5%' nowrap ");

        $dataGrid = $clsDataGrid->showDataGrid($investment_guides, $paginate, $investment_guides->total());

        return view('backend.investment_guide.index', compact('investment_guides', 'filter', 'options', 'dataGrid'));
    }

    public function saveDataIndex(Request $request)
    {
        if (!Gate::allows('investment_guide/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $update = $request->get('update', []);
        foreach ($update as $key => $value) {
            InvestmentGuide::where('id', $key)->update($value);
        }
        return redirect()->route('backend_investment_guide')->with('success', 'Cập nhật thông tin thành công');
    }

    public function edit(InvestmentGuide $investment_guide)
    {
        if (!Gate::allows('investment_guide/' . ($investment_guide->exists ? 'edit' : 'add'))) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $option_categories = Category::makeListCategoryForInvestMent(0, '', $investment_guide->cat_id);
        $option_projects = Project::makeListProjectArray();
        return view('backend.investment_guide.create', compact('investment_guide', 'option_categories', 'option_projects'));
    }

    public function save(InvestmentGuide $investment_guide, Request $request)
    {
        if (!Gate::allows('investment_guide/' . ($investment_guide->exists ? 'edit' : 'add'))) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'slug' => 'nullable|alpha_dash|', //unique:investment_guides,slug,' . $investment_guide->id,
            'description' => 'required|string',
            'content' => 'required|string',
            'projects' => 'nullable|array',
            'project.*' => 'integer|exists:projects,id',
            'files_images' => 'nullable|array',
            'files_images.*' => 'nullable',
            'files_descs' => 'nullable|array',
            'files_descs.*' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        $fieldsToJsonEncode = ['files_descs'];
        foreach ($fieldsToJsonEncode as $field) {
            if (isset($validated[$field]) && is_array($validated[$field])) {
                $validated[$field] = json_encode(array_map('trim', $validated[$field]));
            }
        }

        $language = App::getLocale();
        $name = strip_tags($request->get('name'));
        $slug = strip_tags($request->get('slug'));
        $investment_guide->name = $name;
        $investment_guide->slug = $slug ?: Str::slug($name);
        $investment_guide->description = $request->get('description');
        $investment_guide->content = $request->get('content');
        $investment_guide->project_id = intval($request->get('project_id') ?? null);
        $investment_guide->image = strip_tags($request->get('image'));
        $investment_guide->cat_id = intval($request->get('cat_id'));
        $investment_guide->status = intval($request->get('status'));
        $investment_guide->is_hot = intval($request->get('is_hot'));
        $investment_guide->published_at = $request->get('published_at') ? date('Y-m-d H:i:s', strtotime($request->get('published_at'))) : null;
        if ($request->filled('files_images') && is_array($request->files_images)) {
            $investment_guide->files = implode(';', array_map('trim', $request->files_images));
        }
        $investment_guide->short_file_descs = $validated['files_descs'] ?? '';

        $investment_guide->meta_title = $request->get('meta_title');
        $investment_guide->meta_keywords = $request->get('meta_keywords');
        $investment_guide->meta_description = $request->get('meta_description');

        if (!$investment_guide->exists) {
            $investment_guide->language = $language;
        }

        try {
            $investment_guide->save();
            if ($request->filled('projects')) {
                $investment_guide->projects()->syncWithPivotValues(
                    $request->input('projects', []),
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        } catch (\Exception $ex) {
            return back()->withInput()->withErrors(['slug' => $ex->getMessage()]);
        }

        return redirect()->route('backend_investment_guide_edit', $investment_guide)->with('success', 'Cập nhật thành công');
    }

    public function clone(InvestmentGuide $investment_guide)
    {
        if (!Gate::allows('investment_guide/clone')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $new_id = data_get($investment_guide, 'id', 0);
        $investment_guide = InvestmentGuide::find($new_id);
        if ($investment_guide) {
            $new_investment_guide = $investment_guide->replicate();
            $new_investment_guide->name = $investment_guide->name . ' copy';
            $new_investment_guide->slug = $investment_guide->slug . '-' . strtolower(Str::random(5));
            if ($new_investment_guide->save()) {
                return back()->with('success', 'Sao chép thành công');
            }
        }
        return back()->with('error', 'Sao chép không thành công');
    }

    public function delete(Request $request, $id)
    {
        if (!Gate::allows('investment_guide/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $this->investment_guide->destroy($id);
        return redirect()->to(route('backend_investment_guide'))->with('success', 'Xóa thành công');
    }

    public function bulkDelete(Request $request)
    {
        if (!Gate::allows('investment_guide/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate(['ids' => 'required|array']);

        $ids = $request->get('ids');
        if (empty($ids)) {
            return $this->responseJsonBadRequest();
        }

        $this->investment_guide->destroy($ids);
        return $this->responseJsonOk();
    }

    public function restore(Request $request, $id)
    {
        if (!Gate::allows('investment_guide/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $investment_guide = InvestmentGuide::withTrashed()->findOrFail($id);
        $investment_guide->restore();
        return redirect()->route('backend_investment_guide')->with('success', 'Khôi phục bài viết thành công');
    }

    public function forceDelete(Request $request, $id)
    {
        if (!Gate::allows('investment_guide/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $investment_guide = InvestmentGuide::withTrashed()->findOrFail($id);
        $investment_guide->forceDelete();
        return redirect()->route('backend_investment_guide', 'status=2')->with('success', 'Xóa bài viết thành công');
    }

    public function showImportForm()
    {
        if (!Gate::allows('investment_guide/import')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $this->selectedSubMenu('investment_guide');
        $investment_guide = new InvestmentGuide();
        return view('backend.investment_guide.import', compact('investment_guide'));
    }

    public function importFromUrl(Request $request)
    {
        if (!Gate::allows('investment_guide/import')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $url = $request->input('url');
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ],
            'timeout' => 10,
            'allow_redirects' => true,
            'verify' => false,
        ]);

        $response = $client->get($url);
        $html = (string) $response->getBody();

        $crawler = new Crawler($html);

        // ---- Title ----
        $title = $crawler->filter('title')->count() ? $crawler->filter('title')->text() : null;

        // ---- Description ----
        $description = null;
        if ($crawler->filterXPath('//meta[@name="description"]')->count()) {
            $description = $crawler->filterXPath('//meta[@name="description"]')->attr('content');
        } elseif ($crawler->filterXPath('//meta[@property="og:description"]')->count()) {
            $description = $crawler->filterXPath('//meta[@property="og:description"]')->attr('content');
        }

        $keywords = null;
        if ($crawler->filterXPath('//meta[@name="keywords"]')->count()) {
            $keywords = $crawler->filterXPath('//meta[@name="keywords"]')->attr('content');
        }

        // ---- Nội dung chính ----
        $config = new Configuration();
        $readability = new Readability($config);
        $readability->parse($html);
        $content = $readability->getContent();
        $excerpt = $readability->getExcerpt();

        if (!$description && $excerpt) {
            $description = $excerpt;
        }

        // ---- Ảnh đại diện (thumbnail) ----
        // $image = null;
        // if ($crawler->filterXPath('//meta[@property="og:image"]')->count()) {
        //     $image = $crawler->filterXPath('//meta[@property="og:image"]')->attr('content');
        // } elseif ($crawler->filterXPath('//meta[@name="twitter:image"]')->count()) {
        //     $image = $crawler->filterXPath('//meta[@name="twitter:image"]')->attr('content');
        // }
        $image = null;
        if ($crawler->filterXPath('//meta[@property="og:image"]')->count()) {
            $image = $crawler->filterXPath('//meta[@property="og:image"]')->attr('content');
        } elseif ($crawler->filterXPath('//meta[@name="twitter:image"]')->count()) {
            $image = $crawler->filterXPath('//meta[@name="twitter:image"]')->attr('content');
        }

        if ($image) {
            try {
                // Tải ảnh từ URL
                $imageContents = file_get_contents($image);

                // Lấy đuôi file (nếu không có thì mặc định jpg)
                $ext = pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION);
                if (!$ext) {
                    $ext = 'jpg';
                }

                // Tạo tên file duy nhất
                $fileName = 'imported_' . time() . '_' . uniqid() . '.' . $ext;

                // Đường dẫn vật lý (trong public/uploads)
                $savePath = public_path('uploads/' . $fileName);

                // Đảm bảo thư mục tồn tại
                if (!file_exists(dirname($savePath))) {
                    mkdir(dirname($savePath), 0755, true);
                }

                // Ghi file
                file_put_contents($savePath, $imageContents);

                // Trả về path dạng /uploads/...
                $image = '/uploads/' . $fileName;

            } catch (\Exception $e) {
                Log::error("Import image failed: " . $e->getMessage());
            }
        }

        // ---- Fix ảnh trong content ----
        $contentCrawler = new Crawler($content);
        $contentCrawler->filter('img')->each(function (Crawler $node) use ($url) {
            $img = $node->getNode(0);
            if (!$img instanceof \DOMElement)
                return;

            $src = $img->getAttribute('src');
            if ($src) {
                // Nếu src là base64 → lấy data-src, data-original...
                if (str_starts_with($src, 'data:image')) {
                    $newSrc = $node->attr('data-src') ?: $node->attr('data-original') ?: $node->attr('data-lazy');
                    if ($newSrc) {
                        $img->setAttribute('src', $this->makeAbsoluteUrl($url, $newSrc));
                    }
                } else {
                    $img->setAttribute('src', $this->makeAbsoluteUrl($url, $src));
                }
            }

            // Xóa các giới hạn kích thước
            $img->removeAttribute('width');
            $img->removeAttribute('height');
            $img->removeAttribute('style');

            // Thêm style responsive cho an toàn
            $img->setAttribute('style', 'max-width:100%;height:auto;');
        });

        // Lấy lại content sau khi xử lý
        $content = $contentCrawler->html();

        $data = [
            'name' => $title,
            'slug' => $title ? Str::slug($title) : '',
            'description' => $description,
            'content' => $content,
            'meta_title' => $title,
            'meta_keywords' => $keywords,
            'meta_description' => $description,
            'image' => $image,
        ];

        return redirect()
            ->route('backend_investment_guide_create')
            ->withInput($data);
    }

    /**
     * Convert relative path → absolute URL
     */
    protected function makeAbsoluteUrl($baseUrl, $relativeUrl)
    {
        return (string) UriResolver::resolve(
            new Uri($baseUrl),
            new Uri($relativeUrl)
        );
    }
}
