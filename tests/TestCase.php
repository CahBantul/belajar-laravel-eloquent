<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("DELETE from products");
        DB::delete("DELETE from categories");
        DB::delete("DELETE from vouchers");
        DB::delete("DELETE from comments");
        DB::delete("DELETE from virtual_accounts");
        DB::delete("DELETE from wallets");
        DB::delete("DELETE from customers");
    }
}
