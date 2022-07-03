<?php

namespace App\Http\Controllers;

use App\Party;
use Illuminate\Http\Request;
use Auth;

class PartyController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parties = Party::companies()->userLog();

        if ($request->ajax()){
            return $parties->where('name', 'LIKE', "%{$request->name}%")
                    ->pluck('name', 'id');
        }

        return view('admin.account.parties.index', [
            'parties' => $parties->paginate(30)

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.account.parties.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:160|unique:parties',
            'phone' => 'required|unique:parties',
            'email' => 'required|max:160|unique:parties|email',
            'address' => 'required'
        ]);

        Party::create($request->except('_token'));

        return \redirect()->route('parties.index')->withSuccess('Party Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $party = $this->get_party($id);
        return view('admin.account.parties.show', ['party'=>$party]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function edit(Party $party)
    {
        return view('admin.account.parties.edit', ['party' => $party]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Party $party)
    {
        $this->validate($request, [
            'name' => 'required|max:160|unique:parties,name,'.$party->id,
            'phone' => 'required|unique:parties,phone,'.$party->id,
            'email' => 'required|max:160|unique:parties,email,'.$party->id,
            'address' => 'required'
        ]);

        $party->update($request->except(['_token', 'fk_created_by']));

        return redirect()->route('parties.index')->withSuccess('Party Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Party  $party
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $party = $this->get_party($id);
        try{
            $party->delete();
        }catch (\Exception $e){
            return redirect()->back()->with('error_message', "This Company is use another table you cannot delete it!");
        }

        return redirect()->route('parties.index')->with(['message'=>'Party Deleted Successfully!']);
    }

    public function get_party($id)
    {
        return Party::findOrFail($id);
    }
}
