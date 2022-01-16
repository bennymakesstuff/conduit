<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            // Conduit Fields
            self::addAuditablesToTable($table);
            $table->rememberToken();
            $table->datetime('last_conduit_login')->nullable();

            $table->json('roles')->nullable();

            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->integer('phone')->nullable();
            $table->boolean('active')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }

    /**
     * Adds all fields that are used for auditing purposes
     * @param Blueprint $table
     * @return Blueprint
     */
    public static function addAuditablesToTable(Blueprint $table): Blueprint
    {
        $precision = 0;
        $table->id();
        $table->uuid('uuid')->unique();
        $table->integer('created_by')->nullable();
        $table->integer('updated_by')->nullable();
        $table->integer('deleted_by')->nullable();
        $table->timestamp('created_at', $precision)->nullable();
        $table->timestamp('updated_at', $precision)->nullable();
        $table->softDeletes();

        // Add foreign constraints
        $table->foreign('deleted_by')->references('id')->on('users');
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('updated_by')->references('id')->on('users');
        return $table;
    }
}
