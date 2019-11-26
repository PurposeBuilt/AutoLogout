<?php

use October\Rain\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

class UpdateFrontEndUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->timestamp('last_activity')->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'last_activity')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('last_activity');
            });
        }
    }
}
