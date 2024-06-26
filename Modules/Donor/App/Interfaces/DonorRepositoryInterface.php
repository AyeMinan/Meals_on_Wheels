<?php

namespace Modules\Donor\App\Interfaces;
use Illuminate\Http\Request;
interface DonorRepositoryInterface {

    public function allDonor();
    public function storeDonor(Request $request, $validatedData);
    public function deleteDonor($id);

    public function updateDonor(Request $request, $id);
}
