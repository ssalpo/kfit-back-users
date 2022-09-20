<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->change();
            $table->tinyInteger('platform')->nullable()->comment('Platform type from import records');
            $table->string('platform_id', 50)->nullable()->comment('Platform id from import records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('phone', 20)->change();
            $table->dropColumn(['platform', 'platform_id']);
        });
    }
}
