<?php

use October\Rain\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

class UpdateBackendUsersTable extends Migration
{
    public function up()
    {
        Schema::table('backend_users', function ($table) {
            $table->timestamp('pbs_logout_last_activity')->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasColumn('backend_users', 'pbs_logout_last_activity')) {
            Schema::table('backend_users', function ($table) {
                $table->dropColumn('pbs_logout_last_activity');
            });
        }
    }
}
