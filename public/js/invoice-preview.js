// Lightweight invoice preview logic for create-professional page
(function(){
  function buildPreviewHtml(form){
    const customerName = form.querySelector('#customerSelect option:checked')?.textContent?.trim() || 'بدون عميل';
    const invoiceDate = form.querySelector('input[name="invoice_date"]').value || '';
    const dueDate = form.querySelector('input[name="due_date"]').value || '';

    const rows = Array.from(form.querySelectorAll('tr.item-row')).map((row, idx) => {
      const product = row.querySelector('select[name^="items"][name$="[product_id]"] option:checked')?.textContent?.trim() || 'منتج';
      const qty = row.querySelector('input[name^="items"][name$="[quantity]"]')?.value || '0';
      const price = row.querySelector('input[name^="items"][name$="[unit_price]"]')?.value || '0';
      const total = row.querySelector('input[name^="items"][name$="[total_amount]"]')?.value || (parseFloat(qty||0)*parseFloat(price||0)).toFixed(2);
      return `<tr><td>${idx+1}</td><td>${product}</td><td>${qty}</td><td>${price}</td><td>${total}</td></tr>`;
    }).join('');

    return `
      <div style="direction: rtl; font-family: 'Cairo', sans-serif; padding: 16px;">
        <h3 style="margin:0 0 8px">معاينة الفاتورة</h3>
        <div style="margin-bottom:8px;color:#555">العميل: ${customerName}</div>
        <div style="margin-bottom:8px;color:#555">تاريخ الفاتورة: ${invoiceDate} | تاريخ الاستحقاق: ${dueDate}</div>
        <table style="width:100%; border-collapse: collapse; font-size: 13px;">
          <thead>
            <tr>
              <th style="border:1px solid #e5e7eb; padding:6px;">#</th>
              <th style="border:1px solid #e5e7eb; padding:6px;">المنتج</th>
              <th style="border:1px solid #e5e7eb; padding:6px;">الكمية</th>
              <th style="border:1px solid #e5e7eb; padding:6px;">السعر</th>
              <th style="border:1px solid #e5e7eb; padding:6px;">الإجمالي</th>
            </tr>
          </thead>
          <tbody>${rows || '<tr><td colspan="5" style="text-align:center;padding:10px;color:#666">لا توجد بنود</td></tr>'}</tbody>
        </table>
      </div>`;
  }

  window.showInvoicePreview = function(){
    const form = document.getElementById('invoiceForm');
    if(!form){ alert('النموذج غير موجود'); return; }
    const html = buildPreviewHtml(form);

    let modal = document.getElementById('invoicePreviewModal');
    if(!modal){
      modal = document.createElement('div');
      modal.id = 'invoicePreviewModal';
      modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;z-index:9999;';
      modal.innerHTML = `<div style="background:#fff;border-radius:12px;max-width:900px;width:95%;max-height:90vh;overflow:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid #e5e7eb;">
          <div style="font-weight:700">معاينة الفاتورة</div>
          <button id="closePreviewBtn" style="background:#ef4444;color:#fff;border:none;border-radius:8px;padding:6px 10px;cursor:pointer;">إغلاق</button>
        </div>
        <div id="invoicePreviewBody">${html}</div>
      </div>`;
      document.body.appendChild(modal);
      modal.addEventListener('click', (e)=>{
        if(e.target.id === 'invoicePreviewModal' || e.target.id === 'closePreviewBtn'){
          modal.remove();
        }
      });
    } else {
      modal.querySelector('#invoicePreviewBody').innerHTML = html;
    }
  }
})();

