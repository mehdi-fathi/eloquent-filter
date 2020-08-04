<?php


namespace eloquentFilter\QueryFilter\Detection;


class WhereLikeCondition implements Detector
{
    public function detect($field, $params)
    {
        if (!empty($params['like'])) {
            return 'like';
        }
    }
}
