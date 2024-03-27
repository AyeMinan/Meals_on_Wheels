<?php
namespace Modules\Partner\App\Interface;

interface PartnerInterface
{
    public function all();
    public function getById($id);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
}
