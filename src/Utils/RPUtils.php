<?php

namespace Afiqiqmal\Rpclient\Utils;

class RPUtils
{
    /**
     * @param array $extras
     * @return array
     */
    public static function buildBodyRequest(array $extras) {
        $data = [];
        foreach ($extras as $key => $extra) {
            $data[$key != "page" ? "filter[$key]" : "page"] = $extra;
        }

        return $data;
    }
}