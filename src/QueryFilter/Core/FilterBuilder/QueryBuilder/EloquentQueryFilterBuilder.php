<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\QueryBuilder;

use eloquentFilter\QueryFilter\Core\EloquentBuilder\EloquentModelBuilderWrapper;
use eloquentFilter\QueryFilter\Core\ResolverDetections;
use eloquentFilter\QueryFilter\Factory\QueryBuilderWrapperFactory;

/**
 * Class EloquentQueryFilterBuilder.
 */
class EloquentQueryFilterBuilder extends QueryFilterBuilder
{
    protected EloquentModelBuilderWrapper $queryBuilderWrapper;

    /**
     * @param \eloquentFilter\QueryFilter\Core\EloquentBuilder\EloquentModelBuilderWrapper $queryBuilderWrapper
     */
    public function setQueryBuilderWrapper(EloquentModelBuilderWrapper $queryBuilderWrapper): void
    {
        $this->queryBuilderWrapper = $queryBuilderWrapper;
    }

    /**
     * @return \eloquentFilter\QueryFilter\Core\EloquentBuilder\EloquentModelBuilderWrapper
     */
    public function getQueryBuilderWrapper(): EloquentModelBuilderWrapper
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

        $this->buildExclusiveMacros($detections_injected);

        $this->setQueryBuilderWrapper(QueryBuilderWrapperFactory::createEloquentQueryBuilder($builder));

        $this->resolveDetections($detections_injected, $black_list_detections);

        return $this->getQueryBuilderWrapper()->getResponseFilter($this->responseFilter->getResponse());
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    private function resolveDetections($detections_injected, $black_list_detections)
    {
        $this->queryFilterCore->unsetDetection($black_list_detections);
        $this->queryFilterCore->reloadDetectionInjected();

        $this->queryFilterCore->setDetectionsInjected($detections_injected);

        /** @see ResolverDetections */
        app()->bind('ResolverDetections', function () {
            return new ResolverDetections(
                builder: $this->getQueryBuilderWrapper()->getBuilder(),
                request: $this->requestFilter->getRequest(),
                detector_factory: $this->queryFilterCore->getDetectFactory(),
                main_builder_conditions_contract: $this->queryFilterCore->getMainBuilderConditions()
            );
        });

        /** @see ResolverDetections::getResolverOut() */
        $responseResolver = app('ResolverDetections')->getResolverOut();


        $this->responseFilter->setResponse($responseResolver);
    }

    /**
     * @return string
     */
    public function getNameDriver()
    {
        $MainBuilderConditions = $this->queryFilterCore->getMainBuilderConditions();

        return $MainBuilderConditions->getName();
    }

    /**
     * @param array|null $detections_injected
     * @return void
     */
    private function buildExclusiveMacros(?array $detections_injected): void
    {
        $this->setMacroIsUsedPackage();

        $this->setMacroDetectionInjectedList($detections_injected);

    }

    /**
     * @return void
     */
    private function setMacroIsUsedPackage(): void
    {
        \Illuminate\Database\Eloquent\Builder::macro('isUsedEloquentFilter', function () {
            return config('eloquentFilter.enabled');
        });
    }

    /**
     * @param array|null $detections_injected
     * @return void
     */
    private function setMacroDetectionInjectedList(?array $detections_injected): void
    {
        \Illuminate\Database\Eloquent\Builder::macro('getDetectionsInjected', function () use ($detections_injected) {
            return $detections_injected;
        });
    }

}
