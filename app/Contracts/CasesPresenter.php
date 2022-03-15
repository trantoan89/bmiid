<?php
namespace App\Contracts;


abstract class CasesPresenter
{
    protected $data;

    public function load($data) {
        $this->data = $data;
    }
    
    public function output() {
        return '';
    }
}