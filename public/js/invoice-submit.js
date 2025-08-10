// Simple invoice submission handler
console.log('Invoice submit script loaded');

// Simple submit function
function submitInvoice(btn) {
    console.log('🚀 Submit invoice button clicked!');
    
    // Get form
    const form = document.getElementById('invoiceForm');
    if (!form) {
        console.log('❌ Form not found!');
        alert('خطأ: لم يتم العثور على النموذج');
        return;
    }
    
    // Simple validation
    const customerSelect = document.getElementById('customerSelect');
    if (!customerSelect || !customerSelect.value) {
        alert('يرجى اختيار العميل');
        return;
    }
    
    const productSelects = document.querySelectorAll('select[name*="[product_id]"]');
    if (productSelects.length === 0) {
        alert('يرجى إضافة منتج واحد على الأقل');
        return;
    }
    
    let hasValidProduct = false;
    productSelects.forEach(select => {
        if (select.value) {
            hasValidProduct = true;
        }
    });
    
    if (!hasValidProduct) {
        alert('يرجى اختيار منتج واحد على الأقل');
        return;
    }
    
    console.log('✅ Form validation passed - submitting...');
    
    // Create form data
    const formData = new FormData(form);
    formData.append('action', 'finalize');

    // Log form data for debugging
    console.log('📋 Form data contents:');
    for (let [key, value] of formData.entries()) {
        console.log(`  ${key}: ${value}`);
    }
    
    const actionUrl = form.getAttribute('action');
    console.log('📤 Submitting to:', actionUrl);
    console.log('🔍 Full URL will be:', window.location.origin + actionUrl);
    console.log('🔧 Using production route');
    
    // Show loading
    const button = btn || event?.target || document.querySelector('button[onclick*="submitInvoice"]');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    
    // Submit with fetch
    fetch(actionUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': formData.get('_token')
        }
    })
    .then(response => {
        console.log('🎯 Response:', response.status, response.statusText);
        console.log('🔍 Response URL:', response.url);
        console.log('🔍 Response redirected:', response.redirected);

        // Get response text to see what we actually received
        return response.text().then(text => {
            console.log('📄 Response content (first 500 chars):', text.substring(0, 500));

            if (response.ok) {
                console.log('✅ Success! Redirecting...');
                // Redirect to invoices list
                window.location.href = '/tenant/sales/invoices';
            } else {
                throw new Error(`HTTP ${response.status}: ${response.statusText}\nContent: ${text.substring(0, 200)}`);
            }
        });
    })
    .catch(error => {
        console.log('❌ Error:', error);
        alert('حدث خطأ أثناء حفظ الفاتورة. يرجى المحاولة مرة أخرى.');
        
        // Restore button
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

console.log('Invoice submit script ready');
