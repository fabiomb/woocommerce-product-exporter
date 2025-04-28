document.addEventListener('DOMContentLoaded', function() {
    const exportButton = document.getElementById('export-products');

    exportButton.addEventListener('click', function() {
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': wpApiSettings.nonce
            },
            body: JSON.stringify({
                action: 'export_woocommerce_products'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const blob = new Blob([data.csv], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'woocommerce_products_export.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            } else {
                alert('Error exporting products: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while exporting products.');
        });
    });
});