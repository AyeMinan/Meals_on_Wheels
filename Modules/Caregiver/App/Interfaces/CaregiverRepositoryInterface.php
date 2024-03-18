<?php

namespace Modules\Caregiver\App\Interfaces;

use Illuminate\Http\Request;

interface CaregiverRepositoryInterface{
    public function storeCaregiver($validatedData);

    public function updateCaregiver(Request $request, $id);

    public function deleteCaregiver($id);
}
