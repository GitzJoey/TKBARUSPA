<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/22/2018
 * Time: 12:50 AM
 */

namespace App\Services\Implementations;

use App\Models\Company;

use App\Services\CompanyService;

use Log;
use Config;
use Validator;
use LaravelLocalization;

class CompanyServiceImpl implements CompanyService
{
    public function create(
        $name,
        $address,
        $latitude,
        $longitude,
        $phone_num,
        $fax_num,
        $tax_id,
        $status,
        $is_default,
        $frontweb,
        $image_filename,
        $remarks,
        $date_format,
        $time_format,
        $thousand_separator,
        $decimal_separator,
        $decimal_digit,
        $ribbon
    )
    {
        // TODO: Implement create() method.
    }

    public function read($id)
    {
        return Company::find($id)->first();
    }

    public function readAll($limit = 0)
    {
        if ($limit != 0) {
            return Company::latest()->take($limit)->get();
        } else {
            return Company::get();
        }
    }

    public function update(
        $id,
        $name,
        $address,
        $latitude,
        $longitude,
        $phone_num,
        $fax_num,
        $tax_id,
        $status,
        $is_default,
        $frontweb,
        $image_filename,
        $remarks,
        $date_format,
        $time_format,
        $thousand_separator,
        $decimal_separator,
        $decimal_digit,
        $ribbon
    )
    {
        Log::debug('[CompanyServiceImpl@update] $id:' . $id);

        DB::beginTransaction();

        try {
            $company = Company::find($id);

            $imageName = '';

            if (!empty($company ->image_filename)) {
                if (!empty($data->image_path)) {
                    $imageName = time() . '.' . $data->image_path->getClientOriginalExtension();
                    $path = public_path('images') . '/' . $imageName;

                    Image::make($data->image_path->getRealPath())->resize(160, 160)->save($path);
                } else {
                    $imageName = $store->image_filename;
                }
            } else {
                if (!empty($data->image_path)) {
                    $imageName = time() . '.' . $data->image_path->getClientOriginalExtension();
                    $path = public_path('images') . '/' . $imageName;

                    Image::make($data->image_path->getRealPath())->resize(160, 160)->save($path);
                } else {
                    $imageName = '';
                }
            }

            if ($store->is_default == 'YESNOSELECT.NO' && $data['is_default'] == 'YESNOSELECT.YES') {
                $this->storeService->resetIsDefault();
            }

            if ($store->frontweb == 'YESNOSELECT.NO' && $data['frontweb'] == 'YESNOSELECT.YES') {
                $this->storeService->resetFrontWeb();
            }

            $store->bankAccounts->each(function($ba) { $ba->delete(); });

            for ($i = 0; $i < count($data['bank']); $i++) {
                $ba = new BankAccount();
                $ba->bank_id = $data["bank"][$i];
                $ba->account_name = $data["account_name"][$i];
                $ba->account_number = $data["account_number"][$i];
                $ba->remarks = $data["bank_remarks"][$i];

                $store->bankAccounts()->save($ba);
            }

            $store->currenciesConversions->each(function($curConv) { $curConv->delete(); });
            for ($i = 0; $i < count($data['currencies']); $i++) {
                $curConv = new CurrenciesConversion();
                $curConv->currencies_id = $data["currencies"][$i];
                $curConv->is_base = $data["base_currencies"][$i] == '1'?true:false;
                $curConv->conversion_value = $data["currencies_conversion_value"][$i];
                $curConv->remarks = $data["currencies_remarks"][$i];

                $store->currenciesConversions()->save($curConv);
            }

            $store->name = $data['name'];
            $store->address = $data['address'];
            $store->latitude = empty($data['latitude']) ? 0:$data['latitude'];
            $store->longitude = empty($data['longitude']) ? 0:$data['longitude'];
            $store->phone_num = $data['phone_num'];
            $store->fax_num = $data['fax_num'];
            $store->tax_id = $data['tax_id'];
            $store->status = $data['status'];
            $store->is_default = $data['is_default'];
            $store->image_filename = $imageName;
            $store->frontweb = $data['frontweb'];
            $store->remarks = empty($data['remarks']) ? '' : $data['remarks'];

            $store->date_format = $data['date_format'];
            $store->time_format = $data['time_format'];
            $store->thousand_separator = $data['thousand_separator'];
            $store->decimal_separator = $data['decimal_separator'];
            $store->decimal_digit = $data['decimal_digit'];
            $store->ribbon = $data['ribbon'];

            $store->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        };

        return response()->json();
    }

    public function delete($id)
    {
        Log::debug('[CompanyServiceImpl@delete] $id:' . $id);

        $company = Company::find($id);

        Validator::extend('isdefault', function ($field, $value, $parameters) {
            return $value == 'YESNOSELECT.YES' ? false : true;
        });

        $inputs = array(
            'is_default' => $company->is_default
        );

        $rules = array('is_default' => 'isdefault');

        $messages = array(
            'isdefault' => LaravelLocalization::getCurrentLocale() == 'en' ? 'Default Company cannot be deleted':'Toko Utama tidak bisa di hapus'
        );

        $validator = Validator::make($inputs, $rules, $messages);

        if ($validator->fails()) {
            return redirect(route('db.admin.store'))->withErrors($validator);
        } else {
            $company->delete();
        }

        return redirect(route('db.admin.store'));
    }

    public function createDefaultCompany($companyName)
    {
        $company = Company::create([
            'name' => $companyName,
            'tax_id' => '0000000000',
            'status' => Config::get('lookup.VALUE.STATUS.ACTIVE'),
            'is_default' => Config::get('lookup.VALUE.YESNOSELECT.YES'),
            'frontweb' => Config::get('lookup.VALUE.YESNOSELECT.YES'),
            'date_format' => Config::get('const.DATETIME_FORMAT.PHP_DATE'),
            'time_format' => Config::get('const.DATETIME_FORMAT.PHP_TIME'),
            'thousand_separator' => Config::get('const.DIGIT_GROUP_SEPARATOR'),
            'decimal_separator' => Config::get('const.DECIMAL_SEPARATOR'),
            'decimal_digit' => Config::get('const.DECIMAL_DIGIT'),
        ]);

        return $company->id;
    }

    public function setDefaultCompany($id)
    {
        $company = Company::find($id);

        $this->resetIsDefault();

        $company->is_default = Config::get('lookup.VALUE.YESNOSELECT.YES');
        $company->save();

    }

    public function isEmptyCompanyTable()
    {
        if (Company::count() == 0) return true;
        else return false;
    }

    public function defaultStorePresent()
    {
        $company = $this->getDefaultStore();

        if (!is_null($company)) return true;
        else return false;
    }

    public function getDefaultCompany()
    {
        return Store::whereIsDefault(Config::get('lookup.VALUE.YESNOSELECT.YES'))->get()->first();
    }
}
