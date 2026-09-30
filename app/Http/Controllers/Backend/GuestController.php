<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\InvestorApprovedMail;
use App\Mail\InvestorRejectedMail;
use App\Models\Guest;
use Illuminate\Http\Request;
use App\Libs\DataGrid;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GuestController extends Controller
{
    private Guest $guest;

    public function __construct(Guest $guest)
    {
        $this->guest = $guest;
        $this->selectedMainMenu = 'guest';

        parent::__construct();

        if (!Gate::allows('guest')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    private static array $investorTypeLabels = [
        'individual'   => 'Cá nhân',
        'organization' => 'Tổ chức',
        'fdi'          => 'FDI',
    ];

    private static array $statusLabels = [
        'pending'  => '<span class="badge badge-warning">Chờ xác thực</span>',
        'active'   => '<span class="badge badge-success">Hoạt động</span>',
        'locked'   => '<span class="badge badge-danger">Đã khóa</span>',
        'rejected' => '<span class="badge badge-secondary">Từ chối</span>',
    ];

    public function index(Request $request)
    {
        $this->selectedSubMenu('guest');

        $filter['name']          = $request->get('name', '');
        $filter['email']         = $request->get('email', '');
        $filter['phone']         = $request->get('phone', '');
        $filter['investor_type'] = $request->get('investor_type', '');
        $filter['status']        = $request->get('status', '');

        $query = $this->guest->with('nation')->orderBy('id', 'desc');

        if (!empty($filter['name'])) {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }
        if (!empty($filter['email'])) {
            $query->where('email', 'like', '%' . $filter['email'] . '%');
        }
        if (!empty($filter['phone'])) {
            $query->where('phone', 'like', '%' . $filter['phone'] . '%');
        }
        if (!empty($filter['investor_type'])) {
            $query->where('investor_type', $filter['investor_type']);
        }
        if (!empty($filter['status'])) {
            $query->where('status', $filter['status']);
        }

        $guests = $query->paginate(25);

        // Calculate Report summary
        $report = [
            'total' => Guest::count(),
            'active' => Guest::where('status', 'active')->count(),
            'locked' => Guest::where('status', 'locked')->count(),
            'pending' => Guest::where('status', 'pending')->count(),
            'new_this_month' => Guest::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        $clsDataGrid = new DataGrid();
        $clsDataGrid->setLinkEdit('backend_guest_edit');
        $clsDataGrid->addColumnImage("avatar_url", "Avatar", "width=50px height=50px");
        $clsDataGrid->addColumnLabel("name", "Name", "width='15%' nowrap");
        $clsDataGrid->addColumnLabel("email", "Email", "width='15%' nowrap");
        $clsDataGrid->addColumnLabel("phone", "Phone", "width='10%' nowrap");
        $clsDataGrid->addColumnLabel("investor_type", "Investor Type", "width='10%' nowrap", 1, '', function ($col, $val, $id, $row) {
            return self::$investorTypeLabels[$val] ?? $val;
        });
        $clsDataGrid->addColumnLabel("organization_name", "Organization", "width='15%' nowrap");
        $clsDataGrid->addColumnLabel("status", "Status", "width='10%' nowrap", 1, '', function ($col, $val, $id, $row) {
            return self::$statusLabels[$val] ?? $val;
        });
        $clsDataGrid->addColumnDate("created_at", "Created At", "width='10%' nowrap", 'd-m-Y');

        $option_column_button = [
            'show' => ['title' => 'Xem hồ sơ', 'icon' => 'fa fa-eye', 'class' => 'btn-info', 'route' => 'backend_guest_show'],
            'edit' => ['title' => 'Chỉnh sửa', 'icon' => 'fa fa-edit', 'class' => 'btn-primary', 'route' => 'backend_guest_edit'],
        ];
        $clsDataGrid->addColumnButton('id', '&nbsp', $option_column_button, "width='5%' nowrap");

        $dataGrid = $clsDataGrid->showDataGrid($guests);

        return view('backend.guest.index', compact('guests', 'filter', 'dataGrid', 'report'));
    }

    public function export(Request $request)
    {
        if (!Gate::allows('guest')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $filter = $request->only(['name', 'email', 'phone', 'investor_type', 'status']);
        $fileName = 'danh_sach_nha_dau_tu_' . now()->format('Ymd_Hi') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\GuestsExport($filter), $fileName);
    }

    public function saveDataIndex(Request $request)
    {
        $update = $request->get('update', []);
        foreach ($update as $key => $value) {
            $this->guest->where('id', $key)->update($value);
        }
        return redirect()->route('backend_guest')->with('success', 'Updated successfully');
    }

    public function show(Request $request, Guest $guest)
    {
        if (!Gate::allows('guest/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        
        $guest->load(['nation']);
        $bookmarks = \App\Models\Interest::with('interestable')->where('guest_id', $guest->id)->orderBy('created_at', 'desc')->take(20)->get();
        
        $auditLogs = \App\Models\AuditLog::where('model_type', Guest::class)
            ->where('model_id', $guest->id)
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        return view('backend.guest.show', compact('guest', 'bookmarks', 'auditLogs'));
    }

    public function edit(Request $request, Guest $guest)
    {
        if ($guest->exists && !Gate::allows('guest/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        if (!$guest->exists && !Gate::allows('guest/add')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $nations    = \App\Models\Nation::all();
        $industries = \App\Models\ProjectIndustries::all();
        return view('backend.guest.create', compact('guest', 'nations', 'industries'));
    }

    public function save(Guest $guest, Request $request)
    {
        if ($guest->exists && !Gate::allows('guest/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        if (!$guest->exists && !Gate::allows('guest/add')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $rules = [
            'name'                  => 'required|string|max:255',
            'phone'                 => 'nullable|string|max:20',
            'email'                 => 'required|email|unique:guests,email,' . ($guest->id ?? 'NULL'),
            'address'               => 'nullable|string',
            'identification_number' => 'nullable|string|max:50',
            'nation_id'             => 'nullable|exists:nations,id',
            'investor_type'         => 'nullable|in:individual,organization,fdi',
            'organization_name'     => 'nullable|string|max:255',
            'tax_code'              => 'nullable|string|max:50',
            'status'                => 'nullable|in:pending,active,locked,rejected',
            'password'              => $guest->exists ? 'nullable|min:6' : 'required|min:6',
        ];

        $validated = $request->validate($rules);

        $data = $request->only([
            'name', 'avatar', 'phone', 'email', 'address',
            'identification_number', 'nation_id', 'investor_type',
            'organization_name', 'tax_code', 'status',
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $guest->fill($data);
        $guest->save();

        return redirect()->route('backend_guest_edit', $guest->id)->with('success', 'Saved successfully');
    }

    public function delete(Request $request, $id)
    {
        if (!Gate::allows('guest/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $this->guest->destroy($id);
        return redirect()->route('backend_guest')->with('success', 'Deleted successfully');
    }

    public function bulkDelete(Request $request)
    {
        if (!Gate::allows('guest/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $ids = $request->get('ids');
        if (empty($ids)) {
            return $this->responseJsonBadRequest();
        }

        $this->guest->destroy($ids);
        return $this->responseJsonOk();
    }

    /**
     * Phê duyệt tài khoản Tổ chức / FDI (h-05)
     */
    public function approve(Request $request, Guest $guest)
    {
        if (!Gate::allows('guest/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $guest->update([
            'status'      => 'active',
            'locked_until' => null,
        ]);

        try {
            Mail::to($guest->email)->send(new InvestorApprovedMail($guest));
        } catch (\Exception $e) {
            Log::error('Gửi email phê duyệt NĐT thất bại: ' . $e->getMessage());
        }

        \App\Models\AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'approve',
            'model_type' => Guest::class,
            'model_id'   => $guest->id,
            'changes'    => ['status' => 'active'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Tài khoản đã được phê duyệt và thông báo gửi tới nhà đầu tư.']);
        }

        return redirect()->back()->with('success', 'Tài khoản nhà đầu tư đã được phê duyệt thành công.');
    }

    /**
     * Từ chối tài khoản Tổ chức / FDI (h-05)
     */
    public function reject(Request $request, Guest $guest)
    {
        if (!Gate::allows('guest/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $reason = $request->input('reason', '');

        $guest->update([
            'status' => 'rejected',
        ]);

        try {
            Mail::to($guest->email)->send(new InvestorRejectedMail($guest, $reason));
        } catch (\Exception $e) {
            Log::error('Gửi email từ chối NĐT thất bại: ' . $e->getMessage());
        }

        \App\Models\AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'reject',
            'model_type' => Guest::class,
            'model_id'   => $guest->id,
            'changes'    => ['status' => 'rejected', 'reason' => $reason],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Tài khoản đã bị từ chối và thông báo gửi tới nhà đầu tư.']);
        }

        return redirect()->back()->with('success', 'Đã từ chối tài khoản nhà đầu tư.');
    }

    /**
     * Khóa tài khoản (h-05)
     */
    public function lock(Request $request, Guest $guest)
    {
        if (!Gate::allows('guest/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate([
            'reason' => 'required|string|max:1000'
        ], [
            'reason.required' => 'Bắt buộc phải nhập lý do khóa tài khoản.'
        ]);

        $reason = $request->input('reason');

        $guest->update([
            'status'        => 'locked',
            'locked_until'  => now()->addDays(30), // Or permanent
            'locked_reason' => $reason
        ]);

        \App\Models\AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'lock',
            'model_type' => Guest::class,
            'model_id'   => $guest->id,
            'changes'    => ['status' => 'locked', 'reason' => $reason],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            Mail::to($guest->email)->send(new \App\Mail\InvestorLockedMail($guest, $reason));
        } catch (\Exception $e) {
            Log::error('Gửi email khóa NĐT thất bại: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Tài khoản đã bị khóa.']);
        }

        return redirect()->back()->with('success', 'Tài khoản nhà đầu tư đã bị khóa.');
    }

    /**
     * Mở khóa tài khoản (h-05)
     */
    public function unlock(Request $request, Guest $guest)
    {
        if (!Gate::allows('guest/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $guest->update([
            'status'                 => 'active',
            'locked_until'           => null,
            'locked_reason'          => null,
            'failed_login_attempts'  => 0,
        ]);

        \App\Models\AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'unlock',
            'model_type' => Guest::class,
            'model_id'   => $guest->id,
            'changes'    => ['status' => 'active'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Tài khoản đã được mở khóa.']);
        }

        return redirect()->back()->with('success', 'Tài khoản nhà đầu tư đã được mở khóa.');
    }

    /**
     * Gửi link đặt lại mật khẩu (h-05)
     */
    public function resetPassword(Request $request, Guest $guest)
    {
        if (!Gate::allows('guest/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $token = \Illuminate\Support\Facades\Password::broker('guests')->createToken($guest);
        $url = route('guest_password_reset', ['token' => $token, 'email' => $guest->email]);

        try {
            Mail::to($guest->email)->send(new \App\Mail\InvestorResetPasswordMail($guest, $url));
        } catch (\Exception $e) {
            Log::error('Gửi email reset password thất bại: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Không thể gửi email. Vui lòng kiểm tra cấu hình Mail.']);
        }

        \App\Models\AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'reset_password_requested',
            'model_type' => Guest::class,
            'model_id'   => $guest->id,
            'changes'    => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json(['success' => true, 'message' => 'Đã gửi liên kết đặt lại mật khẩu đến email của nhà đầu tư.']);
    }
}
