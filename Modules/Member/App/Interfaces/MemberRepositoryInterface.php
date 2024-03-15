<?php

namespace Modules\Member\App\Interfaces;

use Illuminate\Http\Request;

interface MemberRepositoryInterface{
    public function storeMember($validatedData);

    public function updateMember(Request $request, $id);

    public function deleteMember($validatedData);
}
