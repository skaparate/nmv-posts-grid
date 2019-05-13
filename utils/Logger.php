<?php

namespace Nicomv\PostsGrid\Utils;

class Logger {
    public static function log( $msg )
    {
        if ( WP_DEBUG ) {
            error_log( $msg );
        }
    }
}