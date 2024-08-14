<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\QueryBuilder;

use eloquentFilter\QueryFilter\Core\DbBuilder\DbBuilderWrapperInterface;
use eloquentFilter\QueryFilter\Core\ResolverDetection\ResolverDetectionDb;
use eloquentFilter\QueryFilter\Factory\QueryBuilderWrapperFactory;

/**
 * Class DBQueryFilterBuilder.
 */
class DBQueryFilterBuilder extends QueryFilterBuilder
{

    protected DbBuilderWrapperInterface $queryBuilderWrapper;

    /**
     * @param \eloquentFilter\QueryFilter\Core\DbBuilder\DbBuilderWrapperInterface $queryBuilderWrapper
     */
    public function setQueryBuilderWrapper(DbBuilderWrapperInterface $queryBuilderWrapper): void
    {
        $this->queryBuilderWrapper = $queryBuilderWrapper;
    }

    /**
     * @return \eloquentFilter\QueryFilter\Core\DbBuilder\DbBuilderWrapperInterface
     */
    public function getQueryBuilderWrapper(): DbBuilderWrapperInterface
    {
        return $this->queryBuilderWrapper;
    }

    /**
     * @param $builder
     * @param array|null $detections_injected
     * @param array|null $black_list_detections
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function apply($builder, array $detections_injected = null, array $black_list_detections = null): mixed
    {
        $this->setMacroIsUsedPackage();

        $this->setQueryBuilderWrapper(QueryBuilderWrapperFactory::createDbQueryBuilder($builder));

        $this->resolveDetections($detections_injected, $black_list_detections);

        return $this->responseFilter->getResponse();
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function resolveDetections($detections_injected, $black_list_detections)
    {
        $this->queryFilterCore->unsetDetection($black_list_detections);
        $this->queryFilterCore->setDetectionsDbInjected($detections_injected);

        /** @see \eloquentFilter\QueryFilter\Core\ResolverDetection\ResolverDetectionDb */
        app()->bind('ResolverDetectionsDb', function () {
            return new ResolverDetectionDb(
                builder: $this->getQueryBuilderWrapper()->getBuilder(),
                request: $this->requestFilter->getRequest(),
                detector_db_factory: $this->queryFilterCore->getDetectDbFactory(),
                main_builder_conditions_contract: $this->queryFilterCore->getMainBuilderConditions()
            );
        });

        /** @see ResolverDetectionDb::getResolverOut() */
        $responseResolver = app('ResolverDetectionsDb')->getResolverOut();

        $this->responseFilter->setResponse($responseResolver);
    }


    /**
     * @return void
     */
    private function setMacroIsUsedPackage(): void
    {
        \Illuminate\Database\Query\Builder::macro('isUsedEloquentFilter', function () {
            return config('eloquentFilter.enabled');
        });
    }

}
