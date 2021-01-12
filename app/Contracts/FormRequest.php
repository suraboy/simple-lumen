<?php

namespace App\Contracts;

use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

/**
 * Class FormRequest
 * @package App\Contracts
 */
abstract class FormRequest extends Request
{
    /**
     * @var mixed
     */
    private $auth;

    /**
     * @var bool
     */
    public $onlyEntered = false;

    /**
     * FormRequest constructor.
     */
    public function __construct()
    {
        $this->auth = config('oauth');
    }

    /**
     * @return array|void
     * @throws ValidationException
     */
    public function validate()
    {
        if (false === $this->authorize() && env('APP_ENV') != 'testing') {
            throw new UnauthorizedException();
        }
        $validator = app('validator')->make(
            $this->all(),
            $this->filterRules(),
            $this->messages(),
            $this->attributes()
        );

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }

    /**
     * @return array
     */
    protected function filterRules(): array
    {
        if ($this->onlyEntered === true) {
            $rules = [];
            $requireRules = $this->ruleRequired();
            foreach ($this->rules() as $field => $rule) {
                if ($this->has($field) || in_array($field, $requireRules)) {
                    $rules[$field] = $rule;
                }
            }
            return $rules;
        } else {
            return $this->rules();
        }
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return mixed
     */
    abstract public function rules();

    /**
     * @return array
     */
    public function ruleRequired()
    {
        return [];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function hasRoles(array $roles): bool
    {
        return (bool)array_intersect($roles, $this->auth['roles'] ?? []);
    }

    /**
     * @param array $permissions
     * @return bool
     */
    public function hasPermissions(array $permissions): bool
    {
        return (bool)array_intersect($permissions, $this->auth['permissions'] ?? []);
    }

    /**
     * @return bool
     */
    public function hasOutlets(): bool
    {
        if (self::hasRoles(['admin'])) {
            return true;
        }

        return (bool)in_array(
            intval(app('request')->get('outlet_id')),
            is_array($this->auth['outlet_id']) ? $this->auth['outlet_id'] : []
        );
    }

    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $requestData = parent::all($keys);

        $requestData = $this->mergeUrlParametersWithRequestData($requestData);

        return $requestData;
    }

    /**
     * apply validation rules to the ID's in the URL, since Laravel
     * doesn't validate them by default!
     *
     * Now you can use validation riles like this: `'id' => 'required|integer|exists:items,id'`
     *
     * @param array $requestData
     *
     * @return  array
     */
    private function mergeUrlParametersWithRequestData(array $requestData)
    {
        if (isset($this->urlParameters) && !empty($this->urlParameters)) {
            foreach ($this->urlParameters as $param) {
                $requestData[$param] = app('request')->route()[2][$param];
            }
        }

        return $requestData;
    }
}
