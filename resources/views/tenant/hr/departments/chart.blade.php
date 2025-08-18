@extends('layouts.modern')

@section('title', 'الهيكل التنظيمي')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">الهيكل التنظيمي</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">عرض شامل للهيكل التنظيمي للشركة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.departments.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للأقسام
                </a>
            </div>
        </div>
    </div>

    <!-- Chart Controls -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <button onclick="expandAll()" style="background: #48bb78; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-expand-arrows-alt"></i> توسيع الكل
            </button>
            <button onclick="collapseAll()" style="background: #f56565; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-compress-arrows-alt"></i> طي الكل
            </button>
            <button onclick="printChart()" style="background: #4299e1; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-print"></i> طباعة
            </button>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button onclick="exportAsImage('png')" style="background: #ed8936; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-file-image"></i> PNG
                </button>
                <button onclick="exportAsImage('jpeg')" style="background: #dd6b20; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-file-image"></i> JPG
                </button>
                <a href="{{ route('tenant.hr.departments.export.pdf') }}" style="background: #2d3748; color: white; padding: 10px 15px; border-radius: 8px; font-weight: 600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('tenant.hr.departments.export.excel') }}" style="background: #38a169; color: white; padding: 10px 15px; border-radius: 8px; font-weight: 600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('tenant.hr.departments.export.html') }}" style="background: #3182ce; color: white; padding: 10px 15px; border-radius: 8px; font-weight: 600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-code"></i> HTML
                </a>
            </div>
        </div>
    </div>

    <!-- Organizational Chart -->
    <div id="org-chart" style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">

        <!-- CEO Level -->
        <div style="text-align: center; margin-bottom: 40px;">
            <div class="org-node" style="display: inline-block; background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 20px; border-radius: 15px; box-shadow: 0 10px 30px rgba(159, 122, 234, 0.3); cursor: pointer; transition: transform 0.3s;"
                 onclick="toggleNode(this)"
                 onmouseover="this.style.transform='translateY(-5px)'"
                 onmouseout="this.style.transform='translateY(0)'">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div style="text-align: right;">
                        <h3 style="margin: 0; font-size: 20px; font-weight: 700;">المدير العام</h3>
                        <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">أحمد محمد علي</p>
                        <p style="margin: 5px 0 0 0; font-size: 12px; opacity: 0.8;">الإدارة العامة</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Connection Line -->
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 2px; height: 30px; background: #e2e8f0; margin: 0 auto;"></div>
        </div>

        <!-- Department Level -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 40px;">

            <!-- HR Department -->
            <div class="department-branch" style="text-align: center;">
                <div class="org-node" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px; border-radius: 12px; box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3); cursor: pointer; transition: transform 0.3s;"
                     onclick="toggleDepartment(this)"
                     onmouseover="this.style.transform='translateY(-3px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div style="text-align: right; flex: 1;">
                            <h4 style="margin: 0; font-size: 16px; font-weight: 700;">الموارد البشرية</h4>
                            <p style="margin: 3px 0 0 0; font-size: 12px; opacity: 0.9;">سارة أحمد</p>
                            <p style="margin: 3px 0 0 0; font-size: 11px; opacity: 0.8;">15 موظف</p>
                        </div>
                    </div>
                </div>

                <!-- HR Positions -->
                <div class="positions" style="margin-top: 15px; display: none;">
                    <div style="width: 2px; height: 20px; background: #e2e8f0; margin: 0 auto;"></div>
                    <div style="display: grid; gap: 10px;">
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #48bb78;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">مدير الموارد البشرية</div>
                            <div style="font-size: 12px; color: #4a5568;">سارة أحمد</div>
                        </div>
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #48bb78;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">أخصائي توظيف</div>
                            <div style="font-size: 12px; color: #4a5568;">محمد حسن</div>
                        </div>
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #48bb78;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">أخصائي رواتب</div>
                            <div style="font-size: 12px; color: #4a5568;">فاطمة علي</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Finance Department -->
            <div class="department-branch" style="text-align: center;">
                <div class="org-node" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px; border-radius: 12px; box-shadow: 0 8px 25px rgba(237, 137, 54, 0.3); cursor: pointer; transition: transform 0.3s;"
                     onclick="toggleDepartment(this)"
                     onmouseover="this.style.transform='translateY(-3px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div style="text-align: right; flex: 1;">
                            <h4 style="margin: 0; font-size: 16px; font-weight: 700;">المالية والمحاسبة</h4>
                            <p style="margin: 3px 0 0 0; font-size: 12px; opacity: 0.9;">عمر خالد</p>
                            <p style="margin: 3px 0 0 0; font-size: 11px; opacity: 0.8;">12 موظف</p>
                        </div>
                    </div>
                </div>

                <!-- Finance Positions -->
                <div class="positions" style="margin-top: 15px; display: none;">
                    <div style="width: 2px; height: 20px; background: #e2e8f0; margin: 0 auto;"></div>
                    <div style="display: grid; gap: 10px;">
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #ed8936;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">مدير المالية</div>
                            <div style="font-size: 12px; color: #4a5568;">عمر خالد</div>
                        </div>
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #ed8936;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">محاسب أول</div>
                            <div style="font-size: 12px; color: #4a5568;">أحمد يوسف</div>
                        </div>
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #ed8936;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">أمين الصندوق</div>
                            <div style="font-size: 12px; color: #4a5568;">مريم أحمد</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Department -->
            <div class="department-branch" style="text-align: center;">
                <div class="org-node" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px; border-radius: 12px; box-shadow: 0 8px 25px rgba(66, 153, 225, 0.3); cursor: pointer; transition: transform 0.3s;"
                     onclick="toggleDepartment(this)"
                     onmouseover="this.style.transform='translateY(-3px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div style="text-align: right; flex: 1;">
                            <h4 style="margin: 0; font-size: 16px; font-weight: 700;">المبيعات والتسويق</h4>
                            <p style="margin: 3px 0 0 0; font-size: 12px; opacity: 0.9;">ليلى حسن</p>
                            <p style="margin: 3px 0 0 0; font-size: 11px; opacity: 0.8;">20 موظف</p>
                        </div>
                    </div>
                </div>

                <!-- Sales Positions -->
                <div class="positions" style="margin-top: 15px; display: none;">
                    <div style="width: 2px; height: 20px; background: #e2e8f0; margin: 0 auto;"></div>
                    <div style="display: grid; gap: 10px;">
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #4299e1;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">مدير المبيعات</div>
                            <div style="font-size: 12px; color: #4a5568;">ليلى حسن</div>
                        </div>
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #4299e1;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">مندوب مبيعات أول</div>
                            <div style="font-size: 12px; color: #4a5568;">كريم محمد</div>
                        </div>
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #4299e1;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">أخصائي تسويق</div>
                            <div style="font-size: 12px; color: #4a5568;">نور أحمد</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IT Department -->
            <div class="department-branch" style="text-align: center;">
                <div class="org-node" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 15px; border-radius: 12px; box-shadow: 0 8px 25px rgba(245, 101, 101, 0.3); cursor: pointer; transition: transform 0.3s;"
                     onclick="toggleDepartment(this)"
                     onmouseover="this.style.transform='translateY(-3px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <div style="text-align: right; flex: 1;">
                            <h4 style="margin: 0; font-size: 16px; font-weight: 700;">تقنية المعلومات</h4>
                            <p style="margin: 3px 0 0 0; font-size: 12px; opacity: 0.9;">يوسف علي</p>
                            <p style="margin: 3px 0 0 0; font-size: 11px; opacity: 0.8;">8 موظف</p>
                        </div>
                    </div>
                </div>

                <!-- IT Positions -->
                <div class="positions" style="margin-top: 15px; display: none;">
                    <div style="width: 2px; height: 20px; background: #e2e8f0; margin: 0 auto;"></div>
                    <div style="display: grid; gap: 10px;">
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #f56565;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">مدير تقنية المعلومات</div>
                            <div style="font-size: 12px; color: #4a5568;">يوسف علي</div>
                        </div>
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #f56565;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">مطور أول</div>
                            <div style="font-size: 12px; color: #4a5568;">حسام محمد</div>
                        </div>
                        <div style="background: #f7fafc; padding: 10px; border-radius: 8px; border-right: 3px solid #f56565;">
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">مسؤول الشبكات</div>
                            <div style="font-size: 12px; color: #4a5568;">زياد أحمد</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e2e8f0;">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-chart-pie" style="margin-left: 10px; color: #4299e1;"></i>
                إحصائيات الهيكل التنظيمي
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">

                <div style="background: #f7fafc; border-radius: 12px; padding: 20px; text-align: center; border-right: 4px solid #48bb78;">
                    <div style="color: #48bb78; font-size: 32px; font-weight: 700; margin-bottom: 5px;">4</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي الأقسام</div>
                </div>

                <div style="background: #f7fafc; border-radius: 12px; padding: 20px; text-align: center; border-right: 4px solid #4299e1;">
                    <div style="color: #4299e1; font-size: 32px; font-weight: 700; margin-bottom: 5px;">12</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي المناصب</div>
                </div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>

                <div style="background: #f7fafc; border-radius: 12px; padding: 20px; text-align: center; border-right: 4px solid #ed8936;">
                    <div style="color: #ed8936; font-size: 32px; font-weight: 700; margin-bottom: 5px;">55</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي الموظفين</div>
                </div>

                <div style="background: #f7fafc; border-radius: 12px; padding: 20px; text-align: center; border-right: 4px solid #9f7aea;">
                    <div style="color: #9f7aea; font-size: 32px; font-weight: 700; margin-bottom: 5px;">4</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">المستويات الإدارية</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDepartment(element) {
    const positions = element.parentElement.querySelector('.positions');
    if (positions) {
        if (positions.style.display === 'none' || positions.style.display === '') {
            positions.style.display = 'block';
            positions.style.animation = 'fadeIn 0.3s ease-in';
        } else {
            positions.style.display = 'none';
        }
    }
}

function expandAll() {
    const allPositions = document.querySelectorAll('.positions');
    allPositions.forEach(positions => {
        positions.style.display = 'block';
        positions.style.animation = 'fadeIn 0.3s ease-in';
    });
}

function collapseAll() {
    const allPositions = document.querySelectorAll('.positions');
    allPositions.forEach(positions => {
        positions.style.display = 'none';
    });
}

function printChart() {
    window.print();
}

async function exportChart() {
    await exportAsImage('png');
}

async function exportAsImage(type = 'png') {
    const target = document.getElementById('org-chart') || document.querySelector('body');
    const canvas = await html2canvas(target, {scale: 2, useCORS: true, backgroundColor: '#ffffff'});
    const dataUrl = canvas.toDataURL(`image/${type}`);
    const a = document.createElement('a');
    const ts = new Date().toISOString().slice(0,19).replace(/[:T]/g,'-');
    a.href = dataUrl;
    a.download = `OrgChart-${ts}.${type === 'jpeg' ? 'jpg' : type}`;
    a.click();
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media print {
        .positions { display: block !important; }
        button { display: none !important; }
    }
`;
document.head.appendChild(style);
</script>

@endsection
