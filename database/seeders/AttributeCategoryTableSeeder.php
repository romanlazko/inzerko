<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AttributeCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('attribute_category')->delete();
        
        \DB::table('attribute_category')->insert(array (
            0 => 
            array (
                'id' => 2,
                'attribute_id' => 2,
                'category_id' => 1,
            ),
            1 => 
            array (
                'id' => 4,
                'attribute_id' => 4,
                'category_id' => 1,
            ),
            2 => 
            array (
                'id' => 5,
                'attribute_id' => 5,
                'category_id' => 1,
            ),
            3 => 
            array (
                'id' => 8,
                'attribute_id' => 8,
                'category_id' => 2,
            ),
            4 => 
            array (
                'id' => 9,
                'attribute_id' => 9,
                'category_id' => 2,
            ),
            5 => 
            array (
                'id' => 10,
                'attribute_id' => 10,
                'category_id' => 2,
            ),
            6 => 
            array (
                'id' => 11,
                'attribute_id' => 11,
                'category_id' => 2,
            ),
            7 => 
            array (
                'id' => 12,
                'attribute_id' => 12,
                'category_id' => 2,
            ),
            8 => 
            array (
                'id' => 13,
                'attribute_id' => 13,
                'category_id' => 2,
            ),
            9 => 
            array (
                'id' => 14,
                'attribute_id' => 14,
                'category_id' => 2,
            ),
            10 => 
            array (
                'id' => 15,
                'attribute_id' => 15,
                'category_id' => 2,
            ),
            11 => 
            array (
                'id' => 16,
                'attribute_id' => 16,
                'category_id' => 2,
            ),
            12 => 
            array (
                'id' => 17,
                'attribute_id' => 2,
                'category_id' => 4,
            ),
            13 => 
            array (
                'id' => 21,
                'attribute_id' => 2,
                'category_id' => 3,
            ),
            14 => 
            array (
                'id' => 25,
                'attribute_id' => 21,
                'category_id' => 3,
            ),
            15 => 
            array (
                'id' => 26,
                'attribute_id' => 22,
                'category_id' => 5,
            ),
            16 => 
            array (
                'id' => 27,
                'attribute_id' => 23,
                'category_id' => 5,
            ),
            17 => 
            array (
                'id' => 28,
                'attribute_id' => 24,
                'category_id' => 5,
            ),
            18 => 
            array (
                'id' => 29,
                'attribute_id' => 25,
                'category_id' => 5,
            ),
            19 => 
            array (
                'id' => 31,
                'attribute_id' => 27,
                'category_id' => 1,
            ),
            20 => 
            array (
                'id' => 32,
                'attribute_id' => 27,
                'category_id' => 3,
            ),
            21 => 
            array (
                'id' => 33,
                'attribute_id' => 27,
                'category_id' => 4,
            ),
            22 => 
            array (
                'id' => 34,
                'attribute_id' => 6,
                'category_id' => 6,
            ),
            23 => 
            array (
                'id' => 44,
                'attribute_id' => 28,
                'category_id' => 3,
            ),
            24 => 
            array (
                'id' => 45,
                'attribute_id' => 4,
                'category_id' => 4,
            ),
            25 => 
            array (
                'id' => 46,
                'attribute_id' => 5,
                'category_id' => 4,
            ),
            26 => 
            array (
                'id' => 47,
                'attribute_id' => 29,
                'category_id' => 1,
            ),
            27 => 
            array (
                'id' => 51,
                'attribute_id' => 29,
                'category_id' => 8,
            ),
            28 => 
            array (
                'id' => 52,
                'attribute_id' => 6,
                'category_id' => 8,
            ),
            29 => 
            array (
                'id' => 53,
                'attribute_id' => 6,
                'category_id' => 27,
            ),
            30 => 
            array (
                'id' => 56,
                'attribute_id' => 14,
                'category_id' => 18,
            ),
            31 => 
            array (
                'id' => 57,
                'attribute_id' => 16,
                'category_id' => 18,
            ),
            32 => 
            array (
                'id' => 58,
                'attribute_id' => 9,
                'category_id' => 18,
            ),
            33 => 
            array (
                'id' => 59,
                'attribute_id' => 10,
                'category_id' => 18,
            ),
            34 => 
            array (
                'id' => 60,
                'attribute_id' => 11,
                'category_id' => 18,
            ),
            35 => 
            array (
                'id' => 61,
                'attribute_id' => 12,
                'category_id' => 18,
            ),
            36 => 
            array (
                'id' => 62,
                'attribute_id' => 13,
                'category_id' => 18,
            ),
            37 => 
            array (
                'id' => 63,
                'attribute_id' => 15,
                'category_id' => 18,
            ),
            38 => 
            array (
                'id' => 64,
                'attribute_id' => 6,
                'category_id' => 28,
            ),
            39 => 
            array (
                'id' => 65,
                'attribute_id' => 6,
                'category_id' => 29,
            ),
            40 => 
            array (
                'id' => 67,
                'attribute_id' => 30,
                'category_id' => 18,
            ),
            41 => 
            array (
                'id' => 69,
                'attribute_id' => 31,
                'category_id' => 18,
            ),
            42 => 
            array (
                'id' => 71,
                'attribute_id' => 6,
                'category_id' => 1,
            ),
            43 => 
            array (
                'id' => 72,
                'attribute_id' => 7,
                'category_id' => 1,
            ),
            44 => 
            array (
                'id' => 73,
                'attribute_id' => 32,
                'category_id' => 1,
            ),
            45 => 
            array (
                'id' => 74,
                'attribute_id' => 33,
                'category_id' => 19,
            ),
            46 => 
            array (
                'id' => 75,
                'attribute_id' => 16,
                'category_id' => 19,
            ),
            47 => 
            array (
                'id' => 76,
                'attribute_id' => 14,
                'category_id' => 19,
            ),
            48 => 
            array (
                'id' => 77,
                'attribute_id' => 8,
                'category_id' => 19,
            ),
            49 => 
            array (
                'id' => 78,
                'attribute_id' => 11,
                'category_id' => 19,
            ),
            50 => 
            array (
                'id' => 79,
                'attribute_id' => 12,
                'category_id' => 19,
            ),
            51 => 
            array (
                'id' => 80,
                'attribute_id' => 15,
                'category_id' => 19,
            ),
            52 => 
            array (
                'id' => 81,
                'attribute_id' => 13,
                'category_id' => 19,
            ),
            53 => 
            array (
                'id' => 82,
                'attribute_id' => 9,
                'category_id' => 19,
            ),
            54 => 
            array (
                'id' => 83,
                'attribute_id' => 10,
                'category_id' => 19,
            ),
            55 => 
            array (
                'id' => 85,
                'attribute_id' => 34,
                'category_id' => 20,
            ),
            56 => 
            array (
                'id' => 86,
                'attribute_id' => 16,
                'category_id' => 20,
            ),
            57 => 
            array (
                'id' => 87,
                'attribute_id' => 16,
                'category_id' => 21,
            ),
            58 => 
            array (
                'id' => 88,
                'attribute_id' => 16,
                'category_id' => 22,
            ),
            59 => 
            array (
                'id' => 89,
                'attribute_id' => 35,
                'category_id' => 21,
            ),
            60 => 
            array (
                'id' => 90,
                'attribute_id' => 36,
                'category_id' => 22,
            ),
            61 => 
            array (
                'id' => 91,
                'attribute_id' => 37,
                'category_id' => 1,
            ),
            62 => 
            array (
                'id' => 92,
                'attribute_id' => 38,
                'category_id' => 1,
            ),
            63 => 
            array (
                'id' => 93,
                'attribute_id' => 32,
                'category_id' => 3,
            ),
            64 => 
            array (
                'id' => 106,
                'attribute_id' => 42,
                'category_id' => 3,
            ),
            65 => 
            array (
                'id' => 107,
                'attribute_id' => 32,
                'category_id' => 4,
            ),
            66 => 
            array (
                'id' => 109,
                'attribute_id' => 32,
                'category_id' => 10,
            ),
            67 => 
            array (
                'id' => 110,
                'attribute_id' => 32,
                'category_id' => 11,
            ),
            68 => 
            array (
                'id' => 111,
                'attribute_id' => 32,
                'category_id' => 12,
            ),
            69 => 
            array (
                'id' => 112,
                'attribute_id' => 32,
                'category_id' => 13,
            ),
            70 => 
            array (
                'id' => 113,
                'attribute_id' => 32,
                'category_id' => 14,
            ),
            71 => 
            array (
                'id' => 114,
                'attribute_id' => 32,
                'category_id' => 15,
            ),
            72 => 
            array (
                'id' => 115,
                'attribute_id' => 32,
                'category_id' => 16,
            ),
            73 => 
            array (
                'id' => 116,
                'attribute_id' => 32,
                'category_id' => 17,
            ),
            74 => 
            array (
                'id' => 117,
                'attribute_id' => 2,
                'category_id' => 9,
            ),
            75 => 
            array (
                'id' => 118,
                'attribute_id' => 2,
                'category_id' => 10,
            ),
            76 => 
            array (
                'id' => 119,
                'attribute_id' => 2,
                'category_id' => 11,
            ),
            77 => 
            array (
                'id' => 120,
                'attribute_id' => 2,
                'category_id' => 12,
            ),
            78 => 
            array (
                'id' => 121,
                'attribute_id' => 2,
                'category_id' => 13,
            ),
            79 => 
            array (
                'id' => 122,
                'attribute_id' => 2,
                'category_id' => 14,
            ),
            80 => 
            array (
                'id' => 123,
                'attribute_id' => 2,
                'category_id' => 15,
            ),
            81 => 
            array (
                'id' => 124,
                'attribute_id' => 2,
                'category_id' => 16,
            ),
            82 => 
            array (
                'id' => 125,
                'attribute_id' => 2,
                'category_id' => 17,
            ),
            83 => 
            array (
                'id' => 127,
                'attribute_id' => 4,
                'category_id' => 9,
            ),
            84 => 
            array (
                'id' => 128,
                'attribute_id' => 4,
                'category_id' => 10,
            ),
            85 => 
            array (
                'id' => 129,
                'attribute_id' => 4,
                'category_id' => 11,
            ),
            86 => 
            array (
                'id' => 130,
                'attribute_id' => 4,
                'category_id' => 12,
            ),
            87 => 
            array (
                'id' => 131,
                'attribute_id' => 4,
                'category_id' => 13,
            ),
            88 => 
            array (
                'id' => 132,
                'attribute_id' => 4,
                'category_id' => 14,
            ),
            89 => 
            array (
                'id' => 133,
                'attribute_id' => 4,
                'category_id' => 15,
            ),
            90 => 
            array (
                'id' => 134,
                'attribute_id' => 4,
                'category_id' => 16,
            ),
            91 => 
            array (
                'id' => 135,
                'attribute_id' => 4,
                'category_id' => 17,
            ),
            92 => 
            array (
                'id' => 147,
                'attribute_id' => 27,
                'category_id' => 9,
            ),
            93 => 
            array (
                'id' => 148,
                'attribute_id' => 27,
                'category_id' => 10,
            ),
            94 => 
            array (
                'id' => 149,
                'attribute_id' => 27,
                'category_id' => 11,
            ),
            95 => 
            array (
                'id' => 150,
                'attribute_id' => 27,
                'category_id' => 12,
            ),
            96 => 
            array (
                'id' => 151,
                'attribute_id' => 27,
                'category_id' => 13,
            ),
            97 => 
            array (
                'id' => 152,
                'attribute_id' => 27,
                'category_id' => 14,
            ),
            98 => 
            array (
                'id' => 153,
                'attribute_id' => 27,
                'category_id' => 15,
            ),
            99 => 
            array (
                'id' => 154,
                'attribute_id' => 27,
                'category_id' => 16,
            ),
            100 => 
            array (
                'id' => 155,
                'attribute_id' => 27,
                'category_id' => 17,
            ),
            101 => 
            array (
                'id' => 156,
                'attribute_id' => 43,
                'category_id' => 9,
            ),
            102 => 
            array (
                'id' => 160,
                'attribute_id' => 46,
                'category_id' => 46,
            ),
            103 => 
            array (
                'id' => 161,
                'attribute_id' => 44,
                'category_id' => 46,
            ),
            104 => 
            array (
                'id' => 162,
                'attribute_id' => 45,
                'category_id' => 46,
            ),
            105 => 
            array (
                'id' => 163,
                'attribute_id' => 47,
                'category_id' => 9,
            ),
            106 => 
            array (
                'id' => 164,
                'attribute_id' => 48,
                'category_id' => 46,
            ),
            107 => 
            array (
                'id' => 165,
                'attribute_id' => 49,
                'category_id' => 46,
            ),
            108 => 
            array (
                'id' => 166,
                'attribute_id' => 50,
                'category_id' => 46,
            ),
            109 => 
            array (
                'id' => 167,
                'attribute_id' => 51,
                'category_id' => 9,
            ),
            110 => 
            array (
                'id' => 168,
                'attribute_id' => 52,
                'category_id' => 9,
            ),
            111 => 
            array (
                'id' => 169,
                'attribute_id' => 53,
                'category_id' => 9,
            ),
            112 => 
            array (
                'id' => 170,
                'attribute_id' => 54,
                'category_id' => 9,
            ),
            113 => 
            array (
                'id' => 171,
                'attribute_id' => 55,
                'category_id' => 9,
            ),
            114 => 
            array (
                'id' => 172,
                'attribute_id' => 56,
                'category_id' => 9,
            ),
            115 => 
            array (
                'id' => 173,
                'attribute_id' => 57,
                'category_id' => 9,
            ),
            116 => 
            array (
                'id' => 174,
                'attribute_id' => 58,
                'category_id' => 1,
            ),
            117 => 
            array (
                'id' => 175,
                'attribute_id' => 58,
                'category_id' => 3,
            ),
            118 => 
            array (
                'id' => 176,
                'attribute_id' => 58,
                'category_id' => 4,
            ),
            119 => 
            array (
                'id' => 177,
                'attribute_id' => 58,
                'category_id' => 9,
            ),
            120 => 
            array (
                'id' => 178,
                'attribute_id' => 58,
                'category_id' => 10,
            ),
            121 => 
            array (
                'id' => 179,
                'attribute_id' => 58,
                'category_id' => 11,
            ),
            122 => 
            array (
                'id' => 180,
                'attribute_id' => 58,
                'category_id' => 12,
            ),
            123 => 
            array (
                'id' => 181,
                'attribute_id' => 58,
                'category_id' => 13,
            ),
            124 => 
            array (
                'id' => 182,
                'attribute_id' => 58,
                'category_id' => 14,
            ),
            125 => 
            array (
                'id' => 183,
                'attribute_id' => 58,
                'category_id' => 15,
            ),
            126 => 
            array (
                'id' => 184,
                'attribute_id' => 58,
                'category_id' => 16,
            ),
            127 => 
            array (
                'id' => 185,
                'attribute_id' => 58,
                'category_id' => 17,
            ),
            128 => 
            array (
                'id' => 186,
                'attribute_id' => 5,
                'category_id' => 53,
            ),
            129 => 
            array (
                'id' => 187,
                'attribute_id' => 6,
                'category_id' => 54,
            ),
            130 => 
            array (
                'id' => 188,
                'attribute_id' => 38,
                'category_id' => 55,
            ),
            131 => 
            array (
                'id' => 189,
                'attribute_id' => 5,
                'category_id' => 56,
            ),
            132 => 
            array (
                'id' => 190,
                'attribute_id' => 37,
                'category_id' => 57,
            ),
            133 => 
            array (
                'id' => 191,
                'attribute_id' => 32,
                'category_id' => 62,
            ),
            134 => 
            array (
                'id' => 192,
                'attribute_id' => 27,
                'category_id' => 62,
            ),
        ));
        
        
    }
}