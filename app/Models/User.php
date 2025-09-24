<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Gate;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    const ROOT_ADMIN_ID = 1;
    const PATTERN_PASSWORD = [
        'PATTERN' => '/^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>]).+$/',
        'MESSAGE' => 'Mật khẩu phải lớn hơn 8 ký tự và chứa ít nhất 1 chữ viết hoa, 1 ký tự đặc biệt'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }


    public function getGroupNameAttribute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'Super Admin';
        }

        return data_get($this, 'group.name', 'Guest');
    }

    public function isSuperAdmin(): bool
    {
        return $this->id === self::ROOT_ADMIN_ID || $this->is_super_admin;
    }

    /**
     * Kiểm tra quyền chung (không phụ thuộc record)
     */
    public function hasPermission(string $perm_key): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $permission_data = data_get($this, 'group.permission_data', []);

        return in_array($perm_key, $permission_data, true);
    }

    /**
     * Lấy scope theo module từ group.scope_data
     */
    public function getScope(string $perm_key): ?array
    {
        if ($this->isSuperAdmin()) {
            return null; // full
        }

        $scope_data = data_get($this, 'group.scope_data', []);

        // Tách resource từ key (vd: "project/edit" => "project")
        $resource = explode('/', $perm_key)[0] ?? null;
        if (!$resource) {
            return null;
        }

        return $scope_data[$resource] ?? null;
    }

    /**
     * Kiểm tra có thể thao tác trên record cụ thể
     */
    public function canDoOn(string $perm_key, ?string $record_id = null): bool
    {
        if (!$this->hasPermission($perm_key)) {
            return false;
        }

        $scope = $this->getScope($perm_key);

        // Nếu scope null => không giới hạn
        if ($scope === null) {
            return true;
        }

        // Quyền "add" thì luôn cho phép
        if (str_ends_with($perm_key, '/add')) {
            return true;
        }

        // Các action khác phải có record_id nằm trong scope
        if ($record_id === null) {
            return false;
        }

        return in_array((string) $record_id, $scope, true);
    }

    /**
     * Gợi ý các button action cho user
     */
    public static function makeOptionColumnButton(): array
    {
        $options = [];

        foreach (['edit', 'delete'] as $action) {
            if (Gate::allows('user/' . $action)) {
                $options[$action] = [
                    'route' => 'backend_user_' . $action,
                ];
            }
        }

        return $options;
    }
}
