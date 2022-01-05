<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_groups', function (Blueprint $table) {
          self::addAuditablesToTable($table);
          $table->string('identifier',50)->unique();
          $table->string('title',50);
          $table->text('description')->nullable();
          $table->boolean('active')->default(true);
          $table->timestamps();
        });


      Schema::table('permissions', function (Blueprint $table) {
        $table->foreign('permission_group')->references('id')->on('permissions');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_groups');
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
    $table->integer('activation_toggled_by')->nullable();
    $table->timestamp('activation_toggled__at', $precision)->nullable();
    $table->softDeletes();

    // Add foreign constraints
    $table->foreign('deleted_by')->references('id')->on('users');
    $table->foreign('created_by')->references('id')->on('users');
    $table->foreign('updated_by')->references('id')->on('users');
    $table->foreign('activation_toggled_by')->references('id')->on('users');
    return $table;
  }
}
