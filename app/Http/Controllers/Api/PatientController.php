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
        try {
            $validator = Validator::make($request->all(), [
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:patients,email',
                'phone' => 'required|string|max:20',
                'photo' => 'required|image|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validation_error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $path = $request->file('photo')->store('documents', 'public');

            $patient = Patient::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'document_photo_path' => $path,
            ]);

            Mail::to($patient->email)->queue(new \App\Mail\PatientRegistered($patient));

            return response()->json([
                'message' => 'Patient registered successfully'
            ], 201);

        } catch (\Throwable $e) {
            \Log::error('Error en registro de paciente: '.$e->getMessage());
            return response()->json([
                'error' => 'Server error',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

}
