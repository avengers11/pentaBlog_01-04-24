<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopandShopDetailstoUserPageHeadings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_page_headings', function (Blueprint $table) {
            $table->string('shop')->nullable();
            $table->string('shop_details')->nullable();
            $table->string('cart')->nullable();
            $table->string('checkout')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
