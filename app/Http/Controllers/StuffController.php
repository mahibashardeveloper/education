<?php

namespace App\Http\Controllers;

use App\Models\Stuff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StuffController extends BaseController
{
    public static function list (Request $request)
    {
        try {
            $admin_id = Auth::guard('admins')->id();
            $limit = $request->limit ?? 10;
            $keyword = $request->keyword ?? '';
            $stuff = Stuff::with('department_info')->where('admin_id', $admin_id)->orderby('id', 'asc');

            if (isset($keyword) && !empty($keyword)) {
                $stuff->where(
                    function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', '%' . $keyword . '%');
                        $q->orWhere('email', 'LIKE', '%' . $keyword . '%');
                        $q->orWhere('phone', 'LIKE', '%' . $keyword . '%');
                    }
                );
            }
            $stuff = $stuff->paginate($limit);

            return ['message' => 'Show event list data successfully' ,'data' => $stuff];
        } catch (\Exception $e) {
            return ['status' => 500, 'errors' => $e->getMessage(), 'line' => $e->getLine()];
        }
    }

    public static function create (Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'joining_date' => 'required',
                    'department_id' => 'required',
                    'phone' => 'required|string',
                    'description' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                return ['status' => 500, 'errors' => $validator->errors()];
            }
            $stuff = new Stuff();
            $admin_id = Auth::guard('admins')->id();
            $stuff->name = $request->name;
            $stuff->email = $request->email;
            $stuff->joining_date = $request->joining_date;
            $stuff->department_id = $request->department_id;
            $stuff->phone = $request->phone;
            $stuff->description = $request->description;
            $stuff->admin_id = $admin_id;
            $stuff->save();
            return ['message' => 'Stuff has been saved successfully.'];
        } catch (\Exception $e) {
            return ['status' => 500, 'errors' => $e->getMessage(), 'line' => $e->getLine()];
        }
    }

    public static function single(Request $request) {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'id' => 'required',
                ]
            );

            if ($validator->fails()) {
                return ['status' => 500, 'errors' => $validator->errors()];
            }
            $admin_id = Auth::guard('admins')->id();
            $stuff = Stuff::where('id', $request->id)->where('admin_id', $admin_id)->first();
            if($stuff == null){
                return ['status' => 500, 'errors' => 'Stuff not found'];
            }
            return ['message' => 'Show single data successfully','data' => $stuff];
        } catch (\Exception $e) {
            return ['status' => 500, 'errors' => $e->getMessage(), 'line' => $e->getLine()];
        }
    }

    public static function update(Request $request) {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'id' => 'required',
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'joining_date' => 'required',
                    'department_id' => 'required',
                    'phone' => 'required|string',
                    'description' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                return ['status' => 500, 'errors' => $validator->errors()];
            }
            $admin_id = Auth::guard('admins')->id();
            $stuff = Stuff::where('id', $request->id)->where('admin_id', $admin_id)->first();
            if($stuff == null){
                return ['status' => 500, 'errors' => 'Stuff not found'];
            }
            $stuff->name = $request->name;
            $stuff->email = $request->email;
            $stuff->joining_date = $request->joining_date;
            $stuff->department_id = $request->department_id;
            $stuff->phone = $request->phone;
            $stuff->description = $request->description;
            $stuff->save();
            return ['message' => 'Stuff has been updated successfully.'];
        } catch (\Exception $e) {
            return ['status' => 500, 'errors' => $e->getMessage(), 'line' => $e->getLine()];
        }
    }

    public static function delete(Request $request)
    {

        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'ids' => 'required|array',
                ]
            );

            if ($validator->fails()) {
                return ['status' => 500, 'errors' => $validator->errors()];
            }
            $admin_id = Auth::guard('admins')->id();
            Stuff::whereIn('id', $request->ids)->where('admin_id', $admin_id)->delete();
            return ['message' => 'Stuff has been deleted successfully'];
        } catch (\Exception $e) {
            return ['status' => 500, 'errors' => $e->getMessage(), 'line' => $e->getLine()];
        }
    }

    public static function stuffList(Request $request)
    {
        try {
            $limit = $request->limit ?? 10;
            $keyword = $request->keyword ?? '';
            $stuffs = Stuff::orderby('id', 'asc');

            if (isset($keyword) && !empty($keyword)) {
                $stuffs->where(
                    function ($q) use ($keyword) {
                        $q->where('title', 'LIKE', '%' . $keyword . '%');
                        $q->orWhere('author_name', 'LIKE', '%' . $keyword . '%');
                        $q->orWhere('description', 'LIKE', '%' . $keyword . '%');
                    }
                );
            }
            $stuffs = $stuffs->paginate($limit);

            return ['message' => 'Show all data list successfully.' ,'data' => $stuffs];
        } catch (\Exception $e) {
            return ['status' => 500, 'errors' => $e->getMessage(), 'line' => $e->getLine()];
        }
    }

    public static function stuffDetails(Request $request) {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'id' => 'required',
                ]
            );

            if ($validator->fails()) {
                return ['status' => 500, 'errors' => $validator->errors()];
            }
            $stuff = Stuff::where('id', $request->id)->first();
            if($stuff == null){
                return ['status' => 500, 'errors' => 'Stuff details data not found.'];
            }
            return ['message' => 'Show single data successfully','data' => $stuff];
        } catch (\Exception $e) {
            return ['status' => 500, 'errors' => $e->getMessage(), 'line' => $e->getLine()];
        }
    }
}
