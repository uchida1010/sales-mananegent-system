<?php

namespace App\Http\Requests;

use App\Enums\ClosingDay;
use App\Enums\DueType;
use App\Enums\PaymentDay;
use App\Enums\PaymentMonth;
use App\Enums\TermsType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            /**
             * 取引先名を指定します。
             *
             * 新規登録する取引先名を指定してください。
             * 取引先名の入力は必須です。
             * 最大255文字まで入力できます。
             *
             * 入力例：
             * - 田中金属株式会社
             * - 株式会社田中商事
             *
             * @example 田中金属株式会社
             */
            'name' => ['required', 'string', 'max:255'],

            /**
             * 取引先名のよみがなを指定します。
             *
             * 新規登録する取引先名のよみがなを指定してください。
             * 必要がない場合は省略できます。
             * 最大255文字まで入力できます。
             *
             * 入力例：
             * - たなかきんぞくかぶしきかいしゃ
             * - かぶしきかいしゃたなかしょうじ
             *
             * @example たなかきんぞくかぶしきかいしゃ
             */
            'kana' => ['nullable', 'string', 'max:255'],

            /**
             * 取引先名の略称を指定します。
             *
             * 新規登録する取引先名の略称を指定してください。
             * 必要がない場合は省略できます。
             * 最大255文字まで入力できます。
             *
             * 入力例：
             * - 田中金属
             * - 田中商事
             *
             * @example 田中金属
             */
            'short_name' => ['nullable', 'string', 'max:255'],

            /**
             * 住所を指定します。
             *
             * 新規登録する取引先名の住所を指定してください。
             * 必要がない場合は省略できます。
             * 最大255文字まで入力できます。
             *
             * 入力例：
             * - 大阪府大阪市淀川区1-1-1
             * - 東京都新宿区1-1-1
             *
             * @example 大阪府大阪市淀川区1-1-1
             */
            'address' => ['nullable', 'string', 'max:255'],

            /**
             * TELを指定します。
             *
             * 新規登録する取引先のTELを指定してください。
             * 必要がない場合は省略できます。
             * 最大20文字まで入力できます。
             *
             * 入力例：
             * - 123-456-7890
             * - 1234567890
             *
             * @example　1234567890
             */
            'tel' => ['nullable', 'string', 'max:20'],

            /**
             * FAXを指定します。
             *
             * 新規登録する取引先のFAXを指定してください。
             * 必要がない場合は省略できます。
             * 最大20文字まで入力できます。
             *
             * 入力例：
             * - 123-456-7890
             * - 1234567890
             *
             * @example　1234567890
             */
            'fax' => ['nullable', 'string', 'max:20'],

            /**
             * 取引先の社内担当者を指定します。
             *
             * 新規登録する取引先を担当するユーザーIDを指定してください。
             * usersテーブルに登録されているIDのみ指定できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 1
             * - 3
             *
             * @example 1
             */
            'person_in_charge_id' => ['nullable', 'integer', 'exists:users,id'],

            /**
             * リード獲得元を指定します。
             *
             * 新規登録する取引先のリード獲得元IDを指定してください。
             * lead_sourcesテーブルに登録されているIDのみ指定できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 1
             * - 2
             *
             * @example 1
             */
            'lead_source_id' => ['required', 'integer', 'exists:lead_sources,id'],

            /**
             * 取引先の有効・無効を指定します。
             *
             * 新規登録する取引先が有効か無効かを指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - true
             * - false
             *
             * @example true
             */
            'is_active' => ['nullable', 'boolean'],

            /**
             * 取引先の備考を指定します。
             *
             * 新規登録する取引先に関する補足情報や注意事項を指定してください。
             * 最大1000文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 毎月の月末に受領書を送付する。
             *
             * @example 納品前に担当者への電話連絡が必要。
             */
            'remarks' => ['nullable', 'string', 'max:1000'],

            /*
            |--------------------------------------------------------------------------
            | 支払条件
            |--------------------------------------------------------------------------
            */

            /**
             * 支払方法の種類を指定します。
             *
             * 新規登録する取引先の支払方法を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - closing（締め払い）
             * - each_time（都度払い）
             *
             * @example closing
             */
            'payment_type' => ['nullable', Rule::enum(TermsType::class)],

            /**
             * 支払締日の種類を指定します。
             *
             * 新規登録する取引先の支払締日を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 10（10日締め）
             * - 15（15日締め）
             * - 20（20日締め）
             * - 25（25日締め）
             * - end_of_month（月末締め）
             *
             * @example end_of_month
             */
            'payment_closing_day' => ['nullable', Rule::enum(ClosingDay::class)],

            /**
             * 支払月の種類を指定します。
             *
             * 新規登録する取引先の支払月を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - next_month（翌月）
             * - month_after_next（翌々月）
             *
             * @example next_month
             */
            'payment_month' => ['nullable', Rule::enum(PaymentMonth::class)],

            /**
             * 支払日の種類を指定します。
             *
             * 新規登録する取引先の支払日を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 5（5日払い）
             * - 10（10日払い）
             * - 15（15日払い）
             * - 20（20日払い）
             * - 25（25日払い）
             * - end_of_month（月末払い）
             *
             * @example end_of_month
             */
            'payment_day' => ['nullable', Rule::enum(PaymentDay::class)],

            /**
             * 支払期日の種類を指定します。
             *
             * 新規登録する取引先の支払期日の種類を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - advance（前金）
             * - three_business_days（3営業日）
             * - seven_business_days（7営業日）
             * - immediate（都度／即時）
             *
             * @example seven_business_days
             */
            'payment_due_type' => ['nullable', Rule::enum(DueType::class)],

            /*
            |--------------------------------------------------------------------------
            | 請求条件
            |--------------------------------------------------------------------------
            */

            /**
             * 請求方法の種類を指定します。
             *
             * 新規登録する取引先の請求方法を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - closing（締め請求）
             * - each_time（都度請求）
             *
             * @example closing
             */
            'billing_type' => ['nullable', Rule::enum(TermsType::class)],

            /**
             * 請求締日の種類を指定します。
             *
             * 新規登録する取引先の請求締日を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 10（10日締め）
             * - 15（15日締め）
             * - 20（20日締め）
             * - 25（25日締め）
             * - end_of_month（月末締め）
             *
             * @example end_of_month
             */
            'billing_closing_day' => ['nullable', Rule::enum(ClosingDay::class)],

            /**
             * 請求に対する支払月の種類を指定します。
             *
             * 新規登録する取引先の請求に対する支払月を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - next_month（翌月）
             * - month_after_next（翌々月）
             *
             * @example next_month
             */
            'billing_payment_month' => ['nullable', Rule::enum(PaymentMonth::class)],

            /**
             * 請求に対する支払日の種類を指定します。
             *
             * 新規登録する取引先の請求に対する支払日を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 5（5日払い）
             * - 10（10日払い）
             * - 15（15日払い）
             * - 20（20日払い）
             * - 25（25日払い）
             * - end_of_month（月末払い）
             *
             * @example end_of_month
             */
            'billing_payment_day' => ['nullable', Rule::enum(PaymentDay::class)],

            /**
             * 請求に対する支払期日の種類を指定します。
             *
             * 新規登録する取引先の請求に対する支払期日を指定してください。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - advance（前金）
             * - three_business_days（3営業日）
             * - seven_business_days（7営業日）
             * - immediate（都度／即時）
             *
             * @example seven_business_days
             */
            'billing_due_type' => ['nullable', Rule::enum(DueType::class)],

            /*
            |--------------------------------------------------------------------------
            | 連絡先(customer_contacts)
            |--------------------------------------------------------------------------
            */

            /**
             * 取引先の取引先担当者情報を指定します。
             *
             * 新規登録する取引先に紐づく取引先担当者情報を配列で指定してください。
             * 取引先担当者は最低1件以上の登録が必須です。
             * 複数の取引先担当者を登録することもできます。
             *
             * 入力例：
             * - 連絡先1：田中太郎、tanaka@example.com
             * - 連絡先2：山田花子、yamada@example.com
             *
             * @example [{"name":"田中太郎","email":"tanaka@example.com"}]
             */
            'contacts' => ['required', 'array', 'min:1'],

            /**
             * 取引先担当者の氏名を指定します。
             *
             * 新規登録する取引先に紐づく各取引先担当者の氏名を指定してください。
             * すべての取引先担当者で氏名の入力が必須です。
             * 最大255文字まで入力できます。
             *
             * 入力例：
             * - 田中太郎
             * - 山田花子
             *
             * @example 田中太郎
             */
            'contacts.*.name' => ['required', 'string', 'max:255'],

            /**
             * 取引先担当者の電話番号(代表)を指定します。
             *
             * 新規登録する取引先に紐づく各取引先担当者の電話番号(代表)を指定してください。
             * ハイフンを含む電話番号も入力できます。
             * 最大20文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 123-456-7890
             * - 1234567890
             *
             * @example 123-456-7890
             */
            'contacts.*.tel' => ['nullable', 'string', 'max:20'],

            /**
             * 取引先担当者の携帯電話番号を指定します。
             *
             * 新規登録する取引先に紐づく各取引先担当者の携帯電話番号を指定してください。
             * ハイフンを含む携帯電話番号も入力できます。
             * 最大20文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 090-1234-5678
             * - 080-1234-5678
             *
             * @example 090-1234-5678
             */
            'contacts.*.mobile' => ['nullable', 'string', 'max:20'],

            /**
             * 取引先担当者のメールアドレスを指定します。
             *
             * 新規登録する取引先に紐づく各取引先担当者のメールアドレスを指定してください。
             * 有効なメールアドレス形式で入力してください。
             * 最大255文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - tanaka@example.com
             * - yamada@example.com
             *
             * @example tanaka@example.com
             */
            'contacts.*.email' => ['nullable', 'email', 'max:255'],

            /**
             * 取引先担当者の社内担当者を指定します。
             *
             * 新規登録する取引先に紐づく各取引先担当者の社内担当者を指定してください。
             * usersテーブルに存在するユーザーIDを指定します。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 1（田中太郎）
             * - 2（山田花子）
             *
             * @example 1
             */
            'contacts.*.person_in_charge_id' => ['nullable', 'integer', 'exists:users,id'],

            /**
             * 取引先担当者の役割を指定します。
             *
             * 新規登録する取引先に紐づく各取引先担当者の役割を指定してください。
             * 最大255文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - メイン担当者
             * - 経理担当者
             *
             * @example メイン担当者
             */
            'contacts.*.role' => ['nullable', 'string', 'max:255'],

            /**
             * 取引先担当者の役職を指定します。
             *
             * 新規登録する取引先に紐づく各取引先担当者の役職を指定してください。
             * 最大255文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 営業部長
             * - 代表取締役
             *
             * @example 営業部長
             */
            'contacts.*.position' => ['nullable', 'string', 'max:255'],

            /**
             * 取引先担当者の有効状態を指定します。
             *
             * 新規登録する取引先に紐づく各取引先担当者の有効状態を指定してください。
             * trueの場合は有効、falseの場合は無効として扱います。
             * 必要がない場合は省略でき、省略時はtrue（有効）として登録します。
             *
             * 入力例：
             * - true（有効）
             * - false（無効）
             *
             * @example true
             */
            'contacts.*.is_active' => ['sometimes', 'boolean'],

            /**
             * 取引先担当者の備考を指定します。
             *
             * 新規登録する取引先に紐づく各取引先担当者の補足情報や注意事項を指定してください。
             * 最大1000文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 連絡は基本的にメールで対応する
             *
             * @example 緊急時は携帯電話に連絡する。
             */
            'contacts.*.remarks' => ['nullable', 'string', 'max:1000'],

            /*
            |--------------------------------------------------------------------------
            | 配送情報(customer_delivery_addresses)
            |--------------------------------------------------------------------------
            */

            /**
             * 取引先の配送情報を指定します。
             *
             * 新規登録する取引先に紐づく配送情報を配列で指定してください。
             * 複数の配送先を登録することもできます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 配送先1：田中金属株式会社 大阪工場
             * - 配送先2：株式会社田中商事 東京営業所
             *
             * @example [{"name":"田中金属株式会社 大阪工場"},{"name":"株式会社田中商事 東京営業所"}]
             */
            'deliveries' => ['nullable', 'array'],

            /**
             * 配送情報の出荷元名または納品先名を指定します。
             *
             * 新規登録する取引先に紐づく各出荷元名または納品先名を指定してください。
             * 配送情報を登録する場合は、すべての出荷元名または納品先名の入力が必須です。
             * 最大255文字まで入力できます。
             *
             * 入力例：
             * - 田中金属株式会社 大阪工場
             * - 株式会社田中商事 東京営業所
             *
             * @example 田中金属株式会社 大阪工場
             */
            'deliveries.*.name' => ['required', 'string', 'max:255'],

            /**
             * 出荷元または納品先の担当者名を指定します。
             *
             * 新規登録する取引先に紐づく各配送先の担当者名を指定してください。
             * 最大255文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 田中太郎
             * - 山田花子
             *
             * @example 田中太郎
             */
            'deliveries.*.contact_name' => ['nullable', 'string', 'max:255'],

            /**
             * 出荷元または納品先の住所を指定します。
             *
             * 新規登録する取引先に紐づく各出荷元または納品先の住所を指定してください。
             * 配送情報を登録する場合は、すべての出荷元または納品先で住所の入力が必須です。
             * 最大255文字まで入力できます。
             *
             * 入力例：
             * - 大阪府大阪市北区梅田1-1-1
             * - 東京都千代田区丸の内1-2-3
             * - 京都府京都市中京区烏丸通1-2-3
             *
             * @example 大阪府大阪市北区梅田1-1-1
             */
            'deliveries.*.address' => ['required', 'string', 'max:255'],

            /**
             * 出荷元または納品先の電話番号を指定します。
             *
             * 新規登録する取引先に紐づく各出荷元または納品先の電話番号を指定してください。
             * ハイフンを含む電話番号も入力できます。
             * 最大20文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 123-456-7890
             * - 1234567890
             *
             * @example 123-456-7890
             */
            'deliveries.*.tel' => ['nullable', 'string', 'max:20'],

            /**
             * 出荷元または納品先の備考を指定します。
             *
             * 新規登録する取引先に紐づく各出荷元または納品先の補足情報や注意事項を指定してください。
             * 最大1000文字まで入力できます。
             * 必要がない場合は省略できます。
             *
             * 入力例：
             * - 到着の10分前にに担当者への電話連絡が必要。
             * - 入庫可能時間：13時から15時
             *
             * @example 到着の10分前にに担当者への電話連絡が必要。
             */
            'deliveries.*.remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attributeは必須です。',
            'string' => ':attributeは文字列で入力してください。',
            'integer' => ':attributeは数値で入力してください。',
            'boolean' => ':attributeの入力内容が不正です。',
            'array' => ':attributeの入力内容が不正です。',
            'max.string' => ':attributeは:max文字以内で入力してください。',
            'email' => ':attributeはメールアドレス形式で入力してください。',
            'exists' => '選択した:attributeが存在しません。',
            'enum' => ':attributeの選択内容が不正です。',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '取引先名',
            'kana' => 'よみがな',
            'short_name' => '略称',
            'address' => '住所',
            'tel' => '電話番号',
            'fax' => 'FAX',
            'person_in_charge_id' => '社内担当者',
            'lead_source_id' => 'リード獲得元',
            'is_active' => '有効状態',
            'remarks' => '備考',

            'payment_type' => '支払条件の支払方法',
            'payment_closing_day' => '支払条件の締日',
            'payment_month' => '支払条件の支払月',
            'payment_day' => '支払条件の支払日',
            'payment_due_type' => '支払条件の支払期日',

            'billing_type' => '請求条件の請求方法',
            'billing_closing_day' => '請求条件の締日',
            'billing_payment_month' => '請求条件の支払月',
            'billing_payment_day' => '請求条件の支払日',
            'billing_due_type' => '請求条件の支払期日',

            'contacts' => '連絡先',
            'contacts.*.name' => '氏名',
            'contacts.*.tel' => '連絡先電話番号',
            'contacts.*.mobile' => '連絡先電話番号',
            'contacts.*.email' => '連絡先メールアドレス',
            'contacts.*.person_in_charge_id' => '社内担当者',
            'contacts.*.role' => '役割',
            'contacts.*.position' => '役割',
            'contacts.*.remarks' => '連絡先備考',

            'deliveries' => '配送情報',
            'deliveries.*.name' => '出荷元または納品先名',
            'deliveries.*.contact_name' => '配送情報担当者名',
            'deliveries.*.address' => '配送情報住所',
            'deliveries.*.tel' => '配送情報電話番号',
            'deliveries.*.remarks' => '配送情報備考',
        ];
    }
}
