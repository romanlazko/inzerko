<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Pole :attribute musí být přijato.',
    'accepted_if' => 'Pole :attribute musí být přijato, když :other je :value.',
    'active_url' => 'Pole :attribute musí být platná URL.',
    'after' => 'Pole :attribute musí být datum po :date.',
    'after_or_equal' => 'Pole :attribute musí být datum po nebo rovno :date.',
    'alpha' => 'Pole :attribute může obsahovat pouze písmena.',
    'alpha_dash' => 'Pole :attribute může obsahovat pouze písmena, číslice, pomlčky a podtržítka.',
    'alpha_num' => 'Pole :attribute může obsahovat pouze písmena a číslice.',
    'array' => 'Pole :attribute musí být pole.',
    'ascii' => 'Pole :attribute může obsahovat pouze jedno-bajtové alfanumerické znaky a symboly.',
    'before' => 'Pole :attribute musí být datum před :date.',
    'before_or_equal' => 'Pole :attribute musí být datum před nebo rovno :date.',
    'between' => [
        'array' => 'Pole :attribute musí mít mezi :min a :max položkami.',
        'file' => 'Pole :attribute musí být mezi :min a :max kilobajty.',
        'numeric' => 'Pole :attribute musí být mezi :min a :max.',
        'string' => 'Pole :attribute musí mít mezi :min a :max znaky.',
    ],
    'boolean' => 'Pole :attribute musí být pravda nebo nepravda.',
    'can' => 'Pole :attribute obsahuje neoprávněnou hodnotu.',
    'confirmed' => 'Potvrzení pole :attribute se neshoduje.',
    'current_password' => 'Heslo je nesprávné.',
    'date' => 'Pole :attribute musí být platné datum.',
    'date_equals' => 'Pole :attribute musí být datum rovné :date.',
    'date_format' => 'Pole :attribute musí odpovídat formátu :format.',
    'decimal' => 'Pole :attribute musí mít :decimal desetinných míst.',
    'declined' => 'Pole :attribute musí být odmítnuto.',
    'declined_if' => 'Pole :attribute musí být odmítnuto, když :other je :value.',
    'different' => 'Pole :attribute a :other musí být odlišné.',
    'digits' => 'Pole :attribute musí mít :digits číslic.',
    'digits_between' => 'Pole :attribute musí mít mezi :min a :max číslicemi.',
    'dimensions' => 'Pole :attribute má neplatné rozměry obrázku.',
    'distinct' => 'Pole :attribute má duplicitní hodnotu.',
    'doesnt_end_with' => 'Pole :attribute nesmí končit jedním z následujících: :values.',
    'doesnt_start_with' => 'Pole :attribute nesmí začínat jedním z následujících: :values.',
    'email' => 'Pole :attribute musí být platná e-mailová adresa.',
    'ends_with' => 'Pole :attribute musí končit jedním z následujících: :values.',
    'enum' => 'Vybrané :attribute je neplatné.',
    'exists' => 'Vybrané :attribute je neplatné.',
    'extensions' => 'Pole :attribute musí mít jednu z následujících přípon: :values.',
    'file' => 'Pole :attribute musí být soubor.',
    'filled' => 'Pole :attribute musí mít hodnotu.',
    'gt' => [
        'array' => 'Pole :attribute musí mít více než :value položek.',
        'file' => 'Pole :attribute musí být větší než :value kilobajty.',
        'numeric' => 'Pole :attribute musí být větší než :value.',
        'string' => 'Pole :attribute musí být delší než :value znaků.',
    ],
    'gte' => [
        'array' => 'Pole :attribute musí mít :value položek nebo více.',
        'file' => 'Pole :attribute musí být větší nebo rovno :value kilobajtům.',
        'numeric' => 'Pole :attribute musí být větší nebo rovno :value.',
        'string' => 'Pole :attribute musí být delší nebo rovno :value znakům.',
    ],
    'hex_color' => 'Pole :attribute musí být platná hexadecimální barva.',
    'image' => 'Pole :attribute musí být obrázek.',
    'in' => 'Vybrané :attribute je neplatné.',
    'in_array' => 'Pole :attribute musí existovat v :other.',
    'integer' => 'Pole :attribute musí být celé číslo.',
    'ip' => 'Pole :attribute musí být platná IP adresa.',
    'ipv4' => 'Pole :attribute musí být platná IPv4 adresa.',
    'ipv6' => 'Pole :attribute musí být platná IPv6 adresa.',
    'json' => 'Pole :attribute musí být platný JSON řetězec.',
    'lowercase' => 'Pole :attribute musí být malé písmeno.',
    'lt' => [
        'array' => 'Pole :attribute musí mít méně než :value položek.',
        'file' => 'Pole :attribute musí být menší než :value kilobajty.',
        'numeric' => 'Pole :attribute musí být menší než :value.',
        'string' => 'Pole :attribute musí být kratší než :value znaků.',
    ],
    'lte' => [
        'array' => 'Pole :attribute nesmí mít více než :value položek.',
        'file' => 'Pole :attribute musí být menší nebo rovno :value kilobajtům.',
        'numeric' => 'Pole :attribute musí být menší nebo rovno :value.',
        'string' => 'Pole :attribute musí být kratší nebo rovno :value znakům.',
    ],
    'mac_address' => 'Pole :attribute musí být platná MAC adresa.',
    'max' => [
        'array' => 'Pole :attribute nesmí mít více než :max položek.',
        'file' => 'Pole :attribute nesmí být větší než :max kilobajtů.',
        'numeric' => 'Pole :attribute nesmí být větší než :max.',
        'string' => 'Pole :attribute nesmí být delší než :max znaků.',
    ],
    'max_digits' => 'Pole :attribute nesmí mít více než :max číslic.',
    'mimes' => 'Pole :attribute musí být soubor typu: :values.',
    'mimetypes' => 'Pole :attribute musí být soubor typu: :values.',
    'min' => [
        'array' => 'Pole :attribute musí mít alespoň :min položek.',
        'file' => 'Pole :attribute musí být alespoň :min kilobajtů.',
        'numeric' => 'Pole :attribute musí být alespoň :min.',
        'string' => 'Pole :attribute musí mít alespoň :min znaků.',
    ],
    'min_digits' => 'Pole :attribute musí mít alespoň :min číslic.',
    'missing' => 'Pole :attribute musí být chybějící.',
    'missing_if' => 'Pole :attribute musí být chybějící, když :other je :value.',
    'missing_unless' => 'Pole :attribute musí být chybějící, pokud :other není v :values.',
    'missing_with' => 'Pole :attribute musí být chybějící, když je :values přítomno.',
    'missing_with_all' => 'Pole :attribute musí být chybějící, když jsou :values přítomny.',
    'multiple_of' => 'Pole :attribute musí být násobek :value.',
    'not_in' => 'Vybrané :attribute je neplatné.',
    'not_regex' => 'Formát pole :attribute je neplatný.',
    'numeric' => 'Pole :attribute musí být číslo.',
    'password' => [
        'letters' => 'Pole :attribute musí obsahovat alespoň jedno písmeno.',
        'mixed' => 'Pole :attribute musí obsahovat alespoň jedno velké a jedno malé písmeno.',
        'numbers' => 'Pole :attribute musí obsahovat alespoň jedno číslo.',
        'symbols' => 'Pole :attribute musí obsahovat alespoň jeden symbol.',
        'uncompromised' => 'Zadané :attribute se objevilo v úniku dat. Vyberte prosím jiné :attribute.',
    ],
    'present' => 'Pole :attribute musí být přítomno.',
    'present_if' => 'Pole :attribute musí být přítomno, když :other je :value.',
    'present_unless' => 'Pole :attribute musí být přítomno, pokud :other není :value.',
    'present_with' => 'Pole :attribute musí být přítomno, když je :values přítomno.',
    'present_with_all' => 'Pole :attribute musí být přítomno, když jsou :values přítomny.',
    'prohibited' => 'Pole :attribute je zakázáno.',
    'prohibited_if' => 'Pole :attribute je zakázáno, když :other je :value.',
    'prohibited_unless' => 'Pole :attribute je zakázáno, pokud :other není v :values.',
    'prohibits' => 'Pole :attribute zakazuje přítomnost :other.',
    'regex' => 'Formát pole :attribute je neplatný.',
    'required' => 'Pole :attribute je povinné.',
    'required_array_keys' => 'Pole :attribute musí obsahovat položky pro: :values.',
    'required_if' => 'Pole :attribute je povinné, když :other je :value.',
    'required_if_accepted' => 'Pole :attribute je povinné, když je :other přijato.',
    'required_unless' => 'Pole :attribute je povinné, pokud :other není v :values.',
    'required_with' => 'Pole :attribute je povinné, když je :values přítomno.',
    'required_with_all' => 'Pole :attribute je povinné, když jsou :values přítomny.',
    'required_without' => 'Pole :attribute je povinné, když :values není přítomno.',
    'required_without_all' => 'Pole :attribute je povinné, když žádné z :values není přítomno.',
    'same' => 'Pole :attribute se musí shodovat s :other.',
    'size' => [
        'array' => 'Pole :attribute musí obsahovat :size položek.',
        'file' => 'Pole :attribute musí být :size kilobajtů.',
        'numeric' => 'Pole :attribute musí být :size.',
        'string' => 'Pole :attribute musí mít :size znaků.',
    ],
    'starts_with' => 'Pole :attribute musí začínat jedním z následujících: :values.',
    'string' => 'Pole :attribute musí být řetězec.',
    'timezone' => 'Pole :attribute musí být platná časová zóna.',
    'unique' => 'Pole :attribute již bylo použito.',
    'uploaded' => 'Nahrávání pole :attribute selhalo.',
    'uppercase' => 'Pole :attribute musí být velké písmeno.',
    'url' => 'Pole :attribute musí být platná URL.',
    'ulid' => 'Pole :attribute musí být platný ULID.',
    'uuid' => 'Pole :attribute musí být platný UUID.',
    'at_least_one_selected' => 'Musí být vybrána alespoň jedna položka.',
    'phone' => 'Neplatné telefonní číslo.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'data.categories.*' => [
            'required' => 'Musíte vybrat alespoň jednu kategorii.',
        ],
        'communication_settings.*.phone.required_if_accepted' => 'Pole ":attribute" je povinné.',
        'phone' => 'Neplatné telefonní číslo.',
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

    'attributes' => [
        'communication_settings.*.phone' => 'telefonní číslo',
        'lang' => 'jazyk',
    ],

];