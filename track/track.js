function showOrderDetails(orderId, status) {
    const orderList = allOrders[status];
    let order = orderList.find(o => o.order_id == orderId);

    if (!order) return;

    let content = `
        <div class="card-detail">Customer: ${order.customer_name}</div>
        <div class="card-detail">Address: ${order.order_address}</div>
        <div class="card-detail">Date Received: ${formatDate(order.time_received)}</div>
        <div class="card-detail">Date Finished: ${formatDate(order.time_finished)}</div>
        <div class="card-detail"><h4>Order Detail:</h4></div>
    `;

    // Build order details
    let detailHTML = '';
    for (const [type, value] of Object.entries(order.order_details || {})) {
        const formattedType = type.replace(/_/g, ' ').toUpperCase();
        detailHTML += `<div>- ${formattedType}</div><ul>`;

        if (Array.isArray(value)) {
            value.forEach(item => {
                detailHTML += `<li>${item.item || 'Item'} x ${item.quantity || 0} = Rp ${numberFormat(item.item_total || 0)}</li>`;
            });
        } else if (typeof value === 'object') {
            for (const [key, val] of Object.entries(value)) {
                detailHTML += `<li>${key}: ${val}</li>`;
            }
        }

        detailHTML += `</ul>`;
    }

    if (!detailHTML) detailHTML = `<p>No order details available.</p>`;
    content += detailHTML;

    content += `<div class="total-price mt-3">Total: Rp ${numberFormat(order.total_amount)}</div>`;

    // Admin controls
    if (isAdmin) {
        content += `<div class="modal-actions"><form method="post">`;
        content += `<input type="hidden" name="order_id" value="${order.order_id}">`;

        switch (status) {
            case "UNRECEIVED":
                content += `<input type="hidden" name="status" value="RECEIVED">
                            <button type="submit" name="update_status" class="move-button">Move to RECEIVED</button>`;
                break;
            case "RECEIVED":
                content += `<input type="hidden" name="status" value="PROGRESS">
                            <button type="submit" name="update_status" class="move-button">Move to PROGRESS</button>`;
                break;
            case "PROGRESS":
                content += `<input type="hidden" name="status" value="DONE">
                            <button type="submit" name="update_status" class="move-button">Move to DONE</button>`;
                break;
            case "DONE":
                content += `<input type="hidden" name="status" value="PROGRESS">
                            <button type="submit" name="update_status" class="move-button">Back to PROGRESS</button>`;
                break;
        }

        content += `</form></div>`;
    }

    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('orderModal').style.display = 'block';
}

function closeOrderDetails() {
    document.getElementById('modalOverlay').style.display = 'none';
    document.getElementById('orderModal').style.display = 'none';
}

// Helper function to format currency
function numberFormat(value) {
    return value.toLocaleString("id-ID");
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return isNaN(date) ? '-' : date.toLocaleDateString("id-ID", { year: 'numeric', month: 'short', day: 'numeric' });
}
