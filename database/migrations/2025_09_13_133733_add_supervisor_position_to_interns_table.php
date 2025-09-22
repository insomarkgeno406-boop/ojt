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
    Schema::table('interns', function (Blueprint $table) {
        $table->string('supervisor_position')->nullable();
    });
}

public function down()
{
    Schema::table('interns', function (Blueprint $table) {
        $table->dropColumn('supervisor_position');
    });
}

};
