<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopandShopDetailstoUserSeos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_seos', function (Blueprint $table) {
            $table->string('meta_keyword_shop')->nullable();
            $table->text('meta_description_shop')->nullable();
            $table->string('meta_keyword_shop_details')->nullable();
            $table->text('meta_description_shop_details')->nullable();
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
