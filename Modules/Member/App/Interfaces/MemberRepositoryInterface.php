<?php

namespace Modules\Member\App\Interfaces;

use Illuminate\Http\Request;

interface MemberRepositoryInterface{
    public function storeMember(Request $request, $validatedData);

    public function updateMember(Request $request, $id);

    public function deleteMember($validatedData);
}
