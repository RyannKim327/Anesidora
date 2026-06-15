<?php

foreach (glob(__DIR__.'/functions/*.php') as $file) {
    require $file;
}

foreach (glob(__DIR__.'/api/*.php') as $file) {
    require $file;
}
