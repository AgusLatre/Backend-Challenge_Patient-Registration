<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Patient;
use App\Mail\PatientRegistered;
use Illuminate\Support\Facades\Mail;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email',
            'phone' => 'required|string|max:20',
            'photo' => 'required|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $path = $request->file('photo')->store('documents', 'public');

        $patient = Patient::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'document_photo_path' => $path,
        ]);

        // Send confirmation email asynchronously
        Mail::to($patient->email)->queue(new PatientRegistered($patient));

        return response()->json(['message' => 'Patient registered successfully'], 201);
    }
}
