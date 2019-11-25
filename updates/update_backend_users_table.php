<?php

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateBackendUsersTable extends Migration
{
    public function up()
    {
        Schema::table('backend_users', function ($table) {
            $table->timestamp('last_activity')->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasColumn('backend_users', 'last_activity')) {
            Schema::table('backend_users', function ($table) {
                $table->dropColumn('last_activity');
            });
        }
    }
}
