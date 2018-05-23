<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/23/2018
 * Time: 10:56 AM
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CurrentStockFilterScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->where($model->getTable().'.is_current', '=', 1);
    }

    public function remove(Builder $builder, Model $model)
    {
        $query = $builder->getQuery();

        foreach((array)$query->wheres as $key => $where) {

            if($where['column'] == 'is_current') {

                unset($query->wheres[$key]);

                $query->wheres = array_values($query->wheres);
            }
        }
    }
}