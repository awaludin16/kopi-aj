const radios = document.querySelectorAll('input[name="payment_method"]');
const payButton = document.getElementById("payButton");

function updateButton(value) {
    if (value === "cash") {
        payButton.textContent = "Bayar di Kasir";
    } else {
        payButton.textContent = "Bayar";
    }
}

// set default saat halaman load
const checkedRadio = document.querySelector(
    'input[name="payment_method"]:checked'
);
if (checkedRadio) {
    updateButton(checkedRadio.value);
}

// update saat user ganti pilihan
radios.forEach((radio) => {
    radio.addEventListener("change", function () {
        updateButton(this.value);
    });
});
