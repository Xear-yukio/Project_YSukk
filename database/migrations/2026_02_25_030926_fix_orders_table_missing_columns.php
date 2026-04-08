<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'courier_name')) {
                $table->string('courier_name')->nullable();
            }
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable();
            }
            if (!Schema::hasColumn('orders', 'estimated_arrival')) {
                $table->string('estimated_arrival')->nullable();
            }
            if (!Schema::hasColumn('orders', 'current_location')) {
                $table->string('current_location')->nullable();
            }
            if (!Schema::hasColumn('orders', 'shipping_history')) {
                $table->text('shipping_history')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['courier_name', 'tracking_number', 'estimated_arrival', 'current_location', 'shipping_history'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
