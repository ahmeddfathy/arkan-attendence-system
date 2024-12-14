<?php
namespace App\Imports;

use App\Models\User;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * يقوم هذا الدالة بإنشاء السجلات في قاعدة البيانات.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User([
            'name' => $row[0],  // تأكد من أن اسم العمود في ملف Excel مطابق
            'email' => $row[1],  // تأكد من اسم العمود
            'password' => bcrypt($row[2]),  // تأكد من أن الحقل يوجد في ملفك
        ]);
    }
}
