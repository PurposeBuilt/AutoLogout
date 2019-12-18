<?php

use October\Rain\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

class UpdateFrontEndUsersTable extends Migration
{
    public function up()
    {
        if (class_exists('Rainlab\User\Models\User')) {
            Schema::table('users', function ($table) {
                $table->timestamp('pbs_logout_last_activity')->nullable();
            });
        }
    }

    public function down()
    {
        if (class_exists('Rainlab\User\Models\User') && Schema::hasColumn('users', 'pbs_logout_last_activity')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('pbs_logout_last_activity');
            });
        }
    }
}
