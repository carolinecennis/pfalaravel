<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
    return [
        'id' => random_int(1,99999999999),
        'name' => $faker->name,
        'parentId' => $faker->parentId,
    ];
});

$factory->define(App\Models\UserBio::class, function (Faker\Generator $faker) {

//    $image_ids = \DB::table('images')->select('id')->get();
//    $image_id = $faker->randomElement($image_ids)->id;

    return [
            'user_id' => factory(App\Models\User::class)->create()->id,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'username' => $faker->userName,
            'birthdate' => $faker->datetime,
            'join_date' => $faker->datetime,
            'user_descr' => str_random(10),
            'image_id' => null,
            'interested_in' => $faker->sentence(6),
            'addr1' => $faker->streetAddress,
            'addr2' => $faker->buildingNumber,
            'city' => $faker->city,
            'state' => str_random(2),
            'zip' => $faker->postcode,
            'trade_count' => random_int(1,50),
            'rating' => random_int(1,50)
    ];
});

$factory->define(App\Models\Listing::class, function (Faker\Generator $faker) {

    $user_ids = \DB::table('users')->select('id')->get();
    $user_id = $faker->randomElement($user_ids)->id;

    $category_ids = \DB::table('categories')->select('id')->get();
    $category_id = $faker->randomElement($category_ids)->id;

    return [
        'name' => $faker->sentence(3),
        'value' => $faker->randomFloat,
        'item_descr' => $faker->sentence(6),
        'user_id' => $user_id,
        'category_id' => $category_id
    ];
});

$factory->define(App\Models\Image::class, function (Faker\Generator $faker) {

    $user_ids = \DB::table('users')->select('id')->get();
    $user_id = $faker->randomElement($user_ids)->id;

    $listing_ids = \DB::table('listings')->select('id')->get();
    $listing_id = $faker->randomElement($listing_ids)->id;

    return [
        'image_type' => "item",
        'title' => $faker->sentence(3),
        'filename' => $faker->name + $faker->fileExtension,
        'path' => str_random(10),
        'author_id' => $user_id,
        'listing_id' => $listing_id,
        'user_id' => $user_id,
        'size' => random_int(1,10),
        'width' => random_int(1,10),
        'height' => random_int(1,10)
    ];
});


$factory->define(App\Models\Neo\Person::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});