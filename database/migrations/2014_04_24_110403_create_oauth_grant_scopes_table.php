<?php

/*
 * This file is part of the HRis Software package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @version    alpha
 *
 * @author     Bertrand Kintanar <bertrand.kintanar@gmail.com>
 * @license    BSD License (3-clause)
 * @copyright  (c) 2014-2016, b8 Studios, Ltd
 *
 * @link       http://github.com/HB-Co/HRis
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This is the create oauth grant scopes table migration class.
 *
 * @author Luca Degasperi <packages@lucadegasperi.com>
 */
class CreateOauthGrantScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_grant_scopes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('grant_id', 40);
            $table->string('scope_id', 40);

            $table->timestamps();

            $table->index('grant_id');
            $table->index('scope_id');

            $table->foreign('grant_id')
                ->references('id')->on('oauth_grants')
                ->onDelete('cascade');

            $table->foreign('scope_id')
                ->references('id')->on('oauth_scopes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_grant_scopes', function (Blueprint $table) {
            $table->dropForeign('oauth_grant_scopes_grant_id_foreign');
            $table->dropForeign('oauth_grant_scopes_scope_id_foreign');
        });
        Schema::drop('oauth_grant_scopes');
    }
}
