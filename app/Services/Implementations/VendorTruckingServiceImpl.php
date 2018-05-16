<?php

namespace App\Services\Implementations;

use App\Models\VendorTrucking;
use App\Models\BankAccount;
use App\Models\Truck;

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
        $bankAccounts,
        $trucks
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

            for ($i = 0; $i < count($trucks); $i++) {
                $tr = new Truck();
                $tr->company_id = $trucks[$i]["company_id"];
                $tr->type = $trucks[$i]["type"];
                $tr->license_plate = $trucks[$i]["license_plate"];
                $tr->inspection_date = $trucks[$i]["inspection_date"];
                $tr->driver = $trucks[$i]["driver"];
                $tr->remarks = $trucks[$i]["remarks"];

                $vendorTrucking->trucks()->save($tr);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read()
    {
        return VendorTrucking::with('bankAccounts.bank', 'trucks')->get();
    }

    public function readAllTrucksMaintainedByCompany()
    {
        $allTrucks = [];
        $vendorTrucking = VendorTrucking::with('trucks')->where('maintenance_by_company', '=', 1)->get();

        foreach ($vendorTrucking as $vt) {
            foreach ($vt->trucks as $t) {
                array_push($allTrucks, array(
                    'hId' => $t->hId,
                    'license_plate' => $t->license_plate,
                    'typeI18n' => $t->typeI18n
                ));
            }
        }

        return $allTrucks;
    }

    public function update(
        $id,
        $company_id,
        $name,
        $address,
        $tax_id,
        $status,
        $maintenanceByCompany,
        $remarks,
        $bankAccounts,
        $inputtedBankAccountIds,
        $trucks,
        $inputtedTruckIds
    )
    {
        DB::beginTransaction();

        try {
            $vendorTrucking = VendorTrucking::with('bankAccounts.bank', 'trucks')->find($id);

            $vendorTruckingBankAccountIds = $vendorTrucking->bankAccounts->map(function ($bankAccount) {
                return $bankAccount->id;
            })->all();

            $vendorTruckingBankAccountsToBeDeleted = array_diff($vendorTruckingBankAccountIds, isset($inputtedBankAccountIds) ?
                $inputtedBankAccountIds : []);

            BankAccount::destroy($vendorTruckingBankAccountsToBeDeleted);

            for ($i = 0; $i < count($bankAccounts); $i++) {
                $ba = BankAccount::findOrNew($bankAccounts[$i]['bank_account_id']);
                $ba->bank_id = $bankAccounts[$i]["bank_id"];
                $ba->account_name = $bankAccounts[$i]["account_name"];
                $ba->account_number = $bankAccounts[$i]["account_number"];
                $ba->remarks = $bankAccounts[$i]["bank_remarks"];

                $vendorTrucking->bankAccounts()->save($ba);
            }

            $vendorTruckingTruckIds = $vendorTrucking->trucks->map(function ($truck) {
                return $truck->id;
            })->all();

            $vendorTruckingTrucksToBeDeleted = array_diff($vendorTruckingTruckIds, isset($inputtedTruckIds) ?
                $inputtedTruckIds : []);

            Truck::destroy($vendorTruckingTrucksToBeDeleted);

            for ($i = 0; $i < count($trucks); $i++) {
                $tr = Truck::findOrNew($trucks[$i]['truck_id']);
                $tr->company_id = $trucks[$i]["company_id"];
                $tr->type = $trucks[$i]["type"];
                $tr->license_plate = $trucks[$i]["license_plate"];
                $tr->inspection_date = $trucks[$i]["inspection_date"];
                $tr->driver = $trucks[$i]["driver"];
                $tr->remarks = $trucks[$i]["remarks"];

                $vendorTrucking->trucks()->save($tr);
            }

            $vendorTrucking->update([
                'company_id' => $company_id,
                'name' => $name,
                'address' => $address,
                'tax_id' => $tax_id,
                'status' => $status,
                'maintenance_by_company' => $maintenanceByCompany,
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
