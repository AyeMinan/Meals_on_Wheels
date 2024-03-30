<?php

namespace Modules\Caregiver\App\Interfaces;

use Illuminate\Http\Request;

interface CaregiverRepositoryInterface{

    public function getAllCaregivers();
    public function storeCaregiver(Request $request, $validatedData);

    public function updateCaregiver(Request $request, $id);

    public function deleteCaregiver($id);
}
