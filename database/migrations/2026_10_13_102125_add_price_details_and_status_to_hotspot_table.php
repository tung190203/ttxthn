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
        Schema::table('hotspot', function (Blueprint $table) {
            $table->bigInteger('price')->nullable()->after('product_type')->comment('Giá gốc (VND)');
            $table->bigInteger('lease_price')->nullable()->after('price')->comment('Giá thuê (VND)');
            $table->bigInteger('sale_price')->nullable()->after('lease_price')->comment('Giá bán (VND)');
            $table->text('available_amenities')->nullable()->after('sale_price')->comment('Tiện ích sẵn có');
            $table->text('contact_info')->nullable()->after('available_amenities')->comment('Thông tin liên hệ');
            $table->text('transaction_note')->nullable()->after('sale_price')->comment('Ghi chú giao dịch');
            $table->foreignId('investment_product_statuses_id')->nullable()
                ->after('vrtour_id')->constrained('investment_product_statuses')->comment('Trạng thái sản phẩm đầu tư');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotspot', function (Blueprint $table) {
            $table->dropConstrainedForeignId('investment_product_statuses_id');
            $table->dropColumn(['price','lease_price', 'sale_price', 'available_amenities', 'contact_info', 'transaction_note']);
        });
    }
};
