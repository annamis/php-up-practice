<?php

return [
    'adminEmail' => 'admin@example.com',
    
    'maxFileSize' => 1024 * 1024 * 2, //2 megabites
    'storagePath' => '@frontend/web/uploads/',
    'storageUri' => '/uploads/', //http://images.com/uploads/f1/d7/a4a7f8fff5a78458.jpg
    
    'postPicture' => [
        'maxWidth' => 600,
        'maxHeight' => 600,
    ],
    
    'maxCommentContentLength' => 1000,
    
    'feedPostLimit' => 200,
];
