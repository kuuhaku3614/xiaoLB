
function updateExpiryDate(promoValue) {
    var membershipDate = document.getElementById('membership_date').value;
    var expiryDateField = document.getElementById('expiry_date');

    if (promoValue === "student") {
        expiryDateField.value = new Date(new Date(membershipDate).setMonth(new Date(membershipDate).getMonth() + 1)).toISOString().split('T')[0];
        expiryDateField.disabled = true;
    } else if (promoValue === "3months") {
        expiryDateField.value = new Date(new Date(membershipDate).setMonth(new Date(membershipDate).getMonth() + 3)).toISOString().split('T')[0];
        expiryDateField.disabled = true;
    } else {
        expiryDateField.disabled = true;
        expiryDateField.value = '';
    }
}
