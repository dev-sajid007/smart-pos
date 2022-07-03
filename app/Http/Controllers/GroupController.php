<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use App\CsvData;
use App\GroupContact;
use App\SmsQuota;
use Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::paginate(10);
        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, $id)
    {
        $group_info = Group::findOrFail($id);
        $group_contacts = GroupContact::orderBy('id', 'desc')->where('fk_group_id', $id)
            ->paginate(10);
        return view('admin.groups.show', compact([
            'group_info', 'group_contacts'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }

    //<import csv file>


    public function get_import(){
        $sms_quota = SmsQuota::where('fk_company_id', Auth::user()->fk_company_id)
            ->where('quantity', '>', 0);
        if($sms_quota->count() <= 0){
            return \redirect('groups')->with(['error_message' => 'You do not have any have sms!']);
        }

        return view('admin.groups.import');
    }

    public function parse_import(Request $request){
        $this->validate($request, [
            'name' => 'required|min:1|max:160|unique:groups',
            'group_csv' => 'required|mimes:csv,txt'
        ]);
        
        $path = $request->file('group_csv')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $count = count($data);
        $csv_data = array_slice($data, 1, $count);

        $csv_data_file = CsvData::create([
            'csv_file_name' => $request->file('group_csv')->getClientOriginalName(),
            'csv_header' => 1,
            'csv_data' => json_encode($csv_data)
        ]);


           
        $group = new Group();
        $group->fk_company_id = $request->fk_company_id;
        $group->fk_created_by = $request->fk_created_by;
        $group->fk_updated_by = $request->fk_updated_by;
        $group_max_id = Group::max('id')+1;
        $group_code_db = 'group-'.$group_max_id;
        $group->group_code = $group_code_db;
        $group->name = $request->name;
        $group->group_csv = $request->file('group_csv')->getClientOriginalName();
        $group->save();

        return view('admin.groups.import_fields', [
                'csv_data'=>$csv_data, 
                'csv_data_file'=>$csv_data_file,
                'group_id_db' => $group->id
                ]);

    }

    public function process_import(Request $request){
     
        
        $data = CsvData::findOrFail($request->csv_file_id);
        $csv_data = json_decode($data->csv_data, true);

        foreach($csv_data as $row){
            $group_contact = new GroupContact();

            $duplicate_error = 0;

            $group_contact->fk_company_id = $request->fk_company_id;
            $group_contact->fk_created_by = $request->fk_created_by;
            $group_contact->fk_updated_by = $request->fk_updated_by;
            $group_contact->fk_group_id = $request->group_id_db;

            foreach(config('app.import_fields_group') as $index => $field){
                $group_contact->$field = $row[$index];
                
            }
            
            if($this->check_duplicate($group_contact->phone, $request->group_id_db) == TRUE){
                $duplicate_error += 1;
            }else{
                $group_contact->save();
            }
            
            

        }

        if($duplicate_error >= 1){
            return \redirect('groups/get_import')->with(['error'=>'Duplicate Data Found!']);
        }else{
            return  \redirect('groups/get_import')->with(['success'=>'Data imported succesfully!']);
        }
        
            


    }


    function check_duplicate($group_phone, $group_id_db){
        $duplicate_count = GroupContact::where([
            'phone' => $group_phone,
            'fk_group_id' => $group_id_db
        ])->get()->count();
        if($duplicate_count>=1){
            return TRUE;
        }else{
            return FALSE;
        }

    }
    
    public function download_sample(){
        $this->download_sample('group');
    }
    // </import>


}
