<?php 

return [
    'accepted' => 'Поле ":attribute" должно быть принято.',
    'accepted_if' => 'Поле ":attribute" должно быть принято, если :other равно :value.',
    'active_url' => 'Поле ":attribute" должно быть действительным URL.',
    'after' => 'Поле ":attribute" должно быть датой после :date.',
    'after_or_equal' => 'Поле ":attribute" должно быть датой после или равной :date.',
    'alpha' => 'Поле ":attribute" должно содержать только буквы.',
    'alpha_dash' => 'Поле ":attribute" должно содержать только буквы, цифры, дефисы и подчеркивания.',
    'alpha_num' => 'Поле ":attribute" должно содержать только буквы и цифры.',
    'array' => 'Поле ":attribute" должно быть массивом.',
    'ascii' => 'Поле ":attribute" должно содержать только однобайтовые буквенно-цифровые символы и символы.',
    'before' => 'Поле ":attribute" должно быть датой до :date.',
    'before_or_equal' => 'Поле ":attribute" должно быть датой до или равной :date.',
    'between' => [
        'array' => 'Поле ":attribute" должно содержать от :min до :max элементов.',
        'file' => 'Поле ":attribute" должно быть от :min до :max килобайт.',
        'numeric' => 'Поле ":attribute" должно быть между :min и :max.',
        'string' => 'Поле ":attribute" должно содержать от :min до :max символов.',
    ],
    'boolean' => 'Поле ":attribute" должно быть истинным или ложным.',
    'can' => 'Поле ":attribute" содержит несанкционированное значение.',
    'confirmed' => 'Подтверждение поля ":attribute" не совпадает.',
    'current_password' => 'Пароль неверен.',
    'date' => 'Поле ":attribute" должно быть допустимой датой.',
    'date_equals' => 'Поле ":attribute" должно быть датой, равной :date.',
    'date_format' => 'Поле ":attribute" должно соответствовать формату :format.',
    'decimal' => 'Поле ":attribute" должно содержать :decimal десятичных знаков.',
    'declined' => 'Поле ":attribute" должно быть отклонено.',
    'declined_if' => 'Поле ":attribute" должно быть отклонено, если :other равно :value.',
    'different' => 'Поле ":attribute" и :other должны быть разными.',
    'digits' => 'Поле ":attribute" должно содержать :digits цифр.',
    'digits_between' => 'Поле ":attribute" должно быть между :min и :max цифрами.',
    'dimensions' => 'Поле ":attribute" имеет недопустимые размеры изображения.',
    'distinct' => 'Поле ":attribute" содержит повторяющееся значение.',
    'doesnt_end_with' => 'Поле ":attribute" не должно заканчиваться одним из следующих значений: :values.',
    'doesnt_start_with' => 'Поле ":attribute" не должно начинаться с одного из следующих значений: :values.',
    'email' => 'Поле ":attribute" должно быть допустимым адресом электронной почты.',
    'ends_with' => 'Поле ":attribute" должно заканчиваться одним из следующих значений: :values.',
    'enum' => 'Выбранное значение для ":attribute" недопустимо.',
    'exists' => 'Выбранное значение для ":attribute" недопустимо.',
    'extensions' => 'Поле ":attribute" должно иметь одно из следующих расширений: :values.',
    'file' => 'Поле ":attribute" должно быть файлом.',
    'filled' => 'Поле ":attribute" должно иметь значение.',
    'gt' => [
        'array' => 'Поле ":attribute" должно содержать больше :value элементов.',
        'file' => 'Поле ":attribute" должно быть больше :value килобайт.',
        'numeric' => 'Поле ":attribute" должно быть больше :value.',
        'string' => 'Поле ":attribute" должно содержать больше :value символов.',
    ],
    'gte' => [
        'array' => 'Поле ":attribute" должно содержать :value элементов или больше.',
        'file' => 'Поле ":attribute" должно быть больше или равно :value килобайт.',
        'numeric' => 'Поле ":attribute" должно быть больше или равно :value.',
        'string' => 'Поле ":attribute" должно содержать :value символов или больше.',
    ],
    'hex_color' => 'Поле ":attribute" должно быть допустимым шестнадцатеричным цветом.',
    'image' => 'Поле ":attribute" должно быть изображением.',
    'in' => 'Выбранное значение для ":attribute" недопустимо.',
    'in_array' => 'Поле ":attribute" должно присутствовать в :other.',
    'integer' => 'Поле ":attribute" должно быть целым числом.',
    'ip' => 'Поле ":attribute" должно быть допустимым IP-адресом.',
    'ipv4' => 'Поле ":attribute" должно быть допустимым IPv4-адресом.',
    'ipv6' => 'Поле ":attribute" должно быть допустимым IPv6-адресом.',
    'json' => 'Поле ":attribute" должно быть допустимой строкой JSON.',
    'lowercase' => 'Поле ":attribute" должно быть в нижнем регистре.',
    'lt' => [
        'array' => 'Поле ":attribute" должно содержать менее :value элементов.',
        'file' => 'Поле ":attribute" должно быть меньше :value килобайт.',
        'numeric' => 'Поле ":attribute" должно быть меньше :value.',
        'string' => 'Поле ":attribute" должно содержать менее :value символов.',
    ],
    'lte' => [
        'array' => 'Поле ":attribute" не должно содержать более :value элементов.',
        'file' => 'Поле ":attribute" должно быть меньше или равно :value килобайт.',
        'numeric' => 'Поле ":attribute" должно быть меньше или равно :value.',
        'string' => 'Поле ":attribute" должно содержать :value символов или меньше.',
    ],
    'mac_address' => 'Поле ":attribute" должно быть допустимым MAC-адресом.',
    'max' => [
        'array' => 'Поле ":attribute" не должно содержать более :max элементов.',
        'file' => 'Поле ":attribute" не должно быть больше :max килобайт.',
        'numeric' => 'Поле ":attribute" не должно быть больше :max.',
        'string' => 'Поле ":attribute" не должно содержать более :max символов.',
    ],
    'max_digits' => 'Поле ":attribute" не должно содержать более :max цифр.',
    'mimes' => 'Поле ":attribute" должно быть файлом типа: :values.',
    'mimetypes' => 'Поле ":attribute" должно быть файлом типа: :values.',
    'min' => [
        'array' => 'Поле ":attribute" должно содержать минимум :min элементов.',
        'file' => 'Поле ":attribute" должно быть минимум :min килобайт.',
        'numeric' => 'Поле ":attribute" должно быть минимум :min.',
        'string' => 'Поле ":attribute" должно содержать минимум :min символов.',
    ],
    'min_digits' => 'Поле ":attribute" должно содержать минимум :min цифр.',
    'missing' => 'Поле ":attribute" должно отсутствовать.',
    'missing_if' => 'Поле ":attribute" должно отсутствовать, если :other равно :value.',
    'missing_unless' => 'Поле ":attribute" должно отсутствовать, если :other не равно :value.',
    'missing_with' => 'Поле ":attribute" должно отсутствовать, если :values присутствует.',
    'missing_with_all' => 'Поле ":attribute" должно отсутствовать, если :values присутствуют.',
    'multiple_of' => 'Поле ":attribute" должно быть кратным :value.',
    'not_in' => 'Выбранное значение для ":attribute" недопустимо.',
    'not_regex' => 'Поле ":attribute" имеет недопустимый формат.',
    'numeric' => 'Поле ":attribute" должно быть числом.',
    'password' => [
        'letters' => 'Поле ":attribute" должно содержать хотя бы одну букву.',
        'mixed' => 'Поле ":attribute" должно содержать хотя бы одну заглавную и одну строчную букву.',
        'numbers' => 'Поле ":attribute" должно содержать хотя бы одну цифру.',
        'symbols' => 'Поле ":attribute" должно содержать хотя бы один символ.',
        'uncompromised' => 'Указанное ":attribute" обнаружено в утечке данных. Пожалуйста, выберите другое значение ":attribute".',
    ],
    'present' => 'Поле ":attribute" должно присутствовать.',
    'present_if' => 'Поле ":attribute" должно присутствовать, если :other равно :value.',
    'present_unless' => 'Поле ":attribute" должно присутствовать, если :other не равно :value.',
    'present_with' => 'Поле ":attribute" должно присутствовать, если :values присутствует.',
    'present_with_all' => 'Поле ":attribute" должно присутствовать, если :values присутствуют.',
    'prohibited' => 'Поле ":attribute" запрещено.',
    'prohibited_if' => 'Поле ":attribute" запрещено, если :other равно :value.',
    'prohibited_unless' => 'Поле ":attribute" запрещено, если :other не равно :values.',
    'prohibits' => 'Поле ":attribute" запрещает присутствие :other.',
    'regex' => 'Поле ":attribute" имеет недопустимый формат.',
    'required' => 'Поле ":attribute" обязательно для заполнения.',
    'required_array_keys' => 'Поле ":attribute" должно содержать значения для: :values.',
    'required_if' => 'Поле ":attribute" обязательно для заполнения, если :other равно :value.',
    'required_if_accepted' => 'Поле ":attribute" обязательно для заполнения, если :other принято.',
    'required_unless' => 'Поле ":attribute" обязательно для заполнения, если :other не равно :values.',
    'required_with' => 'Поле ":attribute" обязательно для заполнения, если :values присутствует.',
    'required_with_all' => 'Поле ":attribute" обязательно для заполнения, если :values присутствуют.',
    'required_without' => 'Поле ":attribute" обязательно для заполнения, если :values отсутствует.',
    'required_without_all' => 'Поле ":attribute" обязательно для заполнения, если ни одно из :values не присутствует.',
    'same' => 'Поле ":attribute" и :other должны совпадать.',
    'size' => [
        'array' => 'Поле ":attribute" должно содержать :size элементов.',
        'file' => 'Поле ":attribute" должно быть :size килобайт.',
        'numeric' => 'Поле ":attribute" должно быть :size.',
        'string' => 'Поле ":attribute" должно содержать :size символов.',
    ],
    'starts_with' => 'Поле ":attribute" должно начинаться с одного из следующих значений: :values.',
    'string' => 'Поле ":attribute" должно быть строкой.',
    'timezone' => 'Поле ":attribute" должно быть допустимым часовым поясом.',
    'unique' => 'Поле ":attribute" уже занято.',
    'uploaded' => 'Загрузка файла ":attribute" не удалась.',
    'uppercase' => 'Поле ":attribute" должно быть в верхнем регистре.',
    'url' => 'Поле ":attribute" должно быть допустимым URL.',
    'uuid' => 'Поле ":attribute" должно быть допустимым UUID.',

    'custom' => [
        'data.categories.*' => [
            'required' => 'Вы должны выбрать хотя бы одну категорию.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];