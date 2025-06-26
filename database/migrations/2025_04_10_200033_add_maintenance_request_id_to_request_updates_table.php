<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('request_updates', function (Blueprint $table) {
            $table->unsignedBigInteger('maintenance_request_id')->after('id');

            $table->foreign('maintenance_request_id')
                ->references('id')
                ->on('maintenance_requests')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('request_updates', function (Blueprint $table) {
            $table->dropForeign(['maintenance_request_id']);
            $table->dropColumn('maintenance_request_id');
        });
    }
};