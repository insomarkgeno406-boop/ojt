<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, use raw SQL to modify ENUMs
        DB::statement("ALTER TABLE messages MODIFY sender_type ENUM('admin', 'intern', 'supervisor')");
        DB::statement("ALTER TABLE messages MODIFY receiver_type ENUM('admin', 'intern', 'supervisor')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE messages MODIFY sender_type ENUM('admin', 'intern')");
        DB::statement("ALTER TABLE messages MODIFY receiver_type ENUM('admin', 'intern')");
    }
};
