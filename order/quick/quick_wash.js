const pricing = {
    'wash_only': 10000,
    'wash_dry': 20000,
    'wash_fold': 25000
};

function toggleSection(checkbox, sectionName) {
    const input = document.getElementById(`${sectionName}_weight`);
    input.disabled = !checkbox.checked;
    if (!checkbox.checked) input.value = '';
    updateSummary();
}

function updateSummary() {
    let total = 0;

    Object.keys(pricing).forEach(service => {
        const checkbox = document.getElementById(`${service}_checkbox`);
        const weightInput = document.getElementById(`${service}_weight`);

        if (checkbox.checked && weightInput.value) {
            const weight = parseFloat(weightInput.value);
            if (!isNaN(weight)) {
                total += pricing[service] * weight;
            }
        }
    });

    document.getElementById('total-price').textContent = `Total Price: Rp ${total.toLocaleString('id-ID')}`;
    document.getElementById('calculated_total').value = total;

    const now = new Date();
    document.getElementById('order_time').textContent = `Order Time: ${now.toLocaleString('id-ID')}`;

    // Finish time: +3 hours
    const finish = new Date(now.getTime() + 5 * 60 * 60 * 1000);
    document.getElementById('finish-date').textContent = `Finish Time: ${finish.toLocaleString('id-ID')}`;
}

// Listen to weight input changes
document.addEventListener("DOMContentLoaded", () => {
    Object.keys(pricing).forEach(service => {
        const weightInput = document.getElementById(`${service}_weight`);
        weightInput.addEventListener("input", updateSummary);
    });

    updateSummary(); // Set default values on load
});
