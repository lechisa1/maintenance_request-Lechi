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
    Schema::table('departments', function (Blueprint $table) {
        $table->unsignedBigInteger('division_id')->after('id'); // or after any relevant column
        $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('departments', function (Blueprint $table) {
        $table->dropForeign(['division_id']);
        $table->dropColumn('division_id');
    });
}
};
