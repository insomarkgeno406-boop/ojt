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
        Schema::table('interns', function (Blueprint $table) {
            // Add pre_enrollment_status for the initial registration phase
            $table->enum('pre_enrollment_status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamp('pre_enrollment_accepted_at')->nullable();
            // New fields for company and supervisor details
            if (!Schema::hasColumn('interns', 'company_address')) {
                $table->string('company_address')->nullable();
            }
            if (!Schema::hasColumn('interns', 'supervisor_position')) {
                $table->string('supervisor_position')->nullable();
            }
        });
        
        // Update current_phase enum to include pre_enrollment
        DB::statement("ALTER TABLE interns MODIFY COLUMN current_phase ENUM('pre_enrollment', 'pre_deployment', 'mid_deployment', 'deployment', 'completed') DEFAULT 'pre_enrollment'");
        
        // Update existing records to have pre_enrollment as current_phase
        DB::statement("UPDATE interns SET current_phase = 'pre_enrollment' WHERE current_phase IS NULL OR current_phase = 'pre_deployment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table) {
            $table->dropColumn([
                'pre_enrollment_status',
                'pre_enrollment_accepted_at',
                'company_address',
                'supervisor_position'
            ]);
        });
        
        // Revert current_phase enum
        DB::statement("ALTER TABLE interns MODIFY COLUMN current_phase ENUM('pre_deployment', 'mid_deployment', 'deployment', 'completed') DEFAULT 'pre_deployment'");
        
        // Update existing records back to pre_deployment
        DB::statement("UPDATE interns SET current_phase = 'pre_deployment' WHERE current_phase = 'pre_enrollment'");
    }
};
