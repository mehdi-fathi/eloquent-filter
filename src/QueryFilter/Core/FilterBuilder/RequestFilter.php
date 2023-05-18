<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

use eloquentFilter\QueryFilter\Core\HelperFilter;
use Illuminate\Support\Arr;

/**
 *
 */
class RequestFilter
{
    /**
     * @var
     */
    protected $accept_request;

    /**
     * @var
     */
    protected $ignore_request;

    /**
     * @var array|null
     */
    protected ?array $request;

    /**
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @param $request
     * @return void
     */
    public function setPureRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return void
     */
    public function handelSerializeRequestFilter($request)
    {
        $this->setRequest($request);
    }

    /**
     * @param array|null $request
     */
    public function setRequest(?array $request): void
    {
        if (!empty($request['page'])) {
            unset($request['page']);
        }

        $request_key_filter = config('eloquentFilter.request_filter_key');

        if (!empty($request_key_filter)) {
            $request = (!empty($request[$request_key_filter])) ? $request[$request_key_filter] : [];
        }

        $request = array_filter($request, function ($value) {
            return !is_null($value) && $value !== '';
        });

        foreach ($request as $key => $item) {
            if (is_array($item)) {
                if (array_key_exists('start', $item) && array_key_exists('end', $item)) {
                    if (!isset($item['start']) && !isset($item['end'])) {
                        unset($request[$key]);
                    }
                }
            }
        }

        $this->request = $request;
    }

    /**
     * @return array|null
     */
    public function getRequest(): ?array
    {
        return $this->request;
    }

    /**
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param            $builder_model
     *
     * @return array|null
     */
    public function setFilterRequests(array $ignore_request = null, array $accept_request = null, $builder_model): ?array
    {
        if (!empty($this->getRequest())) {
            if (!empty(config('eloquentFilter.ignore_request'))) {
                $ignore_request = array_merge(config('eloquentFilter.ignore_request'), (array)$ignore_request);
            }
            if (!empty($ignore_request)) {
                $this->updateRequestByIgnoreRequest($ignore_request);
            }
            if (!empty($accept_request)) {
                $this->setAcceptRequest($accept_request);
                $this->updateRequestByAcceptRequest($this->getAcceptRequest());
            }

            foreach ($this->getRequest() as $name => $value) {
                if (is_array($value) && method_exists($builder_model, $name)) {
                    if (HelperFilter::isAssoc($value)) {
                        unset($this->request[$name]);
                        $out = HelperFilter::convertRelationArrayRequestToStr($name, $value);
                        $this->setRequest(array_merge($out, $this->request));
                    }
                }
            }
        }

        return $this->getRequest();
    }

    /**
     * @param $alias_list_filter
     * @return void
     */
    public function makeAliasRequestFilter($alias_list_filter)
    {
        if (empty($this->getRequest())) {
            return;
        }
        $req = $this->getRequest();

        $req = collect($req)->mapWithKeys(function ($item, $key) use ($alias_list_filter) {
            $key1 = array_search($key, $alias_list_filter);

            if (!empty($alias_list_filter[$key1])) {
                $req[$key1] = $this->getRequest()[$key];
            } else {
                $req[$key] = $item;
            }

            return $req;
        })->toArray();

        if (!empty($req)) {
            $this->setRequest($req);
        }
    }

    /**
     * @param $ignore_request
     */
    private function updateRequestByIgnoreRequest($ignore_request)
    {
        $this->setIgnoreRequest($ignore_request);
        $data = Arr::except($this->getRequest(), $ignore_request);
        $this->setRequest($data);
    }

    /**
     * @param $accept_request
     */
    private function updateRequestByAcceptRequest($accept_request)
    {
        $accept_request_new = HelperFilter::array_slice_keys($this->getRequest(), $accept_request);
        if (!empty($accept_request_new)) {
            $this->setAcceptRequest(HelperFilter::array_slice_keys($this->getRequest(), $accept_request));
            $this->setRequest($this->getAcceptRequest());
        } else {
            $this->setRequest([]);
        }
    }

    /**
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array|null $serialize_request_filter
     * @param $alias_list_filter
     * @param $model
     * @return void
     */
    public function requestAlter(?array $ignore_request, ?array $accept_request, ?array $serialize_request_filter, $alias_list_filter, $model): void
    {
        $this->handelSerializeRequestFilter($serialize_request_filter);

        if ($alias_list_filter) {
            $this->makeAliasRequestFilter($alias_list_filter);
        }

        $this->setFilterRequests($ignore_request, $accept_request, $model);
    }

    /**
     * @param array $ignore_request
     */
    private function setIgnoreRequest(array $ignore_request): void
    {
        $this->ignore_request = $ignore_request;
    }

    /**
     * @param array $accept_request
     */
    private function setAcceptRequest(array $accept_request): void
    {
        if (!empty($accept_request)) {
            $this->accept_request = $accept_request;
        }
    }

    /**
     * @return mixed
     */
    public function getAcceptRequest()
    {
        return $this->accept_request;
    }

    /**
     * @return mixed
     */
    public function getIgnoreRequest()
    {
        return $this->ignore_request;
    }
}
