<?php

namespace App\Services\Implementations;

use App\Models\VendorTrucking;
use App\Models\BankAccount;

use DB;
use Exception;

use App\Services\VendorTruckingService;

class VendorTruckingServiceImpl implements VendorTruckingService
{

    public function create(
        $company_id,
        $name,
        $address,
        $tax_id,
        $status,
        $remarks,
        $bankAccounts
    )
    {
        DB::beginTransaction();
        try {
            $vendorTrucking = VendorTrucking::create([
                'company_id' => $company_id,
                'name' => $name,
                'address' => $address,
                'tax_id' => $tax_id,
                'status' => $status,
                'remarks' => $remarks
            ]);

            for ($i = 0; $i < count($bankAccounts); $i++) {
                $ba = new BankAccount();
                $ba->bank_id = $bankAccounts[$i]["bank_id"];
                $ba->account_name = $bankAccounts[$i]["account_name"];
                $ba->account_number = $bankAccounts[$i]["account_number"];
                $ba->remarks = $bankAccounts[$i]["bank_remarks"];

                $vendorTrucking->bankAccounts()->save($ba);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read()
    {
        return VendorTrucking::with('bankAccounts.bank')->get();
    }

    public function update(
        $id,
        $company_id,
        $name,
        $address,
        $tax_id,
        $status,
        $remarks,
        $bankAccounts
    )
    {
        DB::beginTransaction();

        try {
            $vendorTrucking = VendorTrucking::with('bankAccounts.bank')->find($id);

            $vendorTrucking->bankAccounts->each(function($ba) { $ba->delete(); });

            for ($i = 0; $i < count($bankAccounts); $i++) {
                $ba = new BankAccount();
                $ba->bank_id = $bankAccounts[$i]["bank_id"];
                $ba->account_name = $bankAccounts[$i]["account_name"];
                $ba->account_number = $bankAccounts[$i]["account_number"];
                $ba->remarks = $bankAccounts[$i]["bank_remarks"];

                $vendorTrucking->bankAccounts()->save($ba);
            }

            $vendorTrucking->update([
                'company_id' => $company_id,
                'name' => $name,
                'address' => $address,
                'tax_id' => $tax_id,
                'status' => $status,
                'remarks' => $remarks
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        VendorTrucking::find($id)->delete();
    }
}
