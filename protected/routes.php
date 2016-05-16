<?php

return [
    
    '/news'     => '//News/ShowAll',
    '/news/<1>' => '//News/ShowById(id=<1>)',
    '/admin/news' => '//News/Admin',
    '/admin/news/add' => '//News/Add',
    '/admin/news/del/<1>' => '//News/Del(id=<1>)',
    '/admin/news/edit/<1>' => '//News/Edit(id=<1>)',
    '/admin/news/list' => '//News/ShowAllTbl',
];