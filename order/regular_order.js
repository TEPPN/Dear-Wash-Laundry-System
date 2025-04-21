const pricing = {
    'Complete Wash': 6000,
    'Dry Cleaning': {
        'Suit': 20000,
        'Suit\'s Pants': 10000,
        'Suits': 30000,
        'Dress': 30000
    },
    'Ironing': 4000,
    'Bed sheet' : 7000,
    'Bed Cover' : {
        'S' : 20000,
        'M' : 25000,
        'XL' : 30000
    },
    'Wash Separate': {
        'Underwear': 2000,
        'Sock': 2000,
        'Towel': 10000,
        'Jacket': 5000
    }
};

document.addEventListener('DOMContentLoaded', function() {
    addOrderType();
});

let orderTypeCounter = 0;

function addOrderType() {
    let container = document.getElementById("order-container");
    let div = document.createElement("div");
    div.className = "order-type";
    const orderIndex = orderTypeCounter++;

    div.innerHTML = `
                <h4>Order Type</h4>
                <select name="order_types[]" required onchange="updatePrices(${orderIndex})">
                    <option value="Complete Wash">Complete Wash</option>
                    <option value="Dry Cleaning">Dry Cleaning</option>
                    <option value="Ironing">Ironing</option>
                    <option value="Bed Sheet">Bed Sheet</option>
                    <option value="Bed Cover">Bed Cover</option>
                    <option value="Wash Separate">Wash Separate</option>
                </select>
                <button type="button" onclick="addItem(this, ${orderIndex})">Add Item</button>
                <div class="items-container" id="items-container-${orderIndex}"></div>
                <div class="subtotal" id="subtotal-${orderIndex}">Subtotal: Rp 0</div>
                <button type="button" onclick="removeOrderType(this)" class="remove-btn">Remove Order Type</button>
            `;
            container.appendChild(div);
}

// Modified this part to handle single-value pricing types
function addItem(button, orderIndex) {
    let container = document.getElementById(`items-container-${orderIndex}`);
    let itemIndex = container.children.length;
    let div = document.createElement("div");
    div.className = "item-row";
    
    let orderTypeSelect = button.previousElementSibling;
    let orderType = orderTypeSelect.value;
    let basePrice = pricing[orderType];
    
    if (typeof basePrice === 'object') {
        // Complex pricing (like Dry Cleaning)
        let options = Object.keys(basePrice)
            .map(item => `<option value="${item}">${item}</option>`)
            .join('');
            
        div.innerHTML = `
            <select name="items[${orderIndex}][]" required onchange="updatePrices(${orderIndex})">
                ${options}
            </select>
            <input type="number" name="quantities[${orderIndex}][]" value="1" min="1" onchange="updatePrices(${orderIndex})">
            <span class="item-price">Rp 0</span>
            <button type="button" onclick="removeItem(this, ${orderIndex})">Remove</button>
        `;
    } else {
        // Simple pricing (like Complete Wash)
        div.innerHTML = `
            <span class="service-name">${orderType}</span>
            <input type="hidden" name="items[${orderIndex}][]" value="${orderType}">
            <input type="number" name="quantities[${orderIndex}][]" value="1" min="1" onchange="updatePrices(${orderIndex})">
            <span class="item-price">Rp ${basePrice.toLocaleString('id')}</span>
            <button type="button" onclick="removeItem(this, ${orderIndex})">Remove</button>
        `;
    }
    
    container.appendChild(div);
    updatePrices(orderIndex);
}

function removeItem(button, orderIndex) {
    button.parentElement.remove();
    updatePrices(orderIndex);
}

function removeOrderType(button) {
    button.parentElement.remove();
    updateTotalPrice();
}

function updateItemPrice(element, orderIndex, itemIndex) {
    let orderType = document.querySelector(`.order-type:nth-of-type(${orderIndex + 1}) select[name="order_types[]"]`).value;
    let quantity = 0;
    let item = '';
    
    if (typeof pricing[orderType] === 'object') {
        // For complex pricing types
        let itemSelect = element.tagName === 'SELECT' ? element : element.previousElementSibling;
        let quantityInput = element.tagName === 'SELECT' ? element.nextElementSibling : element;
        item = itemSelect.value;
        quantity = parseInt(quantityInput.value) || 1;
    } else {
        // For single-value pricing types
        quantity = parseInt(element.value) || 1;
        item = orderType;
    }

    let pricePerItem = typeof pricing[orderType] === 'object' ? pricing[orderType][item] : pricing[orderType];
    let totalItemPrice = pricePerItem * quantity;

    let priceSpan = document.getElementById(`price-${orderIndex}-${itemIndex}`);
    if (priceSpan) {
        priceSpan.textContent = `Rp ${totalItemPrice.toLocaleString('id')}`;
    }

    updatePrices(orderIndex);
}

function updatePrices(orderIndex) {
    let orderType = document.querySelector(`.order-type:nth-of-type(${orderIndex + 1}) select[name="order_types[]"]`).value;
    let itemsContainer = document.getElementById(`items-container-${orderIndex}`);
    let subtotalElement = document.getElementById(`subtotal-${orderIndex}`);
    let subtotal = 0;

    itemsContainer.querySelectorAll('.item-row').forEach(row => {
        let quantity = parseInt(row.querySelector('input[type="number"]').value) || 1;
        let pricePerItem;

        // Simplified price lookup
        if (typeof pricing[orderType] === 'object') {
            let select = row.querySelector('select');
            pricePerItem = pricing[orderType][select.value];
        } else {
            pricePerItem = pricing[orderType];
        }

        // Ensure we have a numeric price
        pricePerItem = Number(pricePerItem) || 0;
        
        let itemTotal = pricePerItem * quantity;
        let priceSpan = row.querySelector('.item-price');
        if (priceSpan) {
            priceSpan.textContent = `Rp ${itemTotal.toLocaleString('id')}`;
        }
        subtotal += itemTotal;
    });

    subtotalElement.textContent = `Subtotal: Rp ${subtotal.toLocaleString('id')}`;
    updateTotalPrice();
}

function updateTotalPrice() {
    let totalPrice = 0;
    let subtotals = document.querySelectorAll('.subtotal');
    
    subtotals.forEach(subtotal => {
        let priceText = subtotal.textContent.replace('Subtotal: Rp ', '').replace(/\./g, '');
        totalPrice += parseInt(priceText) || 0;
    });
    
    let totalElement = document.getElementById('total-price');
    totalElement.textContent = `Total Price: Rp ${totalPrice.toLocaleString('id')}`;
}