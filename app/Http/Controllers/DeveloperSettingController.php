<?php

namespace App\Http\Controllers;

use App\DeveloperSetting;
use Illuminate\Http\Request;

class DeveloperSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['settings'] = DeveloperSetting::companies()->get();
        $data['optionData'] = $this->getDynamicOptionsData();

        return view('admin.settings.developer.index', $data);
    }

    public function update(Request $request)
    {
        $companyId = auth()->user()->fk_company_id;

        foreach ($request->titles as $key => $title) {
            DeveloperSetting::updateOrCreate(['company_id' => $companyId, 'title' => $title], ['status' => $request->statuses[$key]]);
        }

        return back()->withSuccess('Setting Updated Successfully');
    }

    private function getDynamicOptionsData()
    {
        return [
            ['title' => 'Hole Sale Price'],
        ];
    }

}
