<?php

namespace eloquentFilter\QueryFilter\Factory;

use eloquentFilter\QueryFilter\Core\QueryFilterCoreBuilder;
use eloquentFilter\QueryFilter\Core\QueryFilterCore;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\SpecialCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereBetweenCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereByOptCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereCustomCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereDateCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereHasCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereInCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereLikeCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereOrCondition;

/**
 * Class QueryFilterCoreFactory.
 */
class QueryFilterCoreFactory
{
    public function createQueryFilterCoreBuilder(): QueryFilterCore
    {
        return new QueryFilterCoreBuilder($this->getDefaultDetectorsInstance());
    }

    /**
     * @return array
     */
    private function getDefaultDetectorsInstance(): array
    {
        return [
            SpecialCondition::class,
            WhereCustomCondition::class,
            WhereBetweenCondition::class,
            WhereByOptCondition::class,
            WhereLikeCondition::class,
            WhereInCondition::class,
            WhereOrCondition::class,
            WhereHasCondition::class,
            WhereDateCondition::class,
            WhereCondition::class,
        ];
    }
}
//todo desing pattern adaptor baraye corebuilder and composite baraye add ..conditions
