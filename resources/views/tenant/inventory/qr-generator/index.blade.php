@extends('layouts.modern')

@section('page-title', 'مولد QR كود المنتجات')
@section('page-description', 'إنشاء QR كود للمنتجات المتوفرة للطباعة في الفواتير')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-qrcode" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            مولد QR كود المنتجات 📱
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء QR كود للمنتجات المتوفرة للطباعة في الفواتير
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-boxes" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $totalProducts }} منتج متوفر</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-tags" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $categories->count() }} فئة</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.qr.guide') }}" style="background: rgba(99, 102, 241, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-book"></i>
                    دليل الاستخدام
                </a>
                <a href="{{ route('tenant.inventory.invoice.qr.example') }}" style="background: rgba(59, 130, 246, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-file-invoice"></i>
                    مثال الفاتورة
                </a>
                <a href="{{ route('tenant.inventory.inventory-products.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للمنتجات
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <!-- QR Generator Options -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-cogs" style="color: #10b981;"></i>
            خيارات إنشاء QR كود
        </h3>
        
        <!-- All Products QR -->
        <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px; transition: all 0.3s ease;" id="allProductsCard">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div>
                    <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 16px; font-weight: 600;">جميع المنتجات المتوفرة</h4>
                    <p style="color: #6b7280; margin: 0; font-size: 14px;">QR كود شامل لجميع المنتجات النشطة في المخزون</p>
                    <p style="color: #f59e0b; margin: 5px 0 0 0; font-size: 12px; font-weight: 500;">💡 إذا كانت البيانات كبيرة، استخدم الزر "مبسط" أو QR الفئات</p>
                </div>
                <div style="background: #f0fdf4; color: #166534; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    {{ $totalProducts }} منتج
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr auto auto; gap: 10px;">
                <button onclick="generateAllProductsQR()"
                        style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-qrcode" style="margin-left: 8px;"></i>
                    إنشاء QR كود لجميع المنتجات
                </button>
                <button onclick="generateCompactQR()"
                        style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-compress-alt"></i>
                    مبسط
                </button>
                <button onclick="testConnection()"
                        style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-network-wired"></i>
                </button>
            </div>
        </div>
        
        <!-- Category Products QR -->
        <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
            <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">QR كود حسب الفئة</h4>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اختر الفئة:</label>
                <select id="categorySelect" data-custom-select data-placeholder="اختر فئة..." data-searchable="true" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">اختر فئة...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" data-count="{{ $category->products_count }}">
                            {{ $category->name }} ({{ $category->products_count }} منتج)
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button onclick="generateCategoryQR()" id="categoryQRBtn" disabled
                    style="background: #6b7280; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: not-allowed; width: 100%;">
                <i class="fas fa-qrcode" style="margin-left: 8px;"></i>
                إنشاء QR كود للفئة
            </button>
        </div>
        
        <!-- Invoice QR (Simplified) -->
        <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px;">
            <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">QR كود للفاتورة (مبسط)</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">النوع:</label>
                    <select id="invoiceType" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="all">جميع المنتجات</option>
                        <option value="featured">المنتجات المميزة</option>
                        <option value="category">فئة محددة</option>
                    </select>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحد الأقصى:</label>
                    <input type="number" id="invoiceLimit" min="1" max="50" value="20" 
                           style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                           placeholder="عدد المنتجات">
                </div>
            </div>
            
            <div id="invoiceCategoryDiv" style="margin-bottom: 15px; display: none;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الفئة:</label>
                <select id="invoiceCategorySelect" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">اختر فئة...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <button onclick="generateInvoiceQR()" 
                    style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%;">
                <i class="fas fa-file-invoice" style="margin-left: 8px;"></i>
                إنشاء QR كود للفاتورة
            </button>
        </div>
    </div>
    
    <!-- QR Code Display -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-qrcode" style="color: #3b82f6;"></i>
            QR كود المُنشأ
        </h3>
        
        <div id="qrResult" style="text-align: center; padding: 40px 20px; color: #6b7280; display: none;">
            <div id="qrCodeContainer" style="margin-bottom: 20px;"></div>
            <div id="qrInfo" style="background: #f8fafc; padding: 15px; border-radius: 8px; text-align: right;"></div>
            <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
                <button onclick="downloadQR()" id="downloadBtn" 
                        style="background: #10b981; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-download" style="margin-left: 8px;"></i>
                    تحميل QR
                </button>
                <button onclick="printQR()" id="printBtn"
                        style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-print" style="margin-left: 8px;"></i>
                    طباعة
                </button>
            </div>
        </div>
        
        <div id="qrPlaceholder" style="text-align: center; padding: 60px 40px; color: #6b7280;">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-qrcode"></i>
            </div>
            <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 18px; font-weight: 600;">لم يتم إنشاء QR كود بعد</h4>
            <p style="margin: 0; font-size: 16px;">اختر أحد الخيارات من اليسار لإنشاء QR كود</p>
        </div>
    </div>
</div>

<!-- Multiple QR Code Libraries for Maximum Compatibility -->
<script>
// QR Code generation using multiple fallback methods
window.QRCodeLoaded = false;
window.QRCodeMethods = [];

// Method 1: Try qrcode.js from jsdelivr
function loadQRCodeMethod1() {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js';
        script.onload = function() {
            if (typeof QRCode !== 'undefined') {
                window.QRCodeMethods.push('qrcode.js');
                window.QRCodeLoaded = true;
                console.log('QRCode Method 1 loaded successfully');
                resolve();
            } else {
                reject('QRCode not available');
            }
        };
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

// Method 2: Try qrcode.js from unpkg
function loadQRCodeMethod2() {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/qrcode@1.5.3/build/qrcode.min.js';
        script.onload = function() {
            if (typeof QRCode !== 'undefined') {
                window.QRCodeMethods.push('unpkg-qrcode');
                window.QRCodeLoaded = true;
                console.log('QRCode Method 2 loaded successfully');
                resolve();
            } else {
                reject('QRCode not available');
            }
        };
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

// Method 3: Use QR Server API as fallback
function generateQRWithAPI(text, size = 300) {
    return new Promise((resolve, reject) => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = size;
        canvas.height = size;

        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            ctx.drawImage(img, 0, 0, size, size);
            resolve(canvas);
        };
        img.onerror = reject;

        // Use QR Server API
        const encodedText = encodeURIComponent(text);
        img.src = `https://api.qrserver.com/v1/create-qr-code/?size=${size}x${size}&data=${encodedText}`;
    });
}

// Method 4: Simple QR generation using Google Charts API
function generateQRWithGoogle(text, size = 300) {
    return new Promise((resolve, reject) => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = size;
        canvas.height = size;

        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            ctx.drawImage(img, 0, 0, size, size);
            resolve(canvas);
        };
        img.onerror = reject;

        // Use Google Charts API
        const encodedText = encodeURIComponent(text);
        img.src = `https://chart.googleapis.com/chart?chs=${size}x${size}&cht=qr&chl=${encodedText}`;
    });
}

// Load QR Code libraries with fallbacks
async function initializeQRCode() {
    try {
        await loadQRCodeMethod1();
        return;
    } catch (e) {
        console.log('Method 1 failed, trying method 2...');
    }

    try {
        await loadQRCodeMethod2();
        return;
    } catch (e) {
        console.log('Method 2 failed, will use API fallbacks...');
    }

    // If all libraries fail, we'll use API methods
    window.QRCodeMethods.push('api-fallback');
    window.QRCodeLoaded = true;
    console.log('Using API fallback methods for QR generation');
}

// Start loading immediately
initializeQRCode();
</script>

<script>
let currentQRData = null;

// Wait for QRCode library to load
function waitForQRCode() {
    return new Promise((resolve, reject) => {
        if (window.QRCodeLoaded) {
            resolve();
            return;
        }

        let attempts = 0;
        const maxAttempts = 100; // 10 seconds max

        const checkInterval = setInterval(() => {
            attempts++;
            if (window.QRCodeLoaded) {
                clearInterval(checkInterval);
                resolve();
            } else if (attempts >= maxAttempts) {
                clearInterval(checkInterval);
                reject(new Error('QRCode library failed to load after 10 seconds'));
            }
        }, 100);
    });
}

// Category select change handler
document.getElementById('categorySelect').addEventListener('change', function() {
    const btn = document.getElementById('categoryQRBtn');
    if (this.value) {
        btn.disabled = false;
        btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
        btn.style.cursor = 'pointer';
    } else {
        btn.disabled = true;
        btn.style.background = '#6b7280';
        btn.style.cursor = 'not-allowed';
    }
});

// Invoice type change handler
document.getElementById('invoiceType').addEventListener('change', function() {
    const categoryDiv = document.getElementById('invoiceCategoryDiv');
    if (this.value === 'category') {
        categoryDiv.style.display = 'block';
    } else {
        categoryDiv.style.display = 'none';
    }
});

// Generate all products QR
async function generateAllProductsQR() {
    try {
        showLoading();
        const response = await fetch('{{ route("tenant.inventory.qr.generate.all") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                limit: 50 // Limit to 50 products to avoid large QR codes
            })
        });

        if (!response.ok) {
            const errorText = await response.text();
            let errorData;
            try {
                errorData = JSON.parse(errorText);
            } catch (e) {
                errorData = { error: errorText };
            }

            console.error('HTTP Error Response:', {
                status: response.status,
                statusText: response.statusText,
                data: errorData
            });

            throw new Error(`HTTP error! status: ${response.status} - ${errorData.error || response.statusText}`);
        }

        const data = await response.json();

        if (data.success) {
            displayQR(data.qr_data, {
                title: 'جميع المنتجات المتوفرة',
                count: data.products_count,
                size: data.data_size
            });
        } else {
            let errorMessage = data.error || 'فشل في إنشاء QR كود';

            // Add more details if available
            if (data.products_count !== undefined) {
                errorMessage += `\nعدد المنتجات: ${data.products_count}`;
            }
            if (data.data_size) {
                errorMessage += `\nحجم البيانات: ${data.data_size}`;
            }
            if (data.tenant_id) {
                errorMessage += `\nرقم المستأجر: ${data.tenant_id}`;
            }

            showError(errorMessage);
            console.error('QR Generation Error:', data);
        }
    } catch (error) {
        console.error('Network Error:', error);
        showError('حدث خطأ في الاتصال: ' + error.message);

        // Add debugging info
        console.log('Request URL:', '{{ route("tenant.inventory.qr.generate.all") }}');
        console.log('CSRF Token:', '{{ csrf_token() }}');
    }
}

// Generate compact QR with minimal data
async function generateCompactQR() {
    try {
        showLoading();
        const response = await fetch('{{ route("tenant.inventory.qr.generate.all") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                limit: 3 // Very limited for compact QR
            })
        });

        if (!response.ok) {
            const errorText = await response.text();
            let errorData;
            try {
                errorData = JSON.parse(errorText);
            } catch (e) {
                errorData = { error: errorText };
            }

            console.error('HTTP Error Response:', {
                status: response.status,
                statusText: response.statusText,
                data: errorData
            });

            throw new Error(`HTTP error! status: ${response.status} - ${errorData.error || response.statusText}`);
        }

        const data = await response.json();

        if (data.success) {
            displayQR(data.qr_data, {
                title: 'QR مبسط (3 منتجات)',
                count: data.products_count,
                size: data.data_size
            });
        } else {
            let errorMessage = data.error || 'فشل في إنشاء QR كود مبسط';

            // Add more details if available
            if (data.products_count !== undefined) {
                errorMessage += `\nعدد المنتجات: ${data.products_count}`;
            }
            if (data.data_size) {
                errorMessage += `\nحجم البيانات: ${data.data_size}`;
            }
            if (data.suggestion) {
                errorMessage += `\nاقتراح: ${data.suggestion}`;
            }

            showError(errorMessage);
            console.error('Compact QR Generation Error:', data);
        }
    } catch (error) {
        console.error('Network Error:', error);
        showError('حدث خطأ في الاتصال: ' + error.message);

        // Add debugging info
        console.log('Request URL:', '{{ route("tenant.inventory.qr.generate.all") }}');
        console.log('CSRF Token:', '{{ csrf_token() }}');
    }
}

// Generate category QR
async function generateCategoryQR() {
    const categoryId = document.getElementById('categorySelect').value;
    if (!categoryId) return;
    
    try {
        showLoading();
        const response = await fetch(`{{ route("tenant.inventory.qr.generate.category", ":id") }}`.replace(':id', categoryId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            displayQR(data.qr_data, {
                title: 'منتجات الفئة: ' + data.category_name,
                count: data.products_count,
                size: data.data_size
            });
        } else {
            showError('فشل في إنشاء QR كود');
        }
    } catch (error) {
        showError('حدث خطأ في الاتصال');
    }
}

// Generate invoice QR
async function generateInvoiceQR() {
    const type = document.getElementById('invoiceType').value;
    const limit = document.getElementById('invoiceLimit').value;
    const categoryId = document.getElementById('invoiceCategorySelect').value;
    
    const requestData = {
        type: type,
        limit: parseInt(limit)
    };
    
    if (type === 'category' && categoryId) {
        requestData.category_id = categoryId;
    }
    
    try {
        showLoading();
        const response = await fetch('{{ route("tenant.inventory.qr.generate.invoice") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(requestData)
        });
        
        const data = await response.json();
        if (data.success) {
            displayQR(data.qr_data, {
                title: 'QR كود للفاتورة',
                count: data.products_count,
                size: data.data_size,
                type: 'invoice'
            });
        } else {
            showError('فشل في إنشاء QR كود');
        }
    } catch (error) {
        showError('حدث خطأ في الاتصال');
    }
}

// Display QR code with multiple fallback methods
async function displayQR(qrData, info) {
    try {
        // Wait for QRCode system to be available
        await waitForQRCode();

        currentQRData = qrData;

        const container = document.getElementById('qrCodeContainer');
        const infoDiv = document.getElementById('qrInfo');
        const placeholder = document.getElementById('qrPlaceholder');
        const result = document.getElementById('qrResult');

        // Clear previous QR
        container.innerHTML = '';

        let canvas = null;

        // Try different QR generation methods
        if (typeof QRCode !== 'undefined' && QRCode.toCanvas) {
            // Method 1: Use qrcode.js library
            try {
                canvas = await new Promise((resolve, reject) => {
                    QRCode.toCanvas(qrData, {
                        width: 300,
                        margin: 2,
                        color: {
                            dark: '#000000',
                            light: '#FFFFFF'
                        }
                    }, function (error, canvasElement) {
                        if (error) {
                            reject(error);
                        } else {
                            resolve(canvasElement);
                        }
                    });
                });
                console.log('QR generated using qrcode.js library');
            } catch (error) {
                console.log('qrcode.js failed, trying API method...');
                canvas = null;
            }
        }

        // Method 2: Use QR Server API
        if (!canvas) {
            try {
                canvas = await generateQRWithAPI(qrData, 300);
                console.log('QR generated using QR Server API');
            } catch (error) {
                console.log('QR Server API failed, trying Google Charts...');
            }
        }

        // Method 3: Use Google Charts API
        if (!canvas) {
            try {
                canvas = await generateQRWithGoogle(qrData, 300);
                console.log('QR generated using Google Charts API');
            } catch (error) {
                console.log('Google Charts API failed');
            }
        }

        if (canvas) {
            container.appendChild(canvas);

            // Update info
            const method = window.QRCodeMethods.join(', ') || 'unknown';
            infoDiv.innerHTML = `
                <div style="margin-bottom: 10px;"><strong>العنوان:</strong> ${info.title}</div>
                <div style="margin-bottom: 10px;"><strong>عدد المنتجات:</strong> ${info.count}</div>
                <div style="margin-bottom: 10px;"><strong>حجم البيانات:</strong> ${info.size}</div>
                <div style="margin-bottom: 10px;"><strong>طريقة الإنشاء:</strong> ${method}</div>
                <div><strong>التاريخ:</strong> ${new Date().toLocaleString('ar-EG')}</div>
            `;

            // Show result, hide placeholder
            placeholder.style.display = 'none';
            result.style.display = 'block';
        } else {
            throw new Error('جميع طرق إنشاء QR كود فشلت');
        }

    } catch (error) {
        console.error('QRCode generation error:', error);
        showError('فشل في إنشاء QR كود: ' + error.message + '\n\nيرجى التحقق من اتصال الإنترنت أو المحاولة لاحقاً.');
    }
}

// Show loading
function showLoading() {
    const placeholder = document.getElementById('qrPlaceholder');
    placeholder.innerHTML = `
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
        <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 18px; font-weight: 600;">جاري إنشاء QR كود...</h4>
        <p style="margin: 0; font-size: 16px;">يرجى الانتظار</p>
    `;
}

// Show error
function showError(message) {
    const placeholder = document.getElementById('qrPlaceholder');
    const result = document.getElementById('qrResult');

    // Hide result if showing
    if (result) {
        result.style.display = 'none';
    }

    // Show error in placeholder
    if (placeholder) {
        placeholder.style.display = 'block';
        placeholder.innerHTML = `
            <div style="background: #ef4444; color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h4 style="margin: 0 0 10px 0; color: #ef4444; font-size: 18px; font-weight: 600;">خطأ</h4>
            <p style="margin: 0; font-size: 16px; white-space: pre-line;">${message}</p>
            <div style="margin-top: 20px;">
                <button onclick="location.reload()" style="background: #6b7280; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-redo" style="margin-left: 5px;"></i>
                    إعادة تحميل الصفحة
                </button>
            </div>
        `;
    }

    console.error('QR Error:', message);
}

// Test connection function
async function testConnection() {
    try {
        showLoading();

        // Test basic connectivity
        const response = await fetch('{{ route("tenant.inventory.qr.generate.all") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                limit: 1 // Test with just 1 product
            })
        });

        let data;
        try {
            data = await response.json();
        } catch (e) {
            const text = await response.text();
            showError(`❌ استجابة غير صالحة
الحالة: ${response.status}
النص: ${text.substring(0, 200)}...`);
            return;
        }

        if (response.ok && data.success) {
            showSuccess(`✅ الاتصال يعمل بشكل صحيح
الحالة: ${response.status}
المنتجات: ${data.products_count || 0}
حجم البيانات: ${data.data_size || 'غير محدد'}
رقم المستأجر: ${data.tenant_id || 'غير محدد'}`);
        } else if (data.error) {
            showError(`⚠️ خطأ من الخادم
الحالة: ${response.status}
الخطأ: ${data.error}
التفاصيل: ${JSON.stringify(data.debug_info || data.validation_errors || {})}`);
        } else {
            showError(`⚠️ استجابة غير متوقعة
الحالة: ${response.status}
البيانات: ${JSON.stringify(data)}`);
        }

    } catch (error) {
        showError(`❌ فشل الاتصال
الخطأ: ${error.message}
الرابط: {{ route("tenant.inventory.qr.generate.all") }}
CSRF: {{ csrf_token() }}`);
    }
}

// Show success message
function showSuccess(message) {
    const placeholder = document.getElementById('qrPlaceholder');
    placeholder.innerHTML = `
        <div style="background: #10b981; color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h4 style="margin: 0 0 10px 0; color: #10b981; font-size: 18px; font-weight: 600;">نجح الاختبار</h4>
        <p style="margin: 0; font-size: 16px; white-space: pre-line;">${message}</p>
    `;
}

// Download QR
function downloadQR() {
    if (!currentQRData) return;
    
    const canvas = document.querySelector('#qrCodeContainer canvas');
    if (canvas) {
        const link = document.createElement('a');
        link.download = 'products-qr-code.png';
        link.href = canvas.toDataURL();
        link.click();
    }
}

// Print QR
function printQR() {
    if (!currentQRData) return;
    
    const canvas = document.querySelector('#qrCodeContainer canvas');
    const info = document.getElementById('qrInfo').innerHTML;
    
    if (canvas) {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>QR كود المنتجات</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
                        .info { text-align: right; margin: 20px 0; padding: 15px; background: #f8fafc; border-radius: 8px; }
                    </style>
                </head>
                <body>
                    <h2>QR كود المنتجات</h2>
                    <img src="${canvas.toDataURL()}" style="max-width: 300px;">
                    <div class="info">${info}</div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}

// Check QRCode system on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (!window.QRCodeLoaded) {
            console.error('QRCode system not loaded');
            showError('جاري تحميل نظام QR كود...\n\nإذا استمرت المشكلة، يرجى التحقق من اتصال الإنترنت.');

            // Try to initialize again
            initializeQRCode().then(() => {
                if (window.QRCodeLoaded) {
                    location.reload();
                }
            });
        } else {
            console.log('QRCode system loaded successfully');
            console.log('Available methods:', window.QRCodeMethods);
        }
    }, 3000); // Wait 3 seconds for system to load
});
</script>
@endsection
