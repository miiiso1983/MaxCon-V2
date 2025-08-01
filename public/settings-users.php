<?php
// User Management Settings - Direct access without Laravel routing
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - MaxCon ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f7fafc;
            line-height: 1.6;
            color: #2d3748;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            text-align: center;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title i {
            margin-left: 12px;
            color: #667eea;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #e2e8f0;
        }

        .table th {
            background: #f8fafc;
            font-weight: 600;
            color: #4a5568;
        }

        .table tr:hover {
            background: #f7fafc;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-left: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-details h4 {
            margin: 0;
            color: #2d3748;
            font-size: 14px;
        }

        .user-details p {
            margin: 0;
            color: #718096;
            font-size: 12px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .role-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin: 2px;
            display: inline-block;
        }

        .role-admin {
            background: #fef3c7;
            color: #92400e;
        }

        .role-manager {
            background: #dbeafe;
            color: #1e40af;
        }

        .role-user {
            background: #e0e7ff;
            color: #5b21b6;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #718096;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4a5568;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .alert-info {
            background: #e6fffa;
            border: 1px solid #81e6d9;
            color: #234e52;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #718096;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 12px;
            }
            
            .btn-sm {
                padding: 4px 8px;
                font-size: 10px;
            }
            
            .modal-content {
                margin: 10% auto;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-users-cog" style="margin-left: 15px;"></i>
                إدارة المستخدمين
            </h1>
            <p>إدارة حسابات المستخدمين والأدوار والصلاحيات</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" style="color: #667eea;">12</div>
                <div class="stat-label">إجمالي المستخدمين</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #10b981;">9</div>
                <div class="stat-label">المستخدمين النشطين</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #f59e0b;">3</div>
                <div class="stat-label">المديرين</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #ef4444;">3</div>
                <div class="stat-label">المستخدمين المعطلين</div>
            </div>
        </div>

        <!-- Alert -->
        <div class="alert alert-info">
            <h4 style="margin-bottom: 10px;">💡 ملاحظة:</h4>
            <p>هذه صفحة تجريبية لإدارة المستخدمين. في النسخة النهائية، ستكون متصلة بقاعدة البيانات مع نظام أمان متقدم.</p>
        </div>

        <!-- Users Management -->
        <div class="card">
            <div class="card-title">
                <div>
                    <i class="fas fa-users"></i>
                    قائمة المستخدمين
                </div>
                <button class="btn btn-primary" onclick="openAddUserModal()">
                    <i class="fas fa-plus"></i>
                    إضافة مستخدم جديد
                </button>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الأدوار</th>
                        <th>الحالة</th>
                        <th>آخر دخول</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="usersTable">
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">أح</div>
                                <div class="user-details">
                                    <h4>أحمد محمد</h4>
                                    <p>مدير النظام</p>
                                </div>
                            </div>
                        </td>
                        <td>ahmed@maxcon.app</td>
                        <td>
                            <span class="role-badge role-admin">مدير</span>
                            <span class="role-badge role-manager">مدير مبيعات</span>
                        </td>
                        <td><span class="status-badge status-active">نشط</span></td>
                        <td>منذ 5 دقائق</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editUser(1)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(1)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">فا</div>
                                <div class="user-details">
                                    <h4>فاطمة علي</h4>
                                    <p>محاسبة</p>
                                </div>
                            </div>
                        </td>
                        <td>fatima@maxcon.app</td>
                        <td>
                            <span class="role-badge role-user">محاسب</span>
                        </td>
                        <td><span class="status-badge status-active">نشط</span></td>
                        <td>منذ ساعة</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editUser(2)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(2)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">مح</div>
                                <div class="user-details">
                                    <h4>محمد حسن</h4>
                                    <p>مندوب مبيعات</p>
                                </div>
                            </div>
                        </td>
                        <td>mohammed@maxcon.app</td>
                        <td>
                            <span class="role-badge role-user">مبيعات</span>
                        </td>
                        <td><span class="status-badge status-inactive">معطل</span></td>
                        <td>منذ 3 أيام</td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="activateUser(3)">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="editUser(3)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(3)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Back Button -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="https://www.maxcon.app" class="btn btn-success">
                <i class="fas fa-home"></i>
                العودة للنظام
            </a>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeAddUserModal()">&times;</span>
            <h2 style="margin-bottom: 20px; color: #2d3748;">إضافة مستخدم جديد</h2>
            
            <form id="addUserForm">
                <div class="form-group">
                    <label class="form-label">الاسم الكامل *</label>
                    <input type="text" class="form-input" id="userName" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">البريد الإلكتروني *</label>
                    <input type="email" class="form-input" id="userEmail" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">كلمة المرور *</label>
                    <input type="password" class="form-input" id="userPassword" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">الدور</label>
                    <select class="form-input" id="userRole">
                        <option value="user">مستخدم عادي</option>
                        <option value="manager">مدير</option>
                        <option value="admin">مدير النظام</option>
                    </select>
                </div>
                
                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        إضافة المستخدم
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeAddUserModal()" style="margin-right: 15px; background: #e2e8f0; color: #4a5568;">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddUserModal() {
            document.getElementById('addUserModal').style.display = 'block';
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').style.display = 'none';
            document.getElementById('addUserForm').reset();
        }

        // User management functions
        function editUser(userId) {
            alert('تحرير المستخدم رقم: ' + userId + '\nهذه الوظيفة ستكون متاحة في النسخة النهائية.');
        }

        function deleteUser(userId) {
            if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
                alert('تم حذف المستخدم رقم: ' + userId + '\nهذه الوظيفة ستكون متاحة في النسخة النهائية.');
            }
        }

        function activateUser(userId) {
            alert('تم تفعيل المستخدم رقم: ' + userId + '\nهذه الوظيفة ستكون متاحة في النسخة النهائية.');
        }

        // Form submission
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                alert('تم إضافة المستخدم بنجاح!\nهذه الوظيفة ستكون متاحة في النسخة النهائية.');
                closeAddUserModal();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addUserModal');
            if (event.target == modal) {
                closeAddUserModal();
            }
        }
    </script>
</body>
</html>
