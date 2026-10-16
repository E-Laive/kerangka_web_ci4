<?php
namespace App\Controllers;
class Home extends BaseController
{
    public function index() { return redirect()->to(site_url(service('authService')->principal() ? 'dashboard' : 'login')); }
}
