<?php
class Users
{
    public function index()
    {
        echo "users index";
    }

    public function show($user = null)
    {
        echo $user;
    }

    public function create()
    {
        print_r($_POST);
    }
}
?>