<?php

use PBS\Logout\Processor;

Route::get('/pbs/logout/{driver}/{id}', function($driver, $id) {
    //
    app(Processor::class)->driver($driver)->logout($id);
});
