<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHeroSectionPostsToUserHomeSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_home_sections', function (Blueprint $table) {
            $table->tinyInteger('hero_section_posts')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_home_sections', function (Blueprint $table) {
            $table->dropColumn('hero_section_posts');
        });
    }
}
