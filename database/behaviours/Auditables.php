<?php

namespace Database\Behaviors;

use Illuminate\Database\Schema\Blueprint;

class Auditables
{
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
