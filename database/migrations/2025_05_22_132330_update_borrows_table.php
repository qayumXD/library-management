<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::table('borrows', function (Blueprint $table) {
    //         // Drop foreign key constraint first
    //         $table->dropForeign(['member_id']);
            
    //         // Drop old columns
    //         $table->dropColumn(['member_id', 'status', 'notes']);
            
    //         // Add new columns
    //         $table->foreignId('user_id')->constrained()->onDelete('cascade');
    //         $table->timestamp('requested_at')->nullable();
    //         $table->timestamp('approved_at')->nullable();
    //         $table->timestamp('rejected_at')->nullable();
    //         $table->boolean('is_reservation')->default(false);
    //     });
    // }
    public function up(): void
{
    Schema::table('borrows', function (Blueprint $table) {
        // Remove this line:
        // $table->dropForeign(['member_id']);

        // Drop old columns
        //$table->dropColumn(['member_id', 'status', 'notes']);

        // Add new columns
        //$table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamp('requested_at')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->timestamp('rejected_at')->nullable();
        $table->boolean('is_reservation')->default(false);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            // Drop new foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Drop new columns
            $table->dropColumn(['user_id', 'requested_at', 'approved_at', 'rejected_at', 'is_reservation']);
            
            // Add back old columns
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
        });
    }
};
