<?php

namespace Tests\Models\CustomDetect;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereLike.
 */
class WhereLikeRelation extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query): Builder
    {
        return $query
            ->whereHas('foo', function ($q) {
                $q->where('bam', 'like', '%'.$this->values['like_relation_value'].'%');
            })
            ->where("$this->filter", '<>', $this->values['value'])
            ->where('email', 'like', '%'.$this->values['email'].'%')
            ->limit($this->values['limit']);
    }
}
