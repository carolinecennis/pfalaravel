<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 5/5/16
 * Time: 1:44 PM
 */

namespace App\Models\Neo;

class Person extends \NeoEloquent {

    protected $label = 'Person'; // or array('User', 'Fan')

    protected $fillable = ['name', 'email'];
}

//$user = Person::create(['name' => 'Some Name', 'email' => 'some@email.com']);

