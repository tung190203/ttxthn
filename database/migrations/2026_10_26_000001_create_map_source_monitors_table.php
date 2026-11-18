<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Bảng lưu trữ lịch sử giám sát nguồn dữ liệu (NhatKyGiamSatNguon)
        Schema::create('map_source_monitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('map_layer_id');
            $table->enum('status', ['healthy', 'warning', 'error'])->default('healthy');
            $table->integer('response_time_ms')->default(0);
            $table->string('http_status_code', 10)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('checked_at')->useCurrent()->index();
            $table->timestamps();

            $table->foreign('map_layer_id')
                ->references('id')
                ->on('map_layers')
                ->onDelete('cascade');

            $table->index(['map_layer_id', 'checked_at']);
            $table->index(['status', 'checked_at']);
        });

        // 2. Bổ sung các cột cấu hình ngưỡng và trạng thái giám sát vào bảng map_layers
        Schema::table('map_layers', function (Blueprint $table) {
            $table->integer('max_downtime_minutes')->default(5)->after('is_active');
            $table->integer('max_latency_ms')->default(2000)->after('max_downtime_minutes');
            $table->string('last_status', 20)->default('healthy')->after('max_latency_ms');
            $table->timestamp('last_checked_at')->nullable()->after('last_status');
            $table->integer('last_latency_ms')->nullable()->after('last_checked_at');
            $table->integer('consecutive_failures')->default(0)->after('last_latency_ms');
            $table->timestamp('last_alert_sent_at')->nullable()->after('consecutive_failures');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_source_monitors');

        Schema::table('map_layers', function (Blueprint $table) {
            $table->dropColumn([
                'max_downtime_minutes',
                'max_latency_ms',
                'last_status',
                'last_checked_at',
                'last_latency_ms',
                'consecutive_failures',
                'last_alert_sent_at',
            ]);
        });
    }
};
