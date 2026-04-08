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
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('estimated_arrival')->nullable();
            $table->string('current_location')->nullable();
            $table->text('shipping_history')->nullable();
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
            if (Schema::hasColumn('orders', 'courier_name')) {
                $table->dropColumn(['courier_name', 'tracking_number', 'estimated_arrival', 'current_location', 'shipping_history']);
            }
        });
    }
};
