<?php

namespace App\Http\Controllers;

use App\SoftwareSetting;
use App\DeveloperSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SoftwareSettingController extends Controller
{
    public function index()
    {
        $data['settings'] = SoftwareSetting::companies()->get();
        $data['optionData'] = $this->getDynamicOptionsData();

        return view('admin.settings.software.index', $data);
    }

    public function update(Request $request)
    {
        $companyId = auth()->user()->fk_company_id;

        foreach ($request->titles as $key => $title) {
            SoftwareSetting::updateOrCreate(['fk_company_id' => $companyId, 'title' => $title], ['options' => $request->options[$key]]);
        }

        return back()->withSuccess('Setting Updated Successfully');
    }

    private function getDynamicOptionsData()
    {

        $data = [
            [
                'title'     => 'Sale Default Printing',
                'option1'   => 'pos-print',
                'option2'   => 'normal-print',
                'caption1'  => 'Pos Print',
                'caption2'  => 'Normal Print',
            ],[
                'title'     => 'Need Additional Item Id Field',
                'option1'   => 'need-additional-item-id-field',
                'option2'   => 'no',
            ],
            ['title'    =>   'Seller Sale Price Update From Sale'],
            // ['title'    =>   'Product Expire Date'],
            ['title'    =>   'Allow Sales When Stock Not Available'],
            ['title'    =>   'Product Cost Update From Purchase'],
            ['title'    =>   'Warehouse Wise Product Stock'],
            ['title'    =>   'Show Barcode in Invoice'],
            ['title'    =>   'Barcode With Company Name'],
            ['title'    =>   'Send Invoice Sms To Customer'],
            ['title'    =>   'Send Invoice Mail To Customer'],
            ['title'    =>   'Product Rak In Product'],
            ['title'    =>   'Product Code Show'],
            ['title'    =>   'Product Tax'],
            ['title'    =>   'Product Subcategory'],
            ['title'    =>   'Customer Point'],
            ['title'    =>   'Product Discount'],
            ['title'    =>   'Product Generic Name'],
            ['title'    =>   'Courier Service For Sale'],
        ];

        $developerSettings = DeveloperSetting::companies()->where('status', 1)->pluck('title')->toArray();

        if (in_array('Hole Sale Price', $developerSettings)) {
            $data[] = ['title'    =>   'Hole Sale Price'];
        }
        return $data;
    }

    public function junkClean()
    {
        Artisan::call('clear-compiled');
        Artisan::call('config:cache');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');

        return redirect()->back()->withSuccess('Application Cache Cleared');
    }
}
