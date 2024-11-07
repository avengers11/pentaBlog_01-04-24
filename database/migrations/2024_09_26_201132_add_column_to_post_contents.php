<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPostContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('post_contents', 'meta_keyword_ids')) {
                $table->text('meta_keyword_ids')->nullable();
            }
            if (!Schema::hasColumn('post_contents', 'preview')) {
                $table->json('preview')->nullable();
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
        Schema::table('post_contents', function (Blueprint $table) {
            //
        });
    }
}
