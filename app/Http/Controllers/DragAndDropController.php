<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DragAndDropController extends Controller
{
    public function index()
    {
        return view('drag-and-drop');
    }

    public function saveForm(Request $request)
    {
        // Handle form submission logic here
        // You can access form elements using $request->input('element_name')

        return response()->json(['message' => 'Form submitted successfully']);
    }
}
