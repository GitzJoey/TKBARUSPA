<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/14/2016
 * Time: 1:17 AM
 */

use \Illuminate\Support\Facades\Schema;
use \Illuminate\Database\Schema\Blueprint;
use \Illuminate\Database\Migrations\Migration;

Class AlterUsersTable extends Migration
{
    public function up()
    {
        if(Schema::hasTable('users') && !Schema::hasColumn('users', 'company_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('company_id')->default(0);
                $table->string('email_activation_token', 60)->unique();
                $table->boolean('active')->default(false);
            });
        }

        DB::statement("ALTER TABLE users CHANGE COLUMN company_id company_id BIGINT(20) UNSIGNED DEFAULT '0' AFTER id");
        DB::statement("ALTER TABLE users CHANGE COLUMN email_activation_token email_activation_token VARCHAR(60) CHARACTER SET 'utf8' NULL DEFAULT NULL AFTER remember_token");
        DB::statement("ALTER TABLE users CHANGE COLUMN active active TINYINT(1) NOT NULL DEFAULT '0' AFTER email_activation_token");
    }

    public function down()
    {

    }
}