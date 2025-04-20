function toggleSection(checkbox, sectionId) {
    const section = document.getElementById(sectionId);
    const inputs = section.querySelectorAll('input, select');

    inputs.forEach(input => {
        if (input !== checkbox) {
            input.disabled = !checkbox.checked;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const sections = ['bed_cover', 'dry_clean', 'wash_separate'];

    sections.forEach(section => {
        const parentDiv = document.getElementById(section);
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = section + '_checkbox';

        const label = document.createElement('label');
        label.textContent = section.charAt(0).toUpperCase() + section.slice(1).replace("_", " ");
        label.prepend(checkbox);

        parentDiv.prepend(label);

        const container = document.createElement('div');
        container.className = 'item-container';
        parentDiv.appendChild(container);

        const addButton = document.createElement('button');
        addButton.type = 'button';
        addButton.textContent = 'Add Item';
        addButton.style.display = 'none';
        parentDiv.appendChild(addButton);

        checkbox.addEventListener('change', () => {
            container.innerHTML = '';
            if (checkbox.checked) {
                addItem(section, container);
                addButton.style.display = 'inline-block';
            } else {
                addButton.style.display = 'none';
            }
        });

        addButton.addEventListener('click', () => {
            addItem(section, container);
        });
    });

    function addItem(type, container) {
        const wrapper = document.createElement('div');

        const select = document.createElement('select');
        select.name = type + '[]';

        let options = [];
        if (type === 'bed_cover') {
            options = ['S', 'M', 'XL'];
        } else if (type === 'dry_clean') {
            options = ['Suit', "Suit's Pants", 'Suits', 'Dress'];
        } else if (type === 'wash_separate') {
            options = ['Underwear', 'Sock', 'Towel', 'Jacket'];
        }

        options.forEach(val => {
            const opt = document.createElement('option');
            opt.value = val;
            opt.textContent = val;
            select.appendChild(opt);
        });

        const qtyInput = document.createElement('input');
        qtyInput.type = 'number';
        qtyInput.name = type + '_quantity[]';
        qtyInput.placeholder = 'Quantity';

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.textContent = 'Remove Item';
        removeBtn.style.marginLeft = '8px';
        removeBtn.onclick = () => {
            container.removeChild(wrapper);
            calculateTotal();
        };

        select.addEventListener('input', calculateTotal);
        qtyInput.addEventListener('input', calculateTotal);

        wrapper.appendChild(select);
        wrapper.appendChild(qtyInput);
        wrapper.appendChild(removeBtn);
        container.appendChild(wrapper);

        calculateTotal();
    }

    const now = new Date();
    const createdTime = now.toLocaleString();
    const finishTime = new Date(now);
    finishTime.setDate(finishTime.getDate() + 3);
    const formattedFinishTime = finishTime.toLocaleString();

    const orderTimeDiv = document.getElementById('order_time');
    const createdLabel = document.createElement('label');
    createdLabel.textContent = 'Order Created: ';
    const createdSpan = document.createElement('span');
    createdSpan.textContent = createdTime;
    createdLabel.appendChild(createdSpan);

    orderTimeDiv.appendChild(createdLabel);

    const pricing = {
        'Complete Wash': 6000,
        'Dry Cleaning': {
            'Suit': 20000,
            "Suit's Pants": 10000,
            'Suits': 30000,
            'Dress': 30000
        },
        'Ironing': 4000,
        'Bed sheet': 7000,
        'Bed Cover': {
            'S': 20000,
            'M': 25000,
            'XL': 30000
        },
        'Wash Separate': {
            'Underwear': 2000,
            'Sock': 2000,
            'Towel': 10000,
            'Jacket': 5000
        }
    };

    function calculateTotal() {
        let total = 0;

        const completeWeight = parseFloat(document.getElementById('complete_wash_weight')?.value || 0);
        total += completeWeight * pricing['Complete Wash'];

        const ironingWeight = parseFloat(document.getElementById('ironing_weight')?.value || 0);
        total += ironingWeight * pricing['Ironing'];

        const bedSheetWeight = parseFloat(document.getElementById('bed_sheet_weight')?.value || 0);
        total += bedSheetWeight * pricing['Bed sheet'];

        document.querySelectorAll('#bed_cover select').forEach(select => {
            const size = select.value;
            if (pricing['Bed Cover'][size]) {
                const qty = parseInt(select.nextElementSibling.value) || 0;
                total += qty * pricing['Bed Cover'][size];
            }
        });

        document.querySelectorAll('#dry_clean select').forEach(select => {
            const item = select.value;
            if (pricing['Dry Cleaning'][item]) {
                const qty = parseInt(select.nextElementSibling.value) || 0;
                total += qty * pricing['Dry Cleaning'][item];
            }
        });

        document.querySelectorAll('#wash_separate select').forEach(select => {
            const item = select.value;
            if (pricing['Wash Separate'][item]) {
                const qty = parseInt(select.nextElementSibling.value) || 0;
                total += qty * pricing['Wash Separate'][item];
            }
        });

        applyRushMultiplier(total);
        document.getElementById('total_price_input').value = finalPrice;

    }

    function applyRushMultiplier(baseTotal) {
        const radios = document.getElementsByName('finish_time');
        let days = 3;
        let multiplier = 1;

        radios.forEach(radio => {
            if (radio.checked) {
                if (radio.value === 'plus50') {
                    days = 2;
                    multiplier = 1.5;
                } else if (radio.value === 'plus100') {
                    days = 1;
                    multiplier = 2;
                }
            }
        });

        const now = new Date();
        now.setDate(now.getDate() + days);
        const finishStr = now.toISOString().split('T')[0];
        document.getElementById('finish-date').textContent = 'Finish Date: ' + finishStr;

        const finalPrice = Math.floor(baseTotal * multiplier);
        document.getElementById('total-price').textContent = 'Total Price: Rp ' + finalPrice.toLocaleString('id-ID');
        document.getElementById('calculated_total').value = finalPrice;

    }

    document.querySelectorAll('input[name="finish_time"]').forEach(el => {
        el.addEventListener('change', calculateTotal);
    });
    ['complete_wash_weight', 'ironing_weight', 'bed_sheet_weight'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', calculateTotal);
        }
    
    });
}); 
