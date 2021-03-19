<?php

    register_activation_hook( __FILE__, function(){
        magazine_template::_createDemoTemplate();
    });