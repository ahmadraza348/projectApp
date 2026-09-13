<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * users.department_id -> departments.id is added here, after both tables
     * exist, because 'users' and 'departments' reference each other
     * (departments.user_id -> users.id, users.department_id -> departments.id).
     * Adding both constraints at table-creation time would be circular.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('department_id')
                ->references('id')->on('departments')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });
    }
};
