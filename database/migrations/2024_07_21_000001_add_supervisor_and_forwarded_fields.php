<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add supervisor_id to interns
        Schema::table('interns', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('id');
            $table->foreign('supervisor_id')->references('id')->on('supervisors')->onDelete('set null');
        });
        // Add supervisor_id and forwarded_to_admin to grade_submissions
        Schema::table('grade_submissions', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('intern_id');
            $table->foreign('supervisor_id')->references('id')->on('supervisors')->onDelete('set null');
            $table->boolean('forwarded_to_admin')->default(false);
        });
        // Add supervisor_id and forwarded_to_admin to documents
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('intern_id');
            $table->foreign('supervisor_id')->references('id')->on('supervisors')->onDelete('set null');
            $table->boolean('forwarded_to_admin')->default(false);
        });
    }
    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn('supervisor_id');
        });
        Schema::table('grade_submissions', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn('supervisor_id');
            $table->dropColumn('forwarded_to_admin');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn('supervisor_id');
            $table->dropColumn('forwarded_to_admin');
        });
    }
}; 