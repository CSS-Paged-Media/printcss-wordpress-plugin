<?php

    register_activation_hook(__DIR__ . '/magazine.php', function(){
        magazine_template::_createDemoTemplate();
    });