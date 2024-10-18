<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\IO;

use eloquentFilter\QueryFilter\Core\HelperFilter;
use Illuminate\Support\Arr;

/**
 *
 */
class RequestFilter
{

    use Encoder;

    /**
     * @var
     */
    protected mixed $accept_request = null;

    /**
     * @var
     */
    protected mixed $ignore_request = null;

    /**
     * @var array|null
     */
    protected ?array $request;

    /**
     * @var
     */
    public $requestEncoded;

    /**
     * @param array|null $request
     */
    public function __construct(?array $request)
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
        $this->handleRequestEncoded($request);
    }

    /**
     * @param array|null $request
     * @param $salt
     */
    public function setRequestEncoded(?array $request, $salt): void
    {
        $this->requestEncoded = $this->encodeWithSalt(json_encode($request), $salt);
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
     * @param $builder_model
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

                $value = $this->getCastedMethodValue($name, $builder_model, $value);

                if (is_array($value) && !empty($builder_model) && method_exists($builder_model, $name)) {
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

    /**
     * @param int|string $name
     * @param $builder_model
     * @param mixed $value
     * @return mixed
     */
    private function getCastedMethodValue(int|string $name, $builder_model, mixed $value): mixed
    {
        $castMethod = config('eloquentFilter.cast_method_sign') . $name;

        if (!empty($builder_model) && method_exists($builder_model, $castMethod)) {
            $value = $builder_model->{$castMethod}($value);
            $this->request[$name] = $value;
        }
        return $value;
    }

    /**
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @return void
     */
    public function handleRequestDb(?array $ignore_request, ?array $accept_request): void
    {

        $serialize_request_filter = $this->getRequest();

        $this->requestAlter(
            ignore_request: $ignore_request,
            accept_request: $accept_request,
            serialize_request_filter: $serialize_request_filter,
            alias_list_filter: $alias_list_filter ?? [],
            model: null,
        );
    }

    /**
     * @param $builder
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @return void
     */
    public function handleRequest($builder, ?array $ignore_request, ?array $accept_request): void
    {

        $serialize_request_filter = $builder->getModel()->serializeRequestFilter($this->getRequest());

        $alias_list_filter = $builder->getModel()->getAliasListFilter();

        $this->requestAlter(
            ignore_request: $ignore_request,
            accept_request: $accept_request,
            serialize_request_filter: $serialize_request_filter,
            alias_list_filter: $alias_list_filter ?? [],
            model: $builder->getModel(),
        );
    }

    /**
     * @param $request
     * @return void
     */
    private function handleRequestEncoded($request): void
    {
        if (isset($request['hashed_filters'])) {
            $this->request = json_decode($this->decodeWithSalt($request['hashed_filters'], config('eloquentFilter.request_salt')()), true);
        } else {
            $this->setRequestEncoded($request, config('eloquentFilter.request_salt')());
        }
    }

}
