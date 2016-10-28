@extends('layouts.app')

@section('title', 'Item')

@section('content')
<div class="container">
    <div class="item-carousel row">
        <div class="col-md-12">
            <img src="/images/carousel-placeholder.png" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Item Description</div>

                <div class="panel-body">
                    <table class="item-info">
                        <tbody>
                            <tr><td>Price</td><td>$15.00</td></tr>
                            <tr><td>Description</td><td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut faucibus sapien massa, quis hendrerit nunc vulputate non. Vivamus congue odio a mi ullamcorper, quis aliquet orci semper. Curabitur finibus cursus ligula id faucibus. Curabitur ut faucibus lectus, nec blandit augue. Cras in magna tellus. Donec imperdiet euismod justo vitae varius.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">User Name</div>

                <div class="panel-body">
                    <table class="item-user-info">
                        <tbody>
                            <tr><td>Rating</td><td>4.5 / 5.0</td></tr>
                            <tr><td>Info</td><td>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</td></tr>
                        </tbody>
                    </table>
                    <img class="item-user-profile-image" src="/images/user-profile-image-placeholder.jpeg" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
