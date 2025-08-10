<!-- Invoice Preview Modal -->
<div id="invoicePreviewModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; padding: 25px;">
                <h5 class="modal-title" style="font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-eye"></i>
                    معاينة الفاتورة
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1; font-size: 24px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 30px; max-height: 70vh; overflow-y: auto;">
                <!-- Invoice Preview Content -->
                <div id="invoicePreviewContent">
                    <!-- Company Header -->
                    <div class="invoice-header-preview" style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
                        <h2 style="color: #1e293b; font-size: 24px; font-weight: 800; margin-bottom: 5px;">{{ $tenant->name ?? 'اسم الشركة' }}</h2>
                        <p style="color: #6b7280; margin: 0;">نظام إدارة الصيدليات المتطور</p>
                    </div>

                    <!-- Invoice Info -->
                    <div class="invoice-info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                        <div>
                            <h4 style="color: #374151; font-weight: 700; margin-bottom: 15px;">معلومات الفاتورة</h4>
                            <div class="info-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                                <span style="color: #6b7280;">رقم الفاتورة:</span>
                                <span style="font-weight: 600;" id="previewInvoiceNumber">سيتم إنشاؤه تلقائياً</span>
                            </div>
                            <div class="info-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                                <span style="color: #6b7280;">تاريخ الفاتورة:</span>
                                <span style="font-weight: 600;" id="previewInvoiceDate">-</span>
                            </div>
                            <div class="info-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                                <span style="color: #6b7280;">تاريخ الاستحقاق:</span>
                                <span style="font-weight: 600;" id="previewDueDate">-</span>
                            </div>
                            <div class="info-item" style="display: flex; justify-content: space-between; padding: 8px 0;">
                                <span style="color: #6b7280;">المندوب:</span>
                                <span style="font-weight: 600;" id="previewSalesRep">-</span>
                            </div>
                        </div>
                        <div>
                            <h4 style="color: #374151; font-weight: 700; margin-bottom: 15px;">معلومات العميل</h4>
                            <div class="info-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                                <span style="color: #6b7280;">اسم العميل:</span>
                                <span style="font-weight: 600;" id="previewCustomerName">-</span>
                            </div>
                            <div class="info-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                                <span style="color: #6b7280;">رقم الهاتف:</span>
                                <span style="font-weight: 600;" id="previewCustomerPhone">-</span>
                            </div>
                            <div class="info-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                                <span style="color: #6b7280;">المديونية السابقة:</span>
                                <span style="font-weight: 600;" id="previewPreviousBalance">0.00 د.ع</span>
                            </div>
                            <div class="info-item" style="display: flex; justify-content: space-between; padding: 8px 0;">
                                <span style="color: #6b7280;">سقف المديونية:</span>
                                <span style="font-weight: 600;" id="previewCreditLimit">0.00 د.ع</span>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="invoice-items-preview" style="margin-bottom: 30px;">
                        <h4 style="color: #374151; font-weight: 700; margin-bottom: 15px;">عناصر الفاتورة</h4>
                        <table class="preview-table" style="width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden;">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th style="padding: 12px; text-align: right; border-bottom: 1px solid #e5e7eb; font-weight: 600;">المنتج</th>
                                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">الكمية</th>
                                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">السعر</th>
                                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">الخصم</th>
                                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">العينات</th>
                                    <th style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">المجموع</th>
                                </tr>
                            </thead>
                            <tbody id="previewItemsBody">
                                <!-- Items will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Invoice Totals -->
                    <div class="invoice-totals-preview" style="background: #f8fafc; border-radius: 15px; padding: 20px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <h4 style="color: #374151; font-weight: 700; margin-bottom: 15px;">ملخص الفاتورة</h4>
                                <div class="total-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                                    <span style="color: #6b7280;">المجموع الفرعي:</span>
                                    <span style="font-weight: 600;" id="previewSubtotal">0.00 د.ع</span>
                                </div>
                                <div class="total-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                                    <span style="color: #6b7280;">إجمالي الخصومات:</span>
                                    <span style="font-weight: 600;" id="previewTotalDiscount">0.00 د.ع</span>
                                </div>
                                <div class="total-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                                    <span style="color: #6b7280;">العينات المجانية:</span>
                                    <span style="font-weight: 600;" id="previewFreeSamples">0</span>
                                </div>
                                <div class="total-item" style="display: flex; justify-content: space-between; padding: 15px 0 0 0; border-top: 2px solid #667eea; margin-top: 10px; font-size: 18px;">
                                    <span style="color: #1e293b; font-weight: 700;">الإجمالي النهائي:</span>
                                    <span style="color: #667eea; font-weight: 800;" id="previewGrandTotal">0.00 د.ع</span>
                                </div>
                            </div>
                            <div>
                                <h4 style="color: #374151; font-weight: 700; margin-bottom: 15px;">حالة المديونية</h4>
                                <div class="total-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                                    <span style="color: #6b7280;">المديونية السابقة:</span>
                                    <span style="font-weight: 600;" id="previewPrevBalance">0.00 د.ع</span>
                                </div>
                                <div class="total-item" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                                    <span style="color: #6b7280;">قيمة الفاتورة:</span>
                                    <span style="font-weight: 600;" id="previewInvoiceAmount">0.00 د.ع</span>
                                </div>
                                <div class="total-item" style="display: flex; justify-content: space-between; padding: 15px 0 0 0; border-top: 2px solid #667eea; margin-top: 10px; font-size: 16px;">
                                    <span style="color: #1e293b; font-weight: 700;">إجمالي المديونية:</span>
                                    <span style="font-weight: 800;" id="previewTotalDebt">0.00 د.ع</span>
                                </div>
                                <div id="previewCreditWarning" style="display: none; background: #fecaca; color: #991b1b; padding: 10px; border-radius: 8px; margin-top: 10px; font-size: 14px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    تحذير: المديونية تتجاوز السقف المحدد
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div id="previewNotesSection" style="margin-top: 20px; display: none;">
                        <h4 style="color: #374151; font-weight: 700; margin-bottom: 10px;">ملاحظات</h4>
                        <div id="previewNotes" style="background: #f8fafc; padding: 15px; border-radius: 10px; border-right: 4px solid #667eea;"></div>
                    </div>

                    <!-- QR Code Placeholder -->
                    <div class="qr-section" style="text-align: center; margin-top: 30px; padding: 20px; background: #f8fafc; border-radius: 15px;">
                        <h4 style="color: #374151; font-weight: 700; margin-bottom: 15px;">رمز QR للفاتورة</h4>
                        <div style="width: 150px; height: 150px; background: white; border: 2px dashed #d1d5db; border-radius: 10px; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: #6b7280;">
                            <div style="text-align: center;">
                                <i class="fas fa-qrcode" style="font-size: 40px; margin-bottom: 10px;"></i>
                                <div style="font-size: 12px;">سيتم إنشاؤه عند الحفظ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-outline" data-dismiss="modal" style="background: transparent; border: 2px solid #6b7280; color: #6b7280; padding: 12px 24px; border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-times"></i>
                    إغلاق
                </button>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-primary" onclick="printPreview()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600;">
                        <i class="fas fa-print"></i>
                        طباعة
                    </button>
                    <button type="button" class="btn btn-success" onclick="confirmAndSave()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600;">
                        <i class="fas fa-check"></i>
                        تأكيد وحفظ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modal {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.modal.show {
    display: flex !important;
    align-items: center;
    justify-content: center;
}

.modal-dialog {
    margin: 20px;
    max-width: 90%;
    width: 1200px;
}

.modal-content {
    max-height: 90vh;
    overflow: hidden;
}

@media (max-width: 768px) {
    .modal-dialog {
        max-width: 95%;
        margin: 10px;
    }

    .invoice-info-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }

    .invoice-totals-preview > div {
        grid-template-columns: 1fr !important;
    }

    .preview-table {
        font-size: 12px;
    }

    .preview-table th,
    .preview-table td {
        padding: 8px 4px !important;
    }
}

@media print {
    .modal-header,
    .modal-footer {
        display: none !important;
    }

    .modal-content {
        box-shadow: none !important;
        border: none !important;
    }

    .modal-body {
        padding: 0 !important;
    }
}
</style>

<script>
// Preview Modal Functions
function showInvoicePreview() {
    updatePreviewContent();
    const modal = document.getElementById('invoicePreviewModal');
    // Ensure CSS class exists when not using Bootstrap modal
    if (!document.getElementById('invoicePreviewModalStyle')) {
        const style = document.createElement('style');
        style.id = 'invoicePreviewModalStyle';
        style.textContent = `.modal.show{display:flex!important;align-items:center;justify-content:center}`;
        document.head.appendChild(style);
    }

    if (!modal) return;
    if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
        window.jQuery('#invoicePreviewModal').modal('show');
    } else {
        modal.style.display = 'flex';
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function updatePreviewContent() {
    // Get form data
    const form = document.getElementById('invoiceForm');
    const formData = new FormData(form);

    // Update basic info
    document.getElementById('previewInvoiceDate').textContent = formData.get('invoice_date') || '-';
    document.getElementById('previewDueDate').textContent = formData.get('due_date') || '-';
    document.getElementById('previewSalesRep').textContent = formData.get('sales_representative') || '-';

    // Update customer info
    const customerSelect = document.getElementById('customerSelect');
    const selectedCustomer = customerSelect.options[customerSelect.selectedIndex];
    if (selectedCustomer.value) {
        document.getElementById('previewCustomerName').textContent = selectedCustomer.textContent.split('(')[0].trim();
        document.getElementById('previewCustomerPhone').textContent = selectedCustomer.dataset.phone || '-';
        document.getElementById('previewPreviousBalance').textContent = (parseFloat(selectedCustomer.dataset.previousBalance || 0)).toFixed(2) + ' د.ع';
        document.getElementById('previewCreditLimit').textContent = (parseFloat(selectedCustomer.dataset.creditLimit || 0)).toFixed(2) + ' د.ع';
    }

    // Update items
    updatePreviewItems();

    // Update totals
    updatePreviewTotals();

    // Update notes
    const notes = formData.get('notes');
    if (notes && notes.trim()) {
        document.getElementById('previewNotes').textContent = notes;
        document.getElementById('previewNotesSection').style.display = 'block';
    } else {
        document.getElementById('previewNotesSection').style.display = 'none';
    }
}

function updatePreviewItems() {
    const tbody = document.getElementById('previewItemsBody');
    tbody.innerHTML = '';

    const productSelects = document.querySelectorAll('select[name*="[product_id]"]');

    productSelects.forEach((select, index) => {
        if (select.value) {
            const selectedOption = select.options[select.selectedIndex];
            const quantity = document.querySelector(`input[name="items[${index}][quantity]"]`).value || 0;
            const unitPrice = document.querySelector(`input[name="items[${index}][unit_price]"]`).value || 0;
            const discount = document.querySelector(`input[name="items[${index}][discount_amount]"]`).value || 0;
            const freeSamples = document.querySelector(`input[name="items[${index}][free_samples]"]`).value || 0;
            const total = document.querySelector(`input[name="items[${index}][total_amount]"]`).value || 0;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td style="padding: 12px; border-bottom: 1px solid #f1f5f9;">${selectedOption.textContent}</td>
                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #f1f5f9;">${quantity}</td>
                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #f1f5f9;">${parseFloat(unitPrice).toFixed(2)} د.ع</td>
                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #f1f5f9;">${parseFloat(discount).toFixed(2)} د.ع</td>
                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #f1f5f9;">${freeSamples}</td>
                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #f1f5f9; font-weight: 600;">${parseFloat(total).toFixed(2)} د.ع</td>
            `;
            tbody.appendChild(row);
        }
    });
}

function updatePreviewTotals() {
    const subtotal = parseFloat(document.getElementById('subtotalAmount').value || 0);
    const discount = parseFloat(document.getElementById('discountAmount').value || 0);
    const freeSamples = parseInt(document.getElementById('freeSamples').value || 0);
    const total = parseFloat(document.getElementById('totalAmount').value || 0);
    const previousBalance = parseFloat(document.getElementById('previousBalance').value || 0);
    const creditLimit = parseFloat(document.getElementById('creditLimit').value || 0);
    const totalDebt = previousBalance + total;

    document.getElementById('previewSubtotal').textContent = subtotal.toFixed(2) + ' د.ع';
    document.getElementById('previewTotalDiscount').textContent = discount.toFixed(2) + ' د.ع';
    document.getElementById('previewFreeSamples').textContent = freeSamples;
    document.getElementById('previewGrandTotal').textContent = total.toFixed(2) + ' د.ع';
    document.getElementById('previewPrevBalance').textContent = previousBalance.toFixed(2) + ' د.ع';
    document.getElementById('previewInvoiceAmount').textContent = total.toFixed(2) + ' د.ع';
    document.getElementById('previewTotalDebt').textContent = totalDebt.toFixed(2) + ' د.ع';

    // Show credit warning if needed
    const warningElement = document.getElementById('previewCreditWarning');
    if (creditLimit > 0 && totalDebt > creditLimit) {
        warningElement.style.display = 'block';
        document.getElementById('previewTotalDebt').style.color = '#ef4444';
    } else {
        warningElement.style.display = 'none';
        document.getElementById('previewTotalDebt').style.color = '#1e293b';
    }
}

function printPreview() {
    window.print();
}

function confirmAndSave() {
    const modal = document.getElementById('invoicePreviewModal');
    if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
        window.jQuery('#invoicePreviewModal').modal('hide');
    } else if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Submit the form with finalize action
    const form = document.getElementById('invoiceForm');
    const finalizeButton = document.querySelector('button[value="finalize"]');
    if (finalizeButton) {
        finalizeButton.click();
    } else if (form) {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'action';
        hidden.value = 'finalize';
        form.appendChild(hidden);
        form.submit();
    }
}
</script>
