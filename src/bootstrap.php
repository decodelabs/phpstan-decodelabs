<?php

if (class_exists('DecodeLabs\\Veneer')) {
    echo 'Info: Using cache Veneer bindings'."\n\n";
    \DecodeLabs\Veneer::setCacheBindings(true);
}
