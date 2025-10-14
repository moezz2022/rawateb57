<?php

return [

    /*
    |--------------------------------------------------------------------------
    | أسطر لغة التحقق (Validation Language Lines)
    |--------------------------------------------------------------------------
    |
    | الأسطر التالية تحتوي رسائل التحقق الافتراضية المستخدمة من قبل
    | صنف المدقق (Validator). بعض هذه القواعد تحتوي على عدة نسخ مثل
    | قواعد الحجم. لا تتردد في تعديل هذه الرسائل هنا بما يتناسب مع
    | متطلبات تطبيقك.
    |
    */

    'accepted'             => 'يجب قبول :attribute.',
    'accepted_if'          => 'يجب قبول :attribute عندما يكون :other يساوي :value.',
    'active_url'           => ':attribute لا يُمثّل رابطًا صحيحًا.',
    'after'                => 'يجب على :attribute أن يكون تاريخًا بعد :date.',
    'after_or_equal'       => ':attribute يجب أن يكون تاريخاً لاحقاً أو مطابقاً لـ :date.',
    'alpha'                => 'يجب أن لا يحتوي :attribute سوى على حروف.',
    'alpha_dash'           => 'يجب أن لا يحتوي :attribute سوى على حروف، أرقام، شرطات وشرطات سفلية.',
    'alpha_num'            => 'يجب أن يحتوي :attribute على حروف وأرقام فقط.',
    'array'                => 'يجب أن يكون :attribute مصفوفة.',
    'ascii'                => 'يجب أن يحتوي :attribute على رموز أبجدية رقمية ورموز أحادية البايت فقط.',
    'before'               => 'يجب على :attribute أن يكون تاريخًا قبل :date.',
    'before_or_equal'      => ':attribute يجب أن يكون تاريخا سابقا أو مطابقا لـ :date.',
    'between'              => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file'    => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string'  => 'يجب أن يكون عدد حروف النص :attribute بين :min و :max.',
        'array'   => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max.',
    ],
    'boolean'              => 'يجب أن تكون قيمة :attribute إما true أو false.',
    'confirmed'            => 'تأكيد :attribute غير متطابق.',
    'current_password'     => 'كلمة المرور غير صحيحة.',
    'date'                 => ':attribute ليس تاريخًا صحيحًا.',
    'date_equals'          => 'يجب أن يكون :attribute تاريخًا مطابقًا لـ :date.',
    'date_format'          => 'لا يتوافق :attribute مع الشكل :format.',
    'decimal'              => 'يجب أن يحتوي :attribute على :decimal منازل عشرية.',
    'declined'             => 'يجب رفض :attribute.',
    'declined_if'          => ':attribute يجب رفضه عندما يكون :other يساوي :value.',
    'different'            => 'يجب أن يكون :attribute و :other مختلفين.',
    'digits'               => 'يجب أن يحتوي :attribute على :digits أرقام.',
    'digits_between'       => 'يجب أن يحتوي :attribute بين :min و :max أرقام.',
    'dimensions'           => 'الـ :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct'             => 'للحقل :attribute قيمة مكررة.',
    'doesnt_end_with'      => ':attribute قد لا ينتهي بأحد القيم التالية: :values.',
    'doesnt_start_with'    => ':attribute قد لا يبدأ بأحد القيم التالية: :values.',
    'email'                => 'يجب أن يكون :attribute بريدًا إلكترونيًا صحيحًا.',
    'ends_with'            => 'يجب أن ينتهي :attribute بأحد القيم التالية: :values.',
    'enum'                 => 'القيمة المحددة في :attribute غير صالحة.',
    'exists'               => 'القيمة المحددة :attribute غير موجودة.',
    'file'                 => 'يجب أن يكون :attribute ملفًا.',
    'filled'               => 'يجب أن يحتوي :attribute على قيمة.',
    'gt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أكثر من :value حروف.',
        'array'   => 'يجب أن يحتوي :attribute على أكثر من :value عناصر.',
    ],
    'gte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من أو تساوي :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أكبر من أو يساوي :value حروف.',
        'array'   => 'يجب أن يحتوي :attribute على :value عناصر أو أكثر.',
    ],
    'image'                => 'يجب أن يكون :attribute صورة.',
    'in'                   => 'العنصر :attribute غير صحيح.',
    'in_array'             => ':attribute غير موجود في :other.',
    'integer'              => 'يجب أن يكون :attribute عددًا صحيحًا.',
    'ip'                   => 'يجب أن يكون :attribute عنوان IP صحيحًا.',
    'ipv4'                 => 'يجب أن يكون :attribute عنوان IPv4 صحيحًا.',
    'ipv6'                 => 'يجب أن يكون :attribute عنوان IPv6 صحيحًا.',
    'json'                 => 'يجب أن يكون :attribute نصًا من نوع JSON.',
    'lowercase'            => 'يجب أن يكون :attribute بحروف صغيرة.',
    'lt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من :value.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أصغر من :value كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute أقل من :value حروف.',
        'array'   => 'يجب أن يحتوي :attribute على أقل من :value عناصر.',
    ],
    'lte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من أو تساوي :value.',
        'file'    => 'يجب أن لا يتجاوز حجم الملف :attribute :value كيلوبايت.',
        'string'  => 'يجب أن لا يتجاوز طول النص :attribute :value حروف.',
        'array'   => 'يجب أن لا يحتوي :attribute على أكثر من :value عناصر.',
    ],
    'mac_address'          => 'يجب أن يكون :attribute عنوان MAC صحيحًا.',
    'max'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من أو تساوي :max.',
        'file'    => 'يجب أن لا يتجاوز حجم الملف :attribute :max كيلوبايت.',
        'string'  => 'يجب أن لا يتجاوز طول النص :attribute :max حروف.',
        'array'   => 'يجب أن لا يحتوي :attribute على أكثر من :max عناصر.',
    ],
    'mimes'                => 'يجب أن يكون الملف من نوع: :values.',
    'mimetypes'            => 'يجب أن يكون الملف من نوع: :values.',
    'min'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من أو تساوي :min.',
        'file'    => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت.',
        'string'  => 'يجب أن يكون طول النص :attribute على الأقل :min حروف.',
        'array'   => 'يجب أن يحتوي :attribute على الأقل :min عناصر.',
    ],
    'multiple_of'          => 'يجب أن تكون قيمة :attribute من مضاعفات :value.',
    'not_in'               => 'العنصر :attribute غير صحيح.',
    'not_regex'            => 'صيغة :attribute غير صحيحة.',
    'numeric'              => 'يجب على :attribute أن يكون رقمًا.',
    'password'             => [
        'letters' => 'يجب أن يحتوي :attribute على حرف واحد على الأقل.',
        'mixed' => 'يجب أن يحتوي :attribute على حرف كبير وحرف صغير على الأقل.',
        'numbers' => 'يجب أن يحتوي :attribute على رقم واحد على الأقل.',
        'symbols' => 'يجب أن يحتوي :attribute على رمز واحد على الأقل.',
        'uncompromised' => ':attribute ظهر في تسريب بيانات. يرجى اختيار كلمة مرور أخرى.',
    ],
    'present'              => 'يجب تقديم :attribute.',
    'prohibited'           => ':attribute محظور.',
    'prohibited_if'        => ':attribute محظور عندما يكون :other يساوي :value.',
    'prohibited_unless'    => ':attribute محظور إلا إذا كان :other في :values.',
    'prohibits'            => ':attribute يمنع تواجد :other.',
    'regex'                => 'صيغة :attribute غير صحيحة.',
    'required'             => 'حقل :attribute مطلوب.',
    'required_array_keys'  => 'يجب أن يحتوي :attribute على مفاتيح للقيم: :values.',
    'required_if'          => ':attribute مطلوب عندما يكون :other يساوي :value.',
    'required_unless'      => ':attribute مطلوب إلا إذا كان :other في :values.',
    'required_with'        => ':attribute مطلوب عندما يكون :values موجودًا.',
    'required_with_all'    => ':attribute مطلوب عندما تكون :values موجودة.',
    'required_without'     => ':attribute مطلوب عندما لا تكون :values موجودة.',
    'required_without_all' => ':attribute مطلوب عند غياب جميع :values.',
    'same'                 => 'يجب أن يتطابق :attribute مع :other.',
    'size'                 => [
        'numeric' => 'يجب أن تكون قيمة :attribute تساوي :size.',
        'file'    => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت.',
        'string'  => 'يجب أن يحتوي النص :attribute على :size حروف.',
        'array'   => 'يجب أن يحتوي :attribute على :size عناصر.',
    ],
    'starts_with'          => 'يجب أن يبدأ :attribute بأحد القيم التالية: :values.',
    'string'               => 'يجب أن يكون :attribute نصًا.',
    'timezone'             => 'يجب أن يكون :attribute نطاقًا زمنيًا صحيحًا.',
    'unique'               => 'قيمة :attribute مُستخدمة من قبل.',
    'uploaded'             => 'فشل في رفع :attribute.',
    'uppercase'            => 'يجب أن يكون :attribute بحروف كبيرة.',
    'url'                  => 'صيغة الرابط :attribute غير صحيحة.',
    'uuid'                 => 'يجب أن يكون :attribute بصيغة UUID صحيحة.',

    /*
    |--------------------------------------------------------------------------
    | أسماء الخصائص (Custom Attribute Names)
    |--------------------------------------------------------------------------
    |
    | هنا تستطيع استبدال أسماء الحقول الافتراضية بأسماء أوضح للمستخدم.
    | مثلاً، بدل "email" يظهر "البريد الإلكتروني".
    |
    */

    'attributes' => [
        'avatar' => 'الصورة الشخصية',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
    ],

];
