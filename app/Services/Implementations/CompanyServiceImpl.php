<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/22/2018
 * Time: 12:50 AM
 */

namespace App\Services\Implementations;

use App\Models\Company;
use App\Models\BankAccount;

use App\Services\CompanyService;

use Log;
use Config;
use LaravelLocalization;
use Intervention\Image\Facades\Image;

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
        $bank,
        $date_format,
        $time_format,
        $thousand_separator,
        $decimal_separator,
        $decimal_digit,
        $ribbon
    )
    {
        Log::debug('[CompanyServiceImpl@create] ');

        $imageName = '';

        if (!empty($image_path)) {
            $imageName = time() . '.' . $image_path->getClientOriginalExtension();
            $path = public_path('images') . '/' . $imageName;

            Image::make($image_path->getRealPath())->resize(160, 160)->save($path);
        }

        if ($is_default == Config::get('lookup.VALUE.YESNOSELECT.YES')) {
            $this->resetIsDefault();
        }

        if ($frontweb == Config::get('lookup.VALUE.YESNOSELECT.YES')) {
            $this->resetFrontWeb();
        }

        $company = Company::create([
            'name' => $name,
            'address' => $address,
            'latitude' => empty($latitude) ? 0:$latitude,
            'longitude' => empty($longitude) ? 0:$longitude,
            'phone_num' => $phone_num,
            'fax_num' => $fax_num,
            'tax_id' => $tax_id,
            'status' => $status,
            'is_default' => $is_default,
            'frontweb' => $frontweb,
            'image_filename' => $imageName,
            'remarks' => empty($remarks) ? '' : $remarks,
            'date_format' => $date_format,
            'time_format' => $time_format,
            'thousand_separator' => $thousand_separator,
            'decimal_separator' => $decimal_separator,
            'decimal_digit' => $decimal_digit,
            'ribbon' => $ribbon,
        ]);

        for ($i = 0; $i < count($bank); $i++) {
            $ba = new BankAccount();
            $ba->bank_id = $bank[$i]["bank_id"];
            $ba->account_name = $bank[$i]["account_name"];
            $ba->account_number = $bank[$i]["account_number"];
            $ba->remarks = $bank[$i]["bank_remarks"];

            $company->bankAccounts()->save($ba);
        }
    }

    public function read()
    {
        return Company::with('bankAccounts.bank')->get();
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
        $bank,
        $date_format,
        $time_format,
        $thousand_separator,
        $decimal_separator,
        $decimal_digit,
        $ribbon
    )
    {
        Log::debug('[CompanyServiceImpl@update] $id:' . $id);

        $company = Company::find($id);

        $imageName = '';

        if (!empty($company ->image_filename)) {
            if (!empty($image_path)) {
                $imageName = time() . '.' . $image_path->getClientOriginalExtension();
                $path = public_path('images') . '/' . $imageName;

                Image::make($image_path->getRealPath())->resize(160, 160)->save($path);
            } else {
                $imageName = $company->image_filename;
            }
        } else {
            if (!empty($image_path)) {
                $imageName = time() . '.' . $image_path->getClientOriginalExtension();
                $path = public_path('images') . '/' . $imageName;

                Image::make($image_path->getRealPath())->resize(160, 160)->save($path);
            } else {
                $imageName = '';
            }
        }

        if ($company->is_default == Config::get('lookup.VALUE.YESNOSELECT.NO') && $is_default == Config::get('lookup.YESNOSELECT.YES')) {
            $this->resetIsDefault();
        }

        if ($company->frontweb == Config::get('lookup.VALUE.YESNOSELECT.NO') && $frontweb == Config::get('lookup.VALUE.YESNOSELECT.YES')) {
            $this->resetFrontWeb();
        }

        $company->bankAccounts->each(function($ba) { $ba->delete(); });

        for ($i = 0; $i < count($bank); $i++) {
            $ba = new BankAccount();
            $ba->bank_id = $bank[$i]["bank_id"];
            $ba->account_name = $bank[$i]["account_name"];
            $ba->account_number = $bank[$i]["account_number"];
            $ba->remarks = $bank[$i]["bank_remarks"];

            $company->bankAccounts()->save($ba);
        }

        $company->name = $name;
        $company->address = $address;
        $company->latitude = empty($latitude) ? 0:$latitude;
        $company->longitude = empty($longitude) ? 0:$longitude;
        $company->phone_num = $phone_num;
        $company->fax_num = $fax_num;
        $company->tax_id = $tax_id;
        $company->status = $status;
        $company->is_default = $is_default;
        $company->image_filename = $imageName;
        $company->frontweb = $frontweb;
        $company->remarks = empty($remarks) ? '' : $remarks;

        $company->date_format = $date_format;
        $company->time_format = $time_format;
        $company->thousand_separator = $thousand_separator;
        $company->decimal_separator = $decimal_separator;
        $company->decimal_digit = $decimal_digit;
        $company->ribbon = $ribbon;

        $company->save();
    }

    public function delete($id)
    {
        Log::debug('[CompanyServiceImpl@delete] $id:' . $id);

        $company = Company::find($id);

        if ($company->is_default == Config::get('lookup.VALUE.YESNOSELECT.YES')) {
            throw new Exception(
                LaravelLocalization::getCurrentLocale() == 'en' ? 'Default Store cannot be deleted':'Toko Utama tidak bisa di hapus'
            );
        } else {
            $company->delete();
        }
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
        $company = $this->getDefaultCompany();

        if (!is_null($company)) return true;
        else return false;
    }

    public function getDefaultCompany()
    {
        return Company::whereIsDefault(Config::get('lookup.VALUE.YESNOSELECT.YES'))->get()->first();
    }

    public function resetIsDefault()
    {
        $company = Company::whereIsDefault(Config::get('lookup.VALUE.YESNOSELECT.YES'))->get();

        foreach ($company as $s) {
            $s->is_default = Config::get('lookup.VALUE.YESNOSELECT.NO');
            $s->save();
        }
    }

    public function resetFrontWeb()
    {
        Log::debug('[CompanyServiceImpl@CresetFrontWeb] ');

        $comp = Company::whereFrontweb(Config::get('lookup.VALUE.YESNOSELECT.YES'))->get();

        foreach ($comp as $c) {
            $c->frontweb = Config::get('lookup.VALUE.YESNOSELECT.NO');
            $c->save();
        }
    }
}
