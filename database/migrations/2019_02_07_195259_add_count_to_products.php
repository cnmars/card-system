<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; class AddCountToProducts extends Migration { public function up() { if (!Schema::hasColumn('products', 'count_all')) { Schema::table('products', function (Blueprint $sp2bac3d) { $sp2bac3d->integer('count_all')->default(0)->after('count_sold'); }); App\Product::whereRaw('1')->update(array('count_sold' => 0, 'count_all' => 0)); \App\Card::selectRaw('`product_id`,SUM(`count_sold`) as `count_sold`,SUM(`count_all`) as `count_all`')->groupBy('product_id')->orderByRaw('`product_id`')->chunk(100, function ($spa9e8db) { foreach ($spa9e8db as $sp097637) { \App\Product::where('id', $sp097637->product_id)->update(array('count_sold' => $sp097637->count_sold, 'count_all' => $sp097637->count_all)); } }); } } public function down() { if (Schema::hasColumn('products', 'count_all')) { Schema::table('products', function (Blueprint $sp2bac3d) { $sp2bac3d->dropColumn(array('count_all')); }); } } }