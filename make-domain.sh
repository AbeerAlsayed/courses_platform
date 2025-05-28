#!/bin/bash

# 1. تحقق من اسم الدومين
if [ -z "$1" ]; then
    echo "❌ يرجى إدخال اسم الدومين. مثال: ./make-domain.sh Auth"
    exit 1
fi

DOMAIN=$1
BASE_DIR="app/Domains/${DOMAIN}"

# 2. المجلدات الأساسية داخل كل دومين
FOLDERS=("Actions" "DTOs" "Models" "Enums" "Requests" "Policies" "Queries" "States")

echo "🚧 جاري إنشاء الدومين: $DOMAIN ..."

# 3. إنشاء المجلدات
for folder in "${FOLDERS[@]}"
do
    mkdir -p "$BASE_DIR/$folder"
    echo "📁 أنشئ: $BASE_DIR/$folder"
done

# 4. توليد ملفات مبدئية
cat > "$BASE_DIR/Actions/SampleAction.php" <<EOL
<?php

namespace App\\Domains\\$DOMAIN\\Actions;

class SampleAction
{
    public function handle()
    {
        // TODO: تنفيذ الأكشن
    }
}
EOL

cat > "$BASE_DIR/DTOs/SampleDTO.php" <<EOL
<?php

namespace App\\Domains\\$DOMAIN\\DTOs;

class SampleDTO
{
    public function __construct()
    {
        // TODO: بيانات النقل
    }
}
EOL

cat > "$BASE_DIR/Enums/SampleStatus.php" <<EOL
<?php

namespace App\\Domains\\$DOMAIN\\Enums;

enum SampleStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
EOL

echo "✅ تم إنشاء دومين $DOMAIN بنجاح."
