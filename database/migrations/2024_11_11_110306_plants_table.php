<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id('plant_id');
            $table->string('name', 100);
            $table->foreignId('type_id')->nullable()->constrained('plants_types')->onDelete('set null');
            $table->softDeletes();
        });
        DB::table('plants')->insert([
            ['plant_id' => 1, 'name' => 'Cactus', 'type_id' => 9],
            ['plant_id' => 2, 'name' => 'Saguaro', 'type_id' => 9],
            ['plant_id' => 3, 'name' => 'Prickly Pear', 'type_id' => 9],
            ['plant_id' => 4, 'name' => 'Opuntia', 'type_id' => 9],
            ['plant_id' => 5, 'name' => 'Cholla', 'type_id' => 9],
            ['plant_id' => 6, 'name' => 'Asparagus Fern', 'type_id' => 6],
            ['plant_id' => 7, 'name' => 'Boston Fern', 'type_id' => 6],
            ['plant_id' => 8, 'name' => 'Maidenhair Fern', 'type_id' => 6],
            ['plant_id' => 9, 'name' => 'Bracken Fern', 'type_id' => 6],
            ['plant_id' => 10, 'name' => 'Aloe Vera', 'type_id' => 9],
            ['plant_id' => 11, 'name' => 'Bamboo', 'type_id' => 6],
            ['plant_id' => 12, 'name' => 'Daisy', 'type_id' => 15],
            ['plant_id' => 13, 'name' => 'Rose', 'type_id' => 15],
            ['plant_id' => 14, 'name' => 'Tulip', 'type_id' => 15],
            ['plant_id' => 15, 'name' => 'Sunflower', 'type_id' => 15],
            ['plant_id' => 16, 'name' => 'Lotus', 'type_id' => 10],
            ['plant_id' => 17, 'name' => 'Water Lily', 'type_id' => 10],
            ['plant_id' => 18, 'name' => 'Iris', 'type_id' => 10],
            ['plant_id' => 19, 'name' => 'Water Hyacinth', 'type_id' => 10],
            ['plant_id' => 20, 'name' => 'Mint', 'type_id' => 3],
            ['plant_id' => 21, 'name' => 'Basil', 'type_id' => 3],
            ['plant_id' => 22, 'name' => 'Thyme', 'type_id' => 3],
            ['plant_id' => 23, 'name' => 'Oregano', 'type_id' => 3],
            ['plant_id' => 24, 'name' => 'Lavender', 'type_id' => 3],
            ['plant_id' => 25, 'name' => 'Rosemary', 'type_id' => 3],
            ['plant_id' => 26, 'name' => 'Wisteria', 'type_id' => 4],
            ['plant_id' => 27, 'name' => 'Clematis', 'type_id' => 4],
            ['plant_id' => 28, 'name' => 'Honeysuckle', 'type_id' => 4],
            ['plant_id' => 29, 'name' => 'Ivy', 'type_id' => 4],
            ['plant_id' => 30, 'name' => 'Morning Glory', 'type_id' => 4],
            ['plant_id' => 31, 'name' => 'Sweet Pea', 'type_id' => 4],
            ['plant_id' => 32, 'name' => 'Climbing Rose', 'type_id' => 4],
            ['plant_id' => 33, 'name' => 'Pine', 'type_id' => 1],
            ['plant_id' => 34, 'name' => 'Oak', 'type_id' => 1],
            ['plant_id' => 35, 'name' => 'Maple', 'type_id' => 1],
            ['plant_id' => 36, 'name' => 'Fir', 'type_id' => 1],
            ['plant_id' => 37, 'name' => 'Cedar', 'type_id' => 1],
            ['plant_id' => 38, 'name' => 'Birch', 'type_id' => 1],
            ['plant_id' => 39, 'name' => 'Pomegranate', 'type_id' => 5],
            ['plant_id' => 40, 'name' => 'Grapevine', 'type_id' => 5],
            ['plant_id' => 41, 'name' => 'Goji Berry', 'type_id' => 5],
            ['plant_id' => 42, 'name' => 'Kiwi', 'type_id' => 5],
            ['plant_id' => 43, 'name' => 'Jasmine', 'type_id' => 5],
            ['plant_id' => 44, 'name' => 'Cucumber', 'type_id' => 5],
            ['plant_id' => 45, 'name' => 'Strawberry', 'type_id' => 5],
            ['plant_id' => 46, 'name' => 'Kale', 'type_id' => 3],
            ['plant_id' => 47, 'name' => 'Spinach', 'type_id' => 3],
            ['plant_id' => 48, 'name' => 'Lettuce', 'type_id' => 3],
            ['plant_id' => 49, 'name' => 'Carrot', 'type_id' => 3],
            ['plant_id' => 50, 'name' => 'Beetroot', 'type_id' => 3],
            ['plant_id' => 51, 'name' => 'Wheatgrass', 'type_id' => 7],
            ['plant_id' => 52, 'name' => 'Barley', 'type_id' => 7],
            ['plant_id' => 53, 'name' => 'Ryegrass', 'type_id' => 7],
            ['plant_id' => 54, 'name' => 'Fescue', 'type_id' => 7],
            ['plant_id' => 55, 'name' => 'Bermuda Grass', 'type_id' => 7],
            ['plant_id' => 56, 'name' => 'Zoysia', 'type_id' => 7],
            ['plant_id' => 57, 'name' => 'Holly', 'type_id' => 1],
            ['plant_id' => 58, 'name' => 'Juniper', 'type_id' => 1],
            ['plant_id' => 59, 'name' => 'Larch', 'type_id' => 1],
            ['plant_id' => 60, 'name' => 'Cypress', 'type_id' => 1],
            ['plant_id' => 61, 'name' => 'Chili Pepper', 'type_id' => 5],
            ['plant_id' => 62, 'name' => 'Bell Pepper', 'type_id' => 5],
            ['plant_id' => 63, 'name' => 'Tomato', 'type_id' => 5],
            ['plant_id' => 64, 'name' => 'Eggplant', 'type_id' => 5],
            ['plant_id' => 65, 'name' => 'Squash', 'type_id' => 5],
            ['plant_id' => 66, 'name' => 'Zucchini', 'type_id' => 5],
            ['plant_id' => 67, 'name' => 'Cabbage', 'type_id' => 3],
            ['plant_id' => 68, 'name' => 'Broccoli', 'type_id' => 3],
            ['plant_id' => 69, 'name' => 'Cauliflower', 'type_id' => 3],
            ['plant_id' => 70, 'name' => 'Peas', 'type_id' => 3],
            ['plant_id' => 71, 'name' => 'Aubergine', 'type_id' => 5],
            ['plant_id' => 72, 'name' => 'Lily', 'type_id' => 15],
            ['plant_id' => 73, 'name' => 'Tulip', 'type_id' => 15],
            ['plant_id' => 74, 'name' => 'Dandelion', 'type_id' => 15],
            ['plant_id' => 75, 'name' => 'Magnolia', 'type_id' => 15],
            ['plant_id' => 76, 'name' => 'Violet', 'type_id' => 15],
            ['plant_id' => 77, 'name' => 'Orchid', 'type_id' => 15],
            ['plant_id' => 78, 'name' => 'Apple Tree', 'type_id' => 1],
            ['plant_id' => 79, 'name' => 'Cherry Tree', 'type_id' => 1],
            ['plant_id' => 80, 'name' => 'Plum Tree', 'type_id' => 1],
            ['plant_id' => 81, 'name' => 'Peach Tree', 'type_id' => 1],
            ['plant_id' => 82, 'name' => 'Strawberry Tree', 'type_id' => 1],
            ['plant_id' => 83, 'name' => 'Apricot Tree', 'type_id' => 1],
            ['plant_id' => 84, 'name' => 'Papaya', 'type_id' => 5],
            ['plant_id' => 85, 'name' => 'Mango', 'type_id' => 5],
            ['plant_id' => 86, 'name' => 'Pineapple', 'type_id' => 5],
            ['plant_id' => 87, 'name' => 'Banana', 'type_id' => 5],
            ['plant_id' => 88, 'name' => 'Coconut', 'type_id' => 5],
            ['plant_id' => 89, 'name' => 'Avocado', 'type_id' => 5],
            ['plant_id' => 90, 'name' => 'Lemon Tree', 'type_id' => 1],
            ['plant_id'=>91, 'name'=>'Other', 'type_id'=>16],
        ]);

    }


    public function down()
    {
        Schema::dropIfExists('plants');
    }
};
