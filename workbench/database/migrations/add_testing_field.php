<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'foreign_key_id')->nullable();
            $table->integer('integer_field')->default(0);
            $table->decimal('decimal_field', 9, 2)->default(0);
            $table->boolean('boolean_field')->nullable();
            $table->string('string_field')->nullable();
            $table->date('date_field')->nullable();
            $table->dateTime('datetime_field')->nullable();
            $table->string('enum_field')->default('pending');
            $table->text('array_field')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
