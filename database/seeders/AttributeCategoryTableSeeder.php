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
                'id' => 26,
                'attribute_id' => 22,
                'category_id' => 5,
            ),
            14 => 
            array (
                'id' => 27,
                'attribute_id' => 23,
                'category_id' => 5,
            ),
            15 => 
            array (
                'id' => 28,
                'attribute_id' => 24,
                'category_id' => 5,
            ),
            16 => 
            array (
                'id' => 29,
                'attribute_id' => 25,
                'category_id' => 5,
            ),
            17 => 
            array (
                'id' => 31,
                'attribute_id' => 27,
                'category_id' => 1,
            ),
            18 => 
            array (
                'id' => 33,
                'attribute_id' => 27,
                'category_id' => 4,
            ),
            19 => 
            array (
                'id' => 34,
                'attribute_id' => 6,
                'category_id' => 6,
            ),
            20 => 
            array (
                'id' => 45,
                'attribute_id' => 4,
                'category_id' => 4,
            ),
            21 => 
            array (
                'id' => 46,
                'attribute_id' => 5,
                'category_id' => 4,
            ),
            22 => 
            array (
                'id' => 47,
                'attribute_id' => 29,
                'category_id' => 1,
            ),
            23 => 
            array (
                'id' => 51,
                'attribute_id' => 29,
                'category_id' => 8,
            ),
            24 => 
            array (
                'id' => 52,
                'attribute_id' => 6,
                'category_id' => 8,
            ),
            25 => 
            array (
                'id' => 53,
                'attribute_id' => 6,
                'category_id' => 27,
            ),
            26 => 
            array (
                'id' => 56,
                'attribute_id' => 14,
                'category_id' => 18,
            ),
            27 => 
            array (
                'id' => 57,
                'attribute_id' => 16,
                'category_id' => 18,
            ),
            28 => 
            array (
                'id' => 58,
                'attribute_id' => 9,
                'category_id' => 18,
            ),
            29 => 
            array (
                'id' => 59,
                'attribute_id' => 10,
                'category_id' => 18,
            ),
            30 => 
            array (
                'id' => 60,
                'attribute_id' => 11,
                'category_id' => 18,
            ),
            31 => 
            array (
                'id' => 61,
                'attribute_id' => 12,
                'category_id' => 18,
            ),
            32 => 
            array (
                'id' => 62,
                'attribute_id' => 13,
                'category_id' => 18,
            ),
            33 => 
            array (
                'id' => 63,
                'attribute_id' => 15,
                'category_id' => 18,
            ),
            34 => 
            array (
                'id' => 64,
                'attribute_id' => 6,
                'category_id' => 28,
            ),
            35 => 
            array (
                'id' => 65,
                'attribute_id' => 6,
                'category_id' => 29,
            ),
            36 => 
            array (
                'id' => 67,
                'attribute_id' => 30,
                'category_id' => 18,
            ),
            37 => 
            array (
                'id' => 69,
                'attribute_id' => 31,
                'category_id' => 18,
            ),
            38 => 
            array (
                'id' => 71,
                'attribute_id' => 6,
                'category_id' => 1,
            ),
            39 => 
            array (
                'id' => 72,
                'attribute_id' => 7,
                'category_id' => 1,
            ),
            40 => 
            array (
                'id' => 73,
                'attribute_id' => 32,
                'category_id' => 1,
            ),
            41 => 
            array (
                'id' => 74,
                'attribute_id' => 33,
                'category_id' => 19,
            ),
            42 => 
            array (
                'id' => 75,
                'attribute_id' => 16,
                'category_id' => 19,
            ),
            43 => 
            array (
                'id' => 76,
                'attribute_id' => 14,
                'category_id' => 19,
            ),
            44 => 
            array (
                'id' => 77,
                'attribute_id' => 8,
                'category_id' => 19,
            ),
            45 => 
            array (
                'id' => 78,
                'attribute_id' => 11,
                'category_id' => 19,
            ),
            46 => 
            array (
                'id' => 79,
                'attribute_id' => 12,
                'category_id' => 19,
            ),
            47 => 
            array (
                'id' => 80,
                'attribute_id' => 15,
                'category_id' => 19,
            ),
            48 => 
            array (
                'id' => 81,
                'attribute_id' => 13,
                'category_id' => 19,
            ),
            49 => 
            array (
                'id' => 82,
                'attribute_id' => 9,
                'category_id' => 19,
            ),
            50 => 
            array (
                'id' => 83,
                'attribute_id' => 10,
                'category_id' => 19,
            ),
            51 => 
            array (
                'id' => 85,
                'attribute_id' => 34,
                'category_id' => 20,
            ),
            52 => 
            array (
                'id' => 86,
                'attribute_id' => 16,
                'category_id' => 20,
            ),
            53 => 
            array (
                'id' => 87,
                'attribute_id' => 16,
                'category_id' => 21,
            ),
            54 => 
            array (
                'id' => 88,
                'attribute_id' => 16,
                'category_id' => 22,
            ),
            55 => 
            array (
                'id' => 89,
                'attribute_id' => 35,
                'category_id' => 21,
            ),
            56 => 
            array (
                'id' => 90,
                'attribute_id' => 36,
                'category_id' => 22,
            ),
            57 => 
            array (
                'id' => 91,
                'attribute_id' => 37,
                'category_id' => 1,
            ),
            58 => 
            array (
                'id' => 92,
                'attribute_id' => 38,
                'category_id' => 1,
            ),
            59 => 
            array (
                'id' => 106,
                'attribute_id' => 42,
                'category_id' => 3,
            ),
            60 => 
            array (
                'id' => 107,
                'attribute_id' => 32,
                'category_id' => 4,
            ),
            61 => 
            array (
                'id' => 109,
                'attribute_id' => 32,
                'category_id' => 10,
            ),
            62 => 
            array (
                'id' => 110,
                'attribute_id' => 32,
                'category_id' => 11,
            ),
            63 => 
            array (
                'id' => 111,
                'attribute_id' => 32,
                'category_id' => 12,
            ),
            64 => 
            array (
                'id' => 112,
                'attribute_id' => 32,
                'category_id' => 13,
            ),
            65 => 
            array (
                'id' => 113,
                'attribute_id' => 32,
                'category_id' => 14,
            ),
            66 => 
            array (
                'id' => 114,
                'attribute_id' => 32,
                'category_id' => 15,
            ),
            67 => 
            array (
                'id' => 115,
                'attribute_id' => 32,
                'category_id' => 16,
            ),
            68 => 
            array (
                'id' => 116,
                'attribute_id' => 32,
                'category_id' => 17,
            ),
            69 => 
            array (
                'id' => 117,
                'attribute_id' => 2,
                'category_id' => 9,
            ),
            70 => 
            array (
                'id' => 118,
                'attribute_id' => 2,
                'category_id' => 10,
            ),
            71 => 
            array (
                'id' => 119,
                'attribute_id' => 2,
                'category_id' => 11,
            ),
            72 => 
            array (
                'id' => 120,
                'attribute_id' => 2,
                'category_id' => 12,
            ),
            73 => 
            array (
                'id' => 121,
                'attribute_id' => 2,
                'category_id' => 13,
            ),
            74 => 
            array (
                'id' => 122,
                'attribute_id' => 2,
                'category_id' => 14,
            ),
            75 => 
            array (
                'id' => 123,
                'attribute_id' => 2,
                'category_id' => 15,
            ),
            76 => 
            array (
                'id' => 124,
                'attribute_id' => 2,
                'category_id' => 16,
            ),
            77 => 
            array (
                'id' => 125,
                'attribute_id' => 2,
                'category_id' => 17,
            ),
            78 => 
            array (
                'id' => 127,
                'attribute_id' => 4,
                'category_id' => 9,
            ),
            79 => 
            array (
                'id' => 128,
                'attribute_id' => 4,
                'category_id' => 10,
            ),
            80 => 
            array (
                'id' => 129,
                'attribute_id' => 4,
                'category_id' => 11,
            ),
            81 => 
            array (
                'id' => 130,
                'attribute_id' => 4,
                'category_id' => 12,
            ),
            82 => 
            array (
                'id' => 131,
                'attribute_id' => 4,
                'category_id' => 13,
            ),
            83 => 
            array (
                'id' => 132,
                'attribute_id' => 4,
                'category_id' => 14,
            ),
            84 => 
            array (
                'id' => 133,
                'attribute_id' => 4,
                'category_id' => 15,
            ),
            85 => 
            array (
                'id' => 134,
                'attribute_id' => 4,
                'category_id' => 16,
            ),
            86 => 
            array (
                'id' => 135,
                'attribute_id' => 4,
                'category_id' => 17,
            ),
            87 => 
            array (
                'id' => 147,
                'attribute_id' => 27,
                'category_id' => 9,
            ),
            88 => 
            array (
                'id' => 148,
                'attribute_id' => 27,
                'category_id' => 10,
            ),
            89 => 
            array (
                'id' => 149,
                'attribute_id' => 27,
                'category_id' => 11,
            ),
            90 => 
            array (
                'id' => 150,
                'attribute_id' => 27,
                'category_id' => 12,
            ),
            91 => 
            array (
                'id' => 151,
                'attribute_id' => 27,
                'category_id' => 13,
            ),
            92 => 
            array (
                'id' => 152,
                'attribute_id' => 27,
                'category_id' => 14,
            ),
            93 => 
            array (
                'id' => 153,
                'attribute_id' => 27,
                'category_id' => 15,
            ),
            94 => 
            array (
                'id' => 154,
                'attribute_id' => 27,
                'category_id' => 16,
            ),
            95 => 
            array (
                'id' => 155,
                'attribute_id' => 27,
                'category_id' => 17,
            ),
            96 => 
            array (
                'id' => 156,
                'attribute_id' => 43,
                'category_id' => 9,
            ),
            97 => 
            array (
                'id' => 160,
                'attribute_id' => 46,
                'category_id' => 46,
            ),
            98 => 
            array (
                'id' => 161,
                'attribute_id' => 44,
                'category_id' => 46,
            ),
            99 => 
            array (
                'id' => 162,
                'attribute_id' => 45,
                'category_id' => 46,
            ),
            100 => 
            array (
                'id' => 163,
                'attribute_id' => 47,
                'category_id' => 9,
            ),
            101 => 
            array (
                'id' => 164,
                'attribute_id' => 48,
                'category_id' => 46,
            ),
            102 => 
            array (
                'id' => 165,
                'attribute_id' => 49,
                'category_id' => 46,
            ),
            103 => 
            array (
                'id' => 166,
                'attribute_id' => 50,
                'category_id' => 46,
            ),
            104 => 
            array (
                'id' => 167,
                'attribute_id' => 51,
                'category_id' => 9,
            ),
            105 => 
            array (
                'id' => 168,
                'attribute_id' => 52,
                'category_id' => 9,
            ),
            106 => 
            array (
                'id' => 169,
                'attribute_id' => 53,
                'category_id' => 9,
            ),
            107 => 
            array (
                'id' => 170,
                'attribute_id' => 54,
                'category_id' => 9,
            ),
            108 => 
            array (
                'id' => 171,
                'attribute_id' => 55,
                'category_id' => 9,
            ),
            109 => 
            array (
                'id' => 172,
                'attribute_id' => 56,
                'category_id' => 9,
            ),
            110 => 
            array (
                'id' => 173,
                'attribute_id' => 57,
                'category_id' => 9,
            ),
            111 => 
            array (
                'id' => 174,
                'attribute_id' => 58,
                'category_id' => 1,
            ),
            112 => 
            array (
                'id' => 175,
                'attribute_id' => 58,
                'category_id' => 3,
            ),
            113 => 
            array (
                'id' => 176,
                'attribute_id' => 58,
                'category_id' => 4,
            ),
            114 => 
            array (
                'id' => 177,
                'attribute_id' => 58,
                'category_id' => 9,
            ),
            115 => 
            array (
                'id' => 178,
                'attribute_id' => 58,
                'category_id' => 10,
            ),
            116 => 
            array (
                'id' => 179,
                'attribute_id' => 58,
                'category_id' => 11,
            ),
            117 => 
            array (
                'id' => 180,
                'attribute_id' => 58,
                'category_id' => 12,
            ),
            118 => 
            array (
                'id' => 181,
                'attribute_id' => 58,
                'category_id' => 13,
            ),
            119 => 
            array (
                'id' => 182,
                'attribute_id' => 58,
                'category_id' => 14,
            ),
            120 => 
            array (
                'id' => 183,
                'attribute_id' => 58,
                'category_id' => 15,
            ),
            121 => 
            array (
                'id' => 184,
                'attribute_id' => 58,
                'category_id' => 16,
            ),
            122 => 
            array (
                'id' => 185,
                'attribute_id' => 58,
                'category_id' => 17,
            ),
            123 => 
            array (
                'id' => 186,
                'attribute_id' => 5,
                'category_id' => 53,
            ),
            124 => 
            array (
                'id' => 187,
                'attribute_id' => 6,
                'category_id' => 54,
            ),
            125 => 
            array (
                'id' => 188,
                'attribute_id' => 38,
                'category_id' => 55,
            ),
            126 => 
            array (
                'id' => 189,
                'attribute_id' => 5,
                'category_id' => 56,
            ),
            127 => 
            array (
                'id' => 190,
                'attribute_id' => 37,
                'category_id' => 57,
            ),
            128 => 
            array (
                'id' => 191,
                'attribute_id' => 32,
                'category_id' => 62,
            ),
            129 => 
            array (
                'id' => 192,
                'attribute_id' => 27,
                'category_id' => 62,
            ),
            130 => 
            array (
                'id' => 195,
                'attribute_id' => 62,
                'category_id' => 63,
            ),
            131 => 
            array (
                'id' => 197,
                'attribute_id' => 63,
                'category_id' => 63,
            ),
            132 => 
            array (
                'id' => 200,
                'attribute_id' => 64,
                'category_id' => 63,
            ),
            133 => 
            array (
                'id' => 202,
                'attribute_id' => 65,
                'category_id' => 63,
            ),
            134 => 
            array (
                'id' => 204,
                'attribute_id' => 59,
                'category_id' => 63,
            ),
            135 => 
            array (
                'id' => 205,
                'attribute_id' => 32,
                'category_id' => 63,
            ),
            136 => 
            array (
                'id' => 206,
                'attribute_id' => 2,
                'category_id' => 63,
            ),
            137 => 
            array (
                'id' => 207,
                'attribute_id' => 21,
                'category_id' => 63,
            ),
            138 => 
            array (
                'id' => 209,
                'attribute_id' => 60,
                'category_id' => 63,
            ),
            139 => 
            array (
                'id' => 210,
                'attribute_id' => 27,
                'category_id' => 63,
            ),
            140 => 
            array (
                'id' => 211,
                'attribute_id' => 61,
                'category_id' => 63,
            ),
            141 => 
            array (
                'id' => 212,
                'attribute_id' => 66,
                'category_id' => 63,
            ),
            142 => 
            array (
                'id' => 213,
                'attribute_id' => 67,
                'category_id' => 63,
            ),
            143 => 
            array (
                'id' => 214,
                'attribute_id' => 69,
                'category_id' => 63,
            ),
            144 => 
            array (
                'id' => 215,
                'attribute_id' => 70,
                'category_id' => 63,
            ),
            145 => 
            array (
                'id' => 217,
                'attribute_id' => 71,
                'category_id' => 63,
            ),
            146 => 
            array (
                'id' => 218,
                'attribute_id' => 72,
                'category_id' => 63,
            ),
            147 => 
            array (
                'id' => 219,
                'attribute_id' => 28,
                'category_id' => 3,
            ),
            148 => 
            array (
                'id' => 220,
                'attribute_id' => 73,
                'category_id' => 3,
            ),
            149 => 
            array (
                'id' => 221,
                'attribute_id' => 27,
                'category_id' => 64,
            ),
            150 => 
            array (
                'id' => 222,
                'attribute_id' => 63,
                'category_id' => 64,
            ),
            151 => 
            array (
                'id' => 223,
                'attribute_id' => 65,
                'category_id' => 64,
            ),
            152 => 
            array (
                'id' => 224,
                'attribute_id' => 62,
                'category_id' => 64,
            ),
            153 => 
            array (
                'id' => 225,
                'attribute_id' => 61,
                'category_id' => 64,
            ),
            154 => 
            array (
                'id' => 226,
                'attribute_id' => 59,
                'category_id' => 64,
            ),
            155 => 
            array (
                'id' => 227,
                'attribute_id' => 74,
                'category_id' => 64,
            ),
            156 => 
            array (
                'id' => 228,
                'attribute_id' => 75,
                'category_id' => 63,
            ),
        ));
        
        
    }
}