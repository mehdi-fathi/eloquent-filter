<?php

namespace eloquentFilter\QueryFilter\Factory;

use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCoreBuilder;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\SpecialCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereBetweenCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereByOptCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\WhereCondition;
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
        return app(QueryFilterCoreBuilder::class, ['default_injected' => $this->getDefaultDetectorsEloquent()]);
    }

    /**
     * @return array
     * @note DON'T CHANGE ORDER THESE BASED ON FLIMSY REASON.
     */
    private function getDefaultDetectorsEloquent(): array
    {
        return [
            SpecialCondition::class,
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
