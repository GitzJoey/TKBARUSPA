<?php

namespace App\Services;

interface TruckService
{
    public function create(
        $store_id,
        $type,
        $plate_number,
        $inspection_date,
        $driver,
        $status,
        $remarks
    );
    public function read();
    public function update(
        $id,
        $store_id,
        $type,
        $plate_number,
        $inspection_date,
        $driver,
        $status,
        $remarks
    );
    public function delete($id);
}