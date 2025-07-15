/**
 * Charts Diagnostics Tool
 * يساعد في تشخيص مشاكل الرسوم البيانية
 */

(function() {
    'use strict';
    
    console.log('🔍 بدء تشخيص الرسوم البيانية...');
    
    // تشخيص Chart.js
    function diagnoseChartJS() {
        console.log('📊 فحص Chart.js...');
        
        if (typeof Chart === 'undefined') {
            console.error('❌ Chart.js غير محمل');
            return false;
        }
        
        console.log('✅ Chart.js محمل بنجاح');
        console.log('📋 إصدار Chart.js:', Chart.version || 'غير معروف');
        return true;
    }
    
    // تشخيص Canvas Elements
    function diagnoseCanvasElements() {
        console.log('🎨 فحص عناصر Canvas...');
        
        const expectedCharts = [
            'revenueChart',
            'categoryChart', 
            'customerSegmentsChart',
            'marketShareChart',
            'performanceChart',
            'goalsChart',
            'riskMatrixChart',
            'revenueProfitChart',
            'marginTrendsChart'
        ];
        
        const foundCanvases = [];
        const missingCanvases = [];
        
        expectedCharts.forEach(chartId => {
            const canvas = document.getElementById(chartId);
            if (canvas) {
                foundCanvases.push(chartId);
                console.log(`✅ تم العثور على: ${chartId}`);
            } else {
                missingCanvases.push(chartId);
                console.log(`❌ مفقود: ${chartId}`);
            }
        });
        
        console.log(`📊 تم العثور على ${foundCanvases.length} من ${expectedCharts.length} رسوم بيانية`);
        
        return {
            found: foundCanvases,
            missing: missingCanvases,
            total: expectedCharts.length
        };
    }
    
    // تشخيص البيانات
    function diagnoseData() {
        console.log('📈 فحص البيانات...');
        
        if (typeof window.analyticsData === 'undefined') {
            console.warn('⚠️ window.analyticsData غير موجود');
            return false;
        }
        
        console.log('✅ window.analyticsData موجود');
        console.log('📋 البيانات المتاحة:', Object.keys(window.analyticsData));
        
        return true;
    }
    
    // تشخيص Chart Instances
    function diagnoseChartInstances() {
        console.log('🔧 فحص مثيلات الرسوم البيانية...');
        
        if (typeof window.chartInstances === 'undefined') {
            console.warn('⚠️ window.chartInstances غير موجود');
            return [];
        }
        
        const instances = Object.keys(window.chartInstances);
        console.log(`✅ تم إنشاء ${instances.length} مثيل رسم بياني`);
        instances.forEach(instance => {
            console.log(`📊 مثيل نشط: ${instance}`);
        });
        
        return instances;
    }
    
    // تشخيص الأخطاء
    function diagnoseErrors() {
        console.log('🚨 فحص الأخطاء...');
        
        // تسجيل أخطاء JavaScript
        const originalError = window.onerror;
        window.onerror = function(message, source, lineno, colno, error) {
            console.error('🚨 خطأ JavaScript:', {
                message,
                source,
                line: lineno,
                column: colno,
                error
            });
            
            if (originalError) {
                return originalError.apply(this, arguments);
            }
        };
        
        // تسجيل Promise rejections
        window.addEventListener('unhandledrejection', function(event) {
            console.error('🚨 Promise rejection:', event.reason);
        });
    }
    
    // إنشاء تقرير التشخيص
    function generateDiagnosticReport() {
        console.log('📋 إنشاء تقرير التشخيص...');
        
        const report = {
            timestamp: new Date().toISOString(),
            chartJS: diagnoseChartJS(),
            canvases: diagnoseCanvasElements(),
            data: diagnoseData(),
            instances: diagnoseChartInstances(),
            userAgent: navigator.userAgent,
            url: window.location.href
        };
        
        console.log('📊 تقرير التشخيص الكامل:', report);
        
        return report;
    }
    
    // عرض التقرير في الواجهة
    function displayDiagnosticUI(report) {
        const diagnosticDiv = document.createElement('div');
        diagnosticDiv.id = 'charts-diagnostic-panel';
        diagnosticDiv.style.cssText = `
            position: fixed;
            top: 10px;
            right: 10px;
            width: 300px;
            max-height: 400px;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-family: 'Cairo', monospace;
            font-size: 12px;
            z-index: 10000;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        `;
        
        const canvasStatus = report.canvases.found.length === report.canvases.total ? '✅' : '❌';
        const chartJSStatus = report.chartJS ? '✅' : '❌';
        const dataStatus = report.data ? '✅' : '❌';
        const instancesCount = report.instances.length;
        
        diagnosticDiv.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <strong>🔍 تشخيص الرسوم البيانية</strong>
                <button onclick="this.parentElement.parentElement.remove()" style="background: #f56565; color: white; border: none; border-radius: 4px; padding: 4px 8px; cursor: pointer;">✕</button>
            </div>
            
            <div style="margin-bottom: 8px;">
                ${chartJSStatus} Chart.js: ${report.chartJS ? 'محمل' : 'غير محمل'}
            </div>
            
            <div style="margin-bottom: 8px;">
                ${canvasStatus} Canvas Elements: ${report.canvases.found.length}/${report.canvases.total}
            </div>
            
            <div style="margin-bottom: 8px;">
                ${dataStatus} البيانات: ${report.data ? 'متوفرة' : 'غير متوفرة'}
            </div>
            
            <div style="margin-bottom: 8px;">
                📊 المثيلات النشطة: ${instancesCount}
            </div>
            
            ${report.canvases.missing.length > 0 ? `
                <div style="margin-top: 10px; padding: 8px; background: rgba(245, 101, 101, 0.2); border-radius: 4px;">
                    <strong>❌ رسوم مفقودة:</strong><br>
                    ${report.canvases.missing.map(id => `• ${id}`).join('<br>')}
                </div>
            ` : ''}
            
            <div style="margin-top: 10px; font-size: 10px; opacity: 0.7;">
                ${new Date().toLocaleString('ar-EG')}
            </div>
        `;
        
        document.body.appendChild(diagnosticDiv);
        
        // إخفاء تلقائي بعد 10 ثوان
        setTimeout(() => {
            if (diagnosticDiv.parentElement) {
                diagnosticDiv.style.opacity = '0';
                diagnosticDiv.style.transition = 'opacity 0.5s';
                setTimeout(() => diagnosticDiv.remove(), 500);
            }
        }, 10000);
    }
    
    // تشغيل التشخيص
    function runDiagnostics() {
        console.log('🚀 تشغيل التشخيص الشامل...');
        
        // تأخير للسماح للصفحة بالتحميل
        setTimeout(() => {
            diagnoseErrors();
            const report = generateDiagnosticReport();
            displayDiagnosticUI(report);
            
            // إضافة التقرير إلى النافذة للوصول إليه من وحدة التحكم
            window.chartsDiagnosticReport = report;
            
            console.log('✅ انتهى التشخيص. يمكنك الوصول للتقرير عبر: window.chartsDiagnosticReport');
        }, 1000);
    }
    
    // تشغيل التشخيص عند تحميل الصفحة
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runDiagnostics);
    } else {
        runDiagnostics();
    }
    
    // إضافة اختصار لوحة المفاتيح لإعادة تشغيل التشخيص
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'D') {
            e.preventDefault();
            runDiagnostics();
        }
    });
    
    console.log('💡 نصيحة: اضغط Ctrl+Shift+D لإعادة تشغيل التشخيص');
    
})();
