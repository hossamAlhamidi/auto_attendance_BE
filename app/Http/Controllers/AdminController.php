<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated_id = htmlspecialchars($id);

        $admin = Admin::Where( 'admin_id', $validated_id )->first();

        if(count($admin->get())==0){
            return response([
                'message'=>'No admin found by This ID'
            ],401);
        }

        $admin->update($request->all());

        $response = [
            'admin_id' => $admin['admin_id'],
            'admin_name' => $admin['admin_name'],
            'email' => $admin['email'],
            'phone_number' => $admin['phone_number']
        ];

        return response($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
