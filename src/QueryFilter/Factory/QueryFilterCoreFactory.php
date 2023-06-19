<?php

namespace eloquentFilter\QueryFilter\Factory;

use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCoreBuilder;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB\DBBuilderQueryByCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\MainBuilderQueryByCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\SpecialCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereBetweenCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereByOptCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereDateCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereHasCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereInCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereLikeCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\WhereOrCondition;

/**
 * Class QueryFilterCoreFactory.
 */
class QueryFilterCoreFactory
{
    public function createQueryFilterCoreEloquentBuilder(): QueryFilterCore
    {
        $mainBuilderConditions = new MainBuilderQueryByCondition();
        return app(QueryFilterCoreBuilder::class, ['defaultSeriesInjected' => $this->getDefaultDetectorsEloquent(), 'detectInjected' => null, 'mainBuilderConditions' => $mainBuilderConditions]);
    }

    public function createQueryFilterCoreDBQueryBuilder(): QueryFilterCore
    {
        $mainBuilderConditions = new DBBuilderQueryByCondition();
        return app(QueryFilterCoreBuilder::class, ['defaultSeriesInjected' => $this->getDefaultDetectorsEloquent(), 'detectInjected' => null, 'mainBuilderConditions' => $mainBuilderConditions]);
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
