<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('other_designations')->nullable()->after('designation');
        });
    }

    public function down()
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn('other_designations');
        });
    }
};
