<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور - مديرية التربية لولاية المغير</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Tajawal', sans-serif;
            text-align: right;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 0;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .email-header {
            background: linear-gradient(135deg, #6b21a8 0%, #8b5cf6 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        
        .email-logo {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .email-title {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        
        .email-subtitle {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
            font-weight: 500;
        }
        
        .email-body {
            padding: 30px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .message {
            margin-bottom: 25px;
            font-size: 16px;
        }
        
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn-reset {
            background: linear-gradient(to right, #6b21a8, #8b5cf6);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(139, 92, 246, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-reset:hover {
            background: linear-gradient(to right, #5b1a91, #7c4ddd);
            box-shadow: 0 6px 15px rgba(139, 92, 246, 0.4);
        }
        
        .warning {
            background-color: #fff7ed;
            border-right: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin: 25px 0;
            font-weight: 500;
        }
        
        .warning-icon {
            color: #f59e0b;
            margin-left: 8px;
        }
        
        .link-container {
            background-color: #f1f5f9;
            padding: 15px;
            border-radius: 8px;
            margin: 25px 0;
            word-break: break-all;
        }
        
        .link-text {
            color: #6b21a8;
            text-decoration: none;
            font-family: monospace;
            font-size: 14px;
        }
        
        .email-footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }
        
        .divider {
            border: none;
            height: 1px;
            background-color: #e2e8f0;
            margin: 25px 0;
        }
        
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .email-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('assets/img/brand/logo57.png') }}" alt="شعار مديرية التربية" class="email-logo">
            <h1 class="email-title">مديرية التربية لولاية المغير</h1>
            <p class="email-subtitle">إعادة تعيين كلمة المرور</p>
        </div>
        
        <div class="email-body">
            <p class="greeting">مرحباً،</p>
            
            <p class="message">لقد تلقينا طلباً لإعادة تعيين كلمة المرور لحسابك. إذا كنت قد طلبت ذلك، يمكنك إعادة تعيين كلمة المرور عبر الزر أدناه:</p>
            
            <div class="btn-container">
                <a href="{{ $actionUrl }}" class="btn-reset">إعادة تعيين كلمة المرور</a>
            </div>
            
            <div class="warning">
                <span class="warning-icon">⚠️</span>
                <strong>يرجى ملاحظة أن هذا الرابط صالح لمدة 60 دقيقة فقط.</strong>
            </div>
            
            <p class="message">إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذه الرسالة، ولن يتم إجراء أي تغييرات على حسابك. للحفاظ على أمان حسابك، لا تشارك هذا الرابط مع أي شخص.</p>
            
            <p class="message">إذا واجهت مشكلة في استخدام الزر أعلاه، يمكنك نسخ الرابط التالي ولصقه في متصفح الويب الخاص بك:</p>
            
            <div class="link-container">
                <a href="{{ $actionUrl }}" class="link-text">{{ $displayableActionUrl }}</a>
            </div>
        </div>
        
        <div class="email-footer">
            <p>{{ now()->year }} © مديرية التربية لولاية المغير - جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
