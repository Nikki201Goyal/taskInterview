<?php

namespace App\Http\Controllers;

use App\Models\userDetails;
use DateTime;
use Illuminate\Http\Request;


class UserController extends Controller
{

    public function viewUser()
    {
        $users = userDetails::OrderBy('created_at', 'DESC')->get();

        // dd($days);
        // dd($terminate);
        return view('Backend.user.show', compact('users'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'image' => 'required',
            'email' => 'required',
            'joiningDate' => 'required',
            'name' => 'required',
            'terminationDate' => 'after:joiningDate',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . $image->getClientOriginalName();
            $image->move('Uploads/', $imageName);
        }
        $creat = new UserDetails();
        $creat->image = 'Uploads/' . $imageName;
        $creat->name   = $request->name;
        $creat->email   = $request->email;
        $creat->joiningDate = $request->joiningDate;
        $creat->terminationDate = $request->terminationDate;

        $creat->save();
        return redirect()->route('viewUser')->with('success', 'You have successfully added');
    }


    public function delete($id)
    {
        UserDetails::find($id)->delete();
        return redirect()->route('viewUser')->with([
            'success' => 'Deleted successfully'
        ]);
    }
}
